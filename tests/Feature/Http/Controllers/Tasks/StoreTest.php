<?php

namespace Feature\Http\Controllers\Tasks;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    public User $user;

    public array $authorization = [];

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->admin()->create();
    }

    #[Test]
    public function it_should_not_be_authorized_to_create_a_task(): void
    {
        $this->post(route('tasks.store'))
            ->assertUnauthorized();
    }

    #[Test]
    public function it_should_not_authorize_guests_to_create_a_task(): void
    {
        $user = User::factory()->create();

        $this->post(route('tasks.store'), authorization($user))
            ->assertUnauthorized();
    }

    #[Test]
    #[DataProvider('validationProvider')]
    public function it_should_validate_fields($field, $value, $rule): void
    {

        $response = $this->post(route('tasks.store'), [
            $field => $value,
        ], authorization($this->user));

        $response->assertStatus(422);
        $response->assertJsonFragment([
            'message' => 'Validation Error',
        ]);
        $response->assertJsonPath("errors.$field.0", __('validation.'.$rule, ['attribute' => str_replace('_', ' ', $field)]));
    }

    #[Test]
    public function it_should_be_able_to_create_a_task(): void
    {
        $response = $this->post(route('tasks.store'), [
            'user_id' => $this->user->id,
            'title' => 'Task Title',
            'description' => 'Task Description',
        ], authorization($this->user));

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
