<?php

namespace Tests\Feature\Http\Controllers\Auth;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class RegisterControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_should_validate_required_fields(): void
    {
        $this->post(route('register'))
            ->assertStatus(422)
            ->assertJson([
                'message' => 'Validation Error',
                'errors' => [
                    'name' => [__('validation.required', ['attribute' => 'name'])],
                    'email' => [__('validation.required', ['attribute' => 'email'])],
                    'password' => [__('validation.required', ['attribute' => 'password'])],
                ],
            ]);
    }

    #[Test]
    #[DataProvider('validatorProvider')]
    public function it_should_be_able_to_validate_fields($field, $value, $expectedMessage, $params = []): void
    {
        if ($value == 'johndoe@example.com') {
            User::factory()->create(['email' => 'johndoe@example.com']);
        }

        $this->post(route('register'), [
            $field => $value,
        ])
            ->assertStatus(422)
            ->assertJson([
                'message' => 'Validation Error',
                'errors' => [
                    $field => [__($expectedMessage, array_merge(['attribute' => $field], $params))],
                ],
            ]);
    }

    #[Test]
    public function it_should_validate_password_confirmation(): void
    {
        $this->post(route('register'), [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => 'password',
            'password_confirmation' => 'different_password',
        ])
            ->assertStatus(422)
            ->assertJson([
                'message' => 'Validation Error',
                'errors' => [
                    'password' => [__('validation.confirmed', ['attribute' => 'password'])],
                ],
            ]);
    }

    #[Test]
    public function it_should_be_able_to_register_user(): void
    {
        $this->freezeTime();

        $now = Carbon::now()->startOfSecond();

        $response = $this->post(route('register'), [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $user = User::query()->latest()->first();

        $response->assertStatus(201)
            ->assertJsonFragment([
                'message' => 'User registered successfully!',
                'user' => [
                    'id' => $user->id,
                    'name' => 'John Doe',
                    'email' => 'johndoe@example.com',
                    'email_verified_at' => null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
            ]);
    }

    public static function validatorProvider(): array
    {
        return [
            'Name required' => ['name', '', 'validation.required'],
            'Name string' => ['name', 1, 'validation.string'],
            'Name min' => ['name', 'a', 'validation.min.string', ['min' => 3]],
            'Name max' => ['name', Str::random(256), 'validation.max.string', ['max' => 255]],

            'Email required' => ['email', '', 'validation.required'],
            'Email string' => ['email', 1, 'validation.string'],
            'Email email' => ['email', 'invalid-email', 'validation.email'],
            'Email unique' => ['email', 'johndoe@example.com', 'validation.unique'],

            'Password required' => ['password', '', 'validation.required'],
            'Password string' => ['password', 12345678, 'validation.string'],
            'Password min' => ['password', '123', 'validation.min.string', ['min' => 8]],
            'Password max' => ['password', Str::random(256), 'validation.max.string', ['max' => 255]],
        ];
    }
}
