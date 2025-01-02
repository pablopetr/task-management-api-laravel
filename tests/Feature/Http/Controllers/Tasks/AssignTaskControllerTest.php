<?php

namespace Tests\Feature\Http\Controllers\Tasks;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AssignTaskControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_should_be_able_to_assign_a_task_to_an_user(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();
        $task = Task::factory()->create();

        $token = authorization($admin);

        $this->post(route('tasks.assign-task'), [
            'task_id' => $task->id,
            'user_id' => $user->id,
        ], $token)
            ->assertOk();

        $this->assertDatabaseHas(Task::class, [
            'id' => $task->id,
            'user_id' => $user->id,
        ]);
    }

    #[Test]
    public function it_should_validate_if_task_and_user_exists(): void
    {
        $admin = User::factory()->admin()->create();

        $token = authorization($admin);

        $this->post(route('tasks.assign-task'), [
            'task_id' => 999,
            'user_id' => 999,
        ], $token)
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'task_id',
                'user_id',
            ]);
    }

    #[Test]
    public function it_should_allow_only_admin_to_assign_task_to_a_user(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->create();


        $this->post(route('tasks.assign-task'), [
            'task_id' => $task->id,
            'user_id' => $user->id,
        ])->assertUnauthorized();
    }
}
