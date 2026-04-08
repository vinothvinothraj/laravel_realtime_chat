<?php

namespace App\Repositories\Eloquent;

use App\Enums\TaskStatus;
use App\Models\Task;
use App\Repositories\Contracts\TaskRepositoryInterface;
use Illuminate\Support\Collection;

class TaskRepository implements TaskRepositoryInterface
{
    public function forUserBoard(int $userId): Collection
    {
        return Task::query()
            ->with('user')
            ->orderByRaw($this->statusOrderExpression())
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();
    }

    public function findForUser(int $taskId, int $userId): ?Task
    {
        return Task::query()
            ->whereKey($taskId)
            ->first();
    }

    public function tasksForStatus(int $userId, string $status, ?int $exceptTaskId = null): Collection
    {
        return Task::query()
            ->where('status', $status)
            ->when($exceptTaskId, fn ($query) => $query->whereKeyNot($exceptTaskId))
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();
    }

    public function nextSortOrderForStatus(int $userId, string $status): int
    {
        $maxSortOrder = Task::query()
            ->where('status', $status)
            ->max('sort_order');

        return ((int) $maxSortOrder) + 1;
    }

    public function create(array $data): Task
    {
        return Task::create($data);
    }

    public function save(Task $task): Task
    {
        $task->save();

        return $task;
    }

    /**
     * @param  array<int, Task>  $tasks
     */
    public function reorderTasks(array $tasks, string $status): void
    {
        foreach (array_values($tasks) as $index => $task) {
            $task->forceFill([
                'status' => $status,
                'sort_order' => $index + 1,
            ]);

            $this->save($task);
        }
    }

    private function statusOrderExpression(): string
    {
        return collect(TaskStatus::ordered())
            ->map(fn (TaskStatus $status, int $index): string => "WHEN '{$status->value}' THEN " . ($index + 1))
            ->prepend('CASE status')
            ->push('END')
            ->implode(' ');
    }
}
