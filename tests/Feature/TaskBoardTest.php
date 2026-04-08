<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskBoardTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_view_task_board(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('tasks.index'))
            ->assertOk()
            ->assertSee('Task Management');
    }

    public function test_user_can_create_and_move_tasks(): void
    {
        $user = User::factory()->create();

        $todoTask = Task::create([
            'user_id' => $user->id,
            'title' => 'Write documentation',
            'description' => null,
            'status' => 'todo',
            'sort_order' => 1,
        ]);

        $secondTodoTask = Task::create([
            'user_id' => $user->id,
            'title' => 'Review code',
            'description' => null,
            'status' => 'todo',
            'sort_order' => 2,
        ]);

        $inProgressTask = Task::create([
            'user_id' => $user->id,
            'title' => 'Implement board',
            'description' => null,
            'status' => 'in_progress',
            'sort_order' => 1,
        ]);

        $this->actingAs($user)
            ->post(route('tasks.store'), [
                'title' => 'New task',
                'description' => 'Created from the board',
                'status' => 'testing',
            ])
            ->assertRedirect(route('tasks.index'));

        $this->assertDatabaseHas('tasks', [
            'user_id' => $user->id,
            'title' => 'New task',
            'status' => 'testing',
        ]);

        $this->actingAs($user)
            ->patchJson(route('tasks.move', ['task' => $todoTask]), [
                'status' => 'in_progress',
                'position' => 0,
            ])
            ->assertOk();

        $this->assertDatabaseHas('tasks', [
            'id' => $todoTask->id,
            'status' => 'in_progress',
            'sort_order' => 1,
        ]);

        $this->assertDatabaseHas('tasks', [
            'id' => $inProgressTask->id,
            'status' => 'in_progress',
            'sort_order' => 2,
        ]);

        $this->assertDatabaseHas('tasks', [
            'id' => $secondTodoTask->id,
            'status' => 'todo',
            'sort_order' => 1,
        ]);
    }
}
