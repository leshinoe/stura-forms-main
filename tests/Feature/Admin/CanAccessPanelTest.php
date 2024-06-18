<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CanAccessPanelTest extends TestCase
{
    #[Test]
    public function guests_are_redirected(): void
    {
        $response = $this->get('/admin');

        $response->assertRedirect('/');
    }

    #[Test]
    public function users_cannot_access_admin_panel(): void
    {
        $this->actingAs(User::factory()->create());

        $response = $this->get('/admin');

        $response->assertForbidden();
    }

    #[Test]
    public function admins_can_access_admin_panel(): void
    {
        $this->actingAs(User::factory()->create([
            'is_admin' => true,
        ]));

        $response = $this->get('/admin');

        $response->assertOk();
    }
}
