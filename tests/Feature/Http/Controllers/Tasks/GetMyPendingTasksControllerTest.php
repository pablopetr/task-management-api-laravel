<?php

namespace Tests\Feature\Http\Controllers\Tasks;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class GetMyPendingTasksControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_should_be_able_to_list_my_pending_tasks(): void
    {
        $user = User::factory()->create();

        $tasks = Task::factory(2)->create(['user_id' => $user->id]);

        $token = authorization($user);

        $response = $this->get(route('tasks.my-pending-tasks'), $token)
            ->assertOk();

        $data = json_decode($response->getContent())->data;

        $this->assertEquals($data[0]->id, $tasks[0]->id);
        $this->assertEquals($data[1]->id, $tasks[1]->id);
    }
}
