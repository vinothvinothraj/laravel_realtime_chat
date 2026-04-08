<?php

namespace App\Services;

use App\Enums\TaskStatus;
use App\Models\Task;
use App\Models\User;
use App\Repositories\Contracts\TaskRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

class TaskService
{
    public function __construct(
        protected TaskRepositoryInterface $tasks
    ) {
    }

    public function boardForUser(User $user): array
    {
        $tasks = $this->tasks->forUserBoard($user->id);
        $tasksByStatus = $tasks->groupBy(function (Task $task): string {
            return $task->status instanceof TaskStatus
                ? $task->status->value
                : (string) $task->status;
        });

        $columns = collect(TaskStatus::ordered())
            ->map(function (TaskStatus $status) use ($tasksByStatus): array {
                $columnTasks = $tasksByStatus->get($status->value, collect());

                return [
                    'value' => $status->value,
                    'label' => $status->label(),
                    'description' => $status->description(),
                    'color_classes' => $status->colorClasses(),
                    'tint_classes' => $status->tintClasses(),
                    'chip_classes' => $status->chipClasses(),
                    'tasks' => $columnTasks->values(),
                    'count' => $columnTasks->count(),
                ];
            })
            ->all();

        return [
            'columns' => $columns,
            'statusOptions' => collect(TaskStatus::ordered())
                ->map(fn (TaskStatus $status): array => [
                    'value' => $status->value,
                    'label' => $status->label(),
                ])
                ->all(),
            'users' => User::query()
                ->orderBy('name')
                ->get(['id', 'name'])
                ->map(fn (User $boardUser): array => [
                    'id' => $boardUser->id,
                    'name' => $boardUser->name,
                ])
                ->all(),
            'summary' => [
                'total' => $tasks->count(),
                'completed' => $tasksByStatus->get(TaskStatus::Completed->value, collect())->count(),
                'active' => $tasksByStatus->get(TaskStatus::Todo->value, collect())->count()
                    + $tasksByStatus->get(TaskStatus::InProgress->value, collect())->count()
                    + $tasksByStatus->get(TaskStatus::Testing->value, collect())->count(),
            ],
        ];
    }

    public function createTask(User $user, array $data): Task
    {
        $status = TaskStatus::from($data['status'] ?? TaskStatus::Todo->value);
        $title = trim($data['title']);
        $description = trim((string) ($data['description'] ?? ''));

        $task = $this->tasks->create([
            'user_id' => $user->id,
            'title' => $title,
            'description' => $description !== '' ? $description : null,
            'status' => $status->value,
            'sort_order' => $this->tasks->nextSortOrderForStatus($user->id, $status->value),
        ]);

        return $task->refresh();
    }

    public function moveTask(User $user, Task $task, array $data): Task
    {
        $targetStatus = TaskStatus::from($data['status']);
        $position = max(0, (int) ($data['position'] ?? 0));

        return DB::transaction(function () use ($user, $task, $targetStatus, $position): Task {
            $ownedTask = $this->tasks->findForUser($task->id, $user->id);

            if (! $ownedTask) {
                throw new ModelNotFoundException();
            }

            $sourceStatus = $ownedTask->status instanceof TaskStatus
                ? $ownedTask->status
                : TaskStatus::from((string) $ownedTask->status);

            $targetTasks = $this->tasks->tasksForStatus($user->id, $targetStatus->value, $ownedTask->id)->all();
            $insertAt = min($position, count($targetTasks));

            array_splice($targetTasks, $insertAt, 0, [$ownedTask]);
            $this->tasks->reorderTasks($targetTasks, $targetStatus->value);

            if ($sourceStatus->value !== $targetStatus->value) {
                $sourceTasks = $this->tasks->tasksForStatus($user->id, $sourceStatus->value, $ownedTask->id)->all();
                $this->tasks->reorderTasks($sourceTasks, $sourceStatus->value);
            }

            return $ownedTask->refresh();
        });
    }
}
