<?php

namespace App\Http\Controllers;

use App\Http\Requests\MoveTaskRequest;
use App\Http\Requests\StoreTaskRequest;
use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TaskController extends Controller
{
    public function __construct(
        protected TaskService $tasks
    ) {
    }

    public function index(): View
    {
        return view('tasks.index', $this->tasks->boardForUser(request()->user()));
    }

    public function store(StoreTaskRequest $request): RedirectResponse
    {
        $this->tasks->createTask($request->user(), $request->validated());

        return redirect()->route('tasks.index');
    }

    public function move(MoveTaskRequest $request, Task $task): JsonResponse
    {
        $updatedTask = $this->tasks->moveTask($request->user(), $task, $request->validated());

        return response()->json([
            'message' => 'Task position updated successfully.',
            'task' => [
                'id' => $updatedTask->id,
                'status' => $updatedTask->status instanceof \App\Enums\TaskStatus
                    ? $updatedTask->status->value
                    : (string) $updatedTask->status,
                'sort_order' => $updatedTask->sort_order,
            ],
        ]);
    }
}
