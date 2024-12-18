<?php

namespace Tests\Feature\Http\Controllers\Tasks;

use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class IndexTest extends TestCase
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
    public function it_should_be_able_to_get_tasks_paginated(): void
    {
        $response = $this->get(route('tasks.index'), authorization($this->user))
            ->assertOk();

        $response->assertJsonCount(1, 'data');
        $response->assertJson([
            'data' => [
                [
                    'id' => $this->task->id,
                    'user_id' => $this->task->user_id,
                    'title' => $this->task->title,
                    'description' => $this->task->description,
                ],
            ],
        ]);
    }

    #[Test]
    public function it_should_be_able_to_get_tasks_paginated_with_limit(): void
    {
        $tasks = Task::factory()->count(20)->create();

        $this->get(route('tasks.index'), authorization($this->user))
            ->assertOk()
            ->assertJsonCount(20, 'data');

        $this->get(route('tasks.index', ['page' => 2]), authorization($this->user))
            ->assertOk()
            ->assertJsonCount(1, 'data');

        Carbon::now()->addDay();
        $lastTask = Task::factory()->create(['title' => 'My latest task']);

        $response = $this->get(route('tasks.index', ['page' => 1]), authorization($this->user))
            ->assertOk()
            ->assertJsonCount(20, 'data');

        $this->assertEquals($lastTask->id, $response['data'][0]['id']);

        $response = $this->get(route('tasks.index', ['page' => 1]), authorization($this->user))
            ->assertOk()
            ->assertJsonCount(20, 'data');

        $this->assertEquals($tasks->last()->id, $response['data'][1]['id']);
    }
}
