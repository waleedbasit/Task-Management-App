<?php

namespace Tests\Unit;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_task()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post('/tasks', [
            'title' => 'Test Task',
            'description' => 'This is a test task description.',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertDatabaseHas('tasks', [
            'user_id' => $user->id,
            'title' => 'Test Task',
            'description' => 'This is a test task description.',
            'completed' => false,
        ]);
    }

    public function test_user_can_update_task_status()
    {
        $user = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $user->id]);
        $this->actingAs($user);

        $response = $this->post('/tasks/' . $task->id, [
            'completed' => true,
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'completed' => true,
        ]);
    }

    public function test_user_can_delete_task()
    {
        $user = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $user->id]);
        $this->actingAs($user);

        $response = $this->delete('/tasks/' . $task->id);

        $response->assertRedirect(route('dashboard'));
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    public function test_user_can_retrieve_tasks()
    {
        $user = User::factory()->create();
        Task::factory(3)->create(['user_id' => $user->id]);
        $this->actingAs($user);

        $response = $this->get('/');

        $response->assertSuccessful();
        $response->assertViewIs('app');
        $response->assertViewHas('page.props.tasks');
    }

    public function test_guest_cannot_create_task()
    {
        $response = $this->post('/tasks', [
            'title' => 'Test Task',
            'description' => 'This is a test task description.',
        ]);

        $response->assertRedirect('/login');
        $this->assertDatabaseCount('tasks', 0);
    }

    public function test_guest_cannot_update_task()
    {
        $task = Task::factory()->create();

        $response = $this->post('/tasks/' . $task->id, [
            'completed' => true,
        ]);

        $response->assertRedirect('/login');
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'completed' => false,
        ]);
    }

    public function test_guest_cannot_delete_task()
    {
        $task = Task::factory()->create();

        $response = $this->delete('/tasks/' . $task->id);

        $response->assertRedirect('/login');
        $this->assertDatabaseHas('tasks', ['id' => $task->id]);
    }
}
