<?php

namespace Tests\Feature\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class LoginControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    #[DataProvider('validationProvider')]
    public function it_should_validate_fields($field, $value, $expectedMessage, $params = []): void
    {
        $this->post(route('login'), [
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
    public function it_should_not_login_user_with_wrong_credentials(): void
    {
        User::factory()->create();

        $this->post(route('login'), [
            'email' => 'johndoe@example.com',
            'password' => 'password',
        ])
            ->assertStatus(422)
            ->assertJson([
                'message' => 'Validation Error',
                'errors' => [
                    'email' => [__('auth.failed')],
                ],
            ]);
    }

    #[Test]
    public function it_should_allow_to_login_user(): void
    {
        $user = User::factory()->create(['password' => 'password']);

        $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'password',
        ])
            ->assertStatus(200)
            ->assertJsonStructure(['token']);
    }

    public static function validationProvider(): array
    {
        return [
            'Email required' => ['email', '', 'validation.required'],
            'Email string' => ['email', 1, 'validation.string'],
            'Email email' => ['email', 'invalid-email', 'validation.email'],

            'Password required' => ['password', '', 'validation.required'],
            'Password string' => ['password', 12345678, 'validation.string'],
        ];
    }
}
