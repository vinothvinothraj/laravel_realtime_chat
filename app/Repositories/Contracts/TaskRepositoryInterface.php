<?php

namespace App\Repositories\Contracts;

use App\Models\Task;
use Illuminate\Support\Collection;

interface TaskRepositoryInterface
{
    public function forUserBoard(int $userId): Collection;

    public function findForUser(int $taskId, int $userId): ?Task;

    public function tasksForStatus(int $userId, string $status, ?int $exceptTaskId = null): Collection;

    public function nextSortOrderForStatus(int $userId, string $status): int;

    public function create(array $data): Task;

    public function save(Task $task): Task;

    /**
     * @param  array<int, Task>  $tasks
     */
    public function reorderTasks(array $tasks, string $status): void;
}
