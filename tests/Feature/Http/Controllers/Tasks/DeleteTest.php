<?php

namespace Tests\Feature\Http\Controllers\Tasks;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class DeleteTest extends TestCase
{
    use RefreshDatabase;

    public User $user;

    public Task $task;

    public array $authorization;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $this->task = Task::factory()->create();
    }

    #[Test]
    public function it_should_not_be_authorized_to_delete_a_task(): void
    {
        $this->delete(route('tasks.destroy', ['task' => $this->task->id]))
            ->assertUnauthorized();
    }

    #[Test]
    public function it_should_be_able_to_delete_a_task(): void
    {
        $this->delete(route('tasks.destroy', ['task' => $this->task->id]), [], authorization($this->user))
            ->assertNoContent();
    }
}
