<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ProfileControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_should_not_be_able_to_get_profile_information_when_user_is_guest(): void
    {
        $this->get(route('profile.show'))
            ->assertUnauthorized();
    }

    #[Test]
    public function it_should_be_able_to_get_profile_information(): void
    {
        $user = User::factory()->create();

        $response = $this->get(route('me'), authorization($user));

        $this->get(route('me'), authorization($user))
            ->assertOk()
            ->assertJson([
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'email_verified_at' => $user->email_verified_at->format('Y-m-d H:i:s'),
                'role' => $user->role,
                'created_at' => $user->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $user->updated_at->format('Y-m-d H:i:s'),
            ]);
    }
}
