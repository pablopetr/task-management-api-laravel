<?php

namespace Tests\Feature\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class LogoutControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_should_not_be_able_to_logout_if_user_is_not_authenticated(): void
    {
        $user = User::factory()->create();

        $token = auth()->login($user);

        $this->post(route('logout'), [], ['Authorization' => "Bearer $token-wrong"])
            ->assertStatus(200)
            ->assertJson(['message' => 'Already logged out']);
    }

    #[Test]
    public function it_should_be_able_to_logout(): void
    {
        $user = User::factory()->create();

        $token = auth()->login($user);

        $this->post(route('logout'), [], ['Authorization' => "Bearer $token"])
            ->assertStatus(200)
            ->assertJson(['message' => 'Successfully logged out']);
    }
}
