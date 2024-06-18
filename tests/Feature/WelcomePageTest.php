<?php

namespace Tests\Feature;

use App\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class WelcomePageTest extends TestCase
{
    #[Test]
    public function german_welcome_page_can_be_viewed(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertViewIs('welcome');
        $response->assertSee('<html lang="de">', escape: false);
        $response->assertSeeText('Willkommen beim Antragsportal');
    }

    #[Test]
    public function english_welcome_page_can_be_viewed(): void
    {
        $response = $this->get('/en');

        $response->assertOk();
        $response->assertViewIs('welcome');
        $response->assertSee('<html lang="en">', escape: false);
        $response->assertSeeText('Welcome to the application portal');
    }

    #[Test]
    public function german_welcome_page_redirects_if_authenticated(): void
    {
        $this->actingAs(User::factory()->create());

        $response = $this->get('/');

        $response->assertRedirect('/dashboard');
    }

    #[Test]
    public function english_welcome_page_redirects_if_authenticated(): void
    {
        $this->actingAs(User::factory()->create());

        $response = $this->get('/en');

        $response->assertRedirect('/dashboard');
    }
}
