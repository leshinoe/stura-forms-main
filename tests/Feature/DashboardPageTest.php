<?php

namespace Tests\Feature;

use App\Models\Dticket\DticketRequest;
use App\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class DashboardPageTest extends TestCase
{
    #[Test]
    public function dashboard_page_redirects_if_not_authenticated(): void
    {
        $response = $this->get('/dashboard');

        $response->assertRedirect('/');
    }

    #[Test]
    public function german_dashboard_page_can_be_viewed(): void
    {
        $user = User::factory()->create(['locale' => 'de']);
        $this->actingAs($user);

        $response = $this->get('/dashboard');

        $response->assertOk();
        $response->assertViewIs('dashboard');
        $response->assertSeeText('Dashboard');
        $response->assertSee('<html lang="de">', escape: false);
        $response->assertSeeText("Hallo $user->name!");
        $response->assertDontSeeText('SoSe 2022');
    }

    #[Test]
    public function english_dashboard_page_can_be_viewed(): void
    {
        $user = User::factory()->create(['locale' => 'en']);
        $this->actingAs($user);

        $response = $this->get('/dashboard');

        $response->assertOk();
        $response->assertViewIs('dashboard');
        $response->assertSeeText('Dashboard');
        $response->assertSee('<html lang="en">', escape: false);
        $response->assertSeeText("Hello $user->name!");
        $response->assertDontSeeText('SoSe 2022');
    }

    #[Test]
    public function dashboard_page_shows_dticket_requests()
    {
        $user = User::factory()->has(DticketRequest::factory())->create();
        $this->actingAs($user);

        $response = $this->get('/dashboard');

        $response->assertOk();
        $response->assertViewIs('dashboard');
        $response->assertSeeText('Dashboard');
        $response->assertSeeText(__('stura.dashboard.dticket_section_title'));
        $response->assertSeeText('SoSe 2099');
    }
}
