<?php

namespace Feature\Http\Controllers\Tasks;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    use RefreshDatabase;

    public User $user;

    public Task $task;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->task = Task::factory()->create();
    }

    #[Test]
    public function it_should_not_be_authorized_to_update_a_task(): void
    {
        $this->put(route('tasks.update', ['task' => $this->task->id]))
            ->assertUnauthorized();
    }

    #[Test]
    #[DataProvider('validationProvider')]
    public function it_should_be_able_to_validate_fields($field, $value, $rule): void
    {
        $response = $this->put(route('tasks.update', ['task' => $this->task->id]), [
            $field => $value,
        ], authorization($this->user));

        $response->assertStatus(422);
        $response->assertJsonFragment([
            'message' => 'Validation Error',
        ]);

        $response->assertJsonPath("errors.$field.0", __('validation.'.$rule, ['attribute' => str_replace('_', ' ', $field)]));
    }

    #[Test]
    public function it_should_update_a_task(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $user->id]);

        $this->put(route('tasks.update', ['task' => $task->id]), [
            'user_id' => $user->id,
            'title' => 'Task Title',
            'description' => 'Task Description',
        ], authorization($this->user))
            ->assertOk();
    }

    public static function validationProvider(): array
    {
        return [
            'User Id required' => ['user_id', '', 'required'],
            'User Id exists' => ['user_id', -1, 'exists'],
            'Title required' => ['title', '', 'required'],
            'Description required' => ['description', '', 'required'],
        ];
    }
}
