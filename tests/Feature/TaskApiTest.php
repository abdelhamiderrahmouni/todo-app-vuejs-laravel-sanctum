<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class TaskApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_user_can_access_home_page_when_authenticated(): void
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );

        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_user_can_create_task(): void
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );

        $formData = [
            'name' => 'Task Title',
            'body' => 'Task Description',
        ];

        $this->post('/api/tasks', $formData)
            ->assertStatus(201);
    }

    public function test_user_can_update_task(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs(
            $user,
            ['*']
        );

        $task = $user->tasks()->create([
            'name' => 'Task Title',
            'body' => 'Task Description',
        ]);

        $formData = [
            'name' => 'Task Title Updated',
            'body' => 'Task Description Updated',
        ];

        $this->put("/api/tasks/{$task->id}", $formData)
            ->assertStatus(200);
    }

    public function test_user_can_delete_task(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs(
            $user,
            ['*']
        );

        $task = $user->tasks()->create([
            'name' => 'Task Title',
            'body' => 'Task Description',
        ]);

        $this->delete("/api/tasks/{$task->id}")
            ->assertStatus(204);

        $this->assertDatabaseMissing('tasks', [
            'id' => $task->id,
        ]);
    }
}
