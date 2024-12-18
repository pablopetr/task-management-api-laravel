<?php

namespace Feature\Http\Controllers\Tasks;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CreateTaskControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_should_not_be_authorized_to_create_a_task(): void
    {
        $this->post(route('tasks.create'))
            ->assertUnauthorized();
    }

    #[Test]
    #[DataProvider('validationProvider')]
    public function it_should_be_able_to_validate_fields($field, $value, $rule): void
    {
        $user = User::factory()->create();

        $token = auth()->login($user);

        $response = $this->post(route('tasks.create'), [
            $field => $value,
        ], ['Authorization' => "Bearer $token"]);


        $response->assertStatus(422);
        $response->assertJsonFragment([
            'message' => 'Validation Error',
        ]);
        $response->assertJsonPath("errors.$field.0", __('validation.' . $rule, ['attribute' => str_replace('_', ' ', $field)]));
    }

    #[Test]
    public function it_should_be_able_to_create_a_task(): void
    {
        $user = User::factory()->create();

        $token = auth()->login($user);

        $response = $this->post(route('tasks.create'), [
           'user_id' => $user->id,
            'title' => 'Task Title',
            'description' => 'Task Description',
        ], ['Authorization' => "Bearer $token"]);

        $response->assertCreated();
    }

    public static function validationProvider(): array
    {
        return [
            'User Id required' => ['user_id', '', 'required'],
            'UserId exists' => ['user_id', -1, 'exists'],
            'Title required' => ['title', '', 'required'],
            'Description required' => ['description', '', 'required'],
        ];
    }
}
