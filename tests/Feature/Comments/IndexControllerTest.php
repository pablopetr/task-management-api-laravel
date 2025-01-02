<?php

namespace Tests\Feature\Comments;

use App\Models\Comment;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class IndexControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function guest_should_not_be_able_to_see_comments(): void
    {
        $task = Task::factory()->create();

        $this->get(route('tasks.comments', ['task' => $task]))
            ->assertUnauthorized();
    }

    #[Test]
    public function it_should_be_able_to_list_comments(): void
    {
        $user = User::factory()->create();

        $task = Task::factory()->create();
        $comments = Comment::factory(2)->create(['user_id' => $user->id, 'task_id' => $task->id]);

        $token = authorization($user);

        $response = $this->get(route('tasks.comments', ['task' => $task]), $token)
            ->assertOk();

        $data = json_decode($response->getContent())->data;

        $this->assertCount(2, $data);
        $this->assertEquals($comments[0]->id, $data[0]->id);
        $this->assertEquals($comments[1]->id, $data[1]->id);
    }

    #[Test]
    public function it_should_not_be_able_to_list_comments_from_other_task(): void
    {
        $user = User::factory()->create();

        $task = Task::factory()->create();
        $comments = Comment::factory(2)->create(['user_id' => $user->id, 'task_id' => $task->id]);
        Comment::factory()->create(['user_id' => $user->id]);

        $token = authorization($user);

        $response = $this->get(route('tasks.comments', ['task' => $task]), $token)
            ->assertOk();

        $data = json_decode($response->getContent())->data;

        $this->assertCount(2, $data);
        $this->assertEquals($comments[0]->id, $data[0]->id);
        $this->assertEquals($comments[1]->id, $data[1]->id);
    }
}
