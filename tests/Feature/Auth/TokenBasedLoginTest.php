<?php

namespace Tests\Feature\Auth;

use App\Models\OneTimeToken;
use App\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class TokenBasedLoginTest extends TestCase
{
    #[Test]
    public function test_login_with_valid_token()
    {
        $user = User::factory()->create();
        OneTimeToken::factory()->for($user)->create([
            'token' => 'valid-token',
        ]);

        $this->assertGuest();
        $response = $this->get('/auth/token/valid-token');

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($user);
    }

    #[Test]
    public function test_login_with_invalid_token()
    {
        $user = User::factory()->create();
        OneTimeToken::factory()->for($user)->create([
            'token' => 'valid-token',
        ]);

        $this->assertGuest();
        $response = $this->get('/auth/token/invalid-token');

        $response->assertRedirect('/');
        $this->assertGuest();
    }

    #[Test]
    public function test_login_with_expired_token()
    {
        $user = User::factory()->create();
        OneTimeToken::factory()->for($user)->create([
            'token' => 'expired-token',
            'expires_at' => now()->subDay(),
        ]);

        $this->assertGuest();
        $response = $this->get('/auth/token/expired-token');

        $response->assertRedirect('/');
        $this->assertGuest();
    }

    #[Test]
    public function test_login_with_legacy_link()
    {
        $user = User::factory()->create();
        OneTimeToken::factory()->for($user)->create([
            'token' => 'valid-token',
        ]);

        $this->assertGuest();
        $response = $this->get('/requests/dticket?token=valid-token');

        $response->assertRedirect('/auth/token/valid-token');
        $this->assertGuest();
    }
}
