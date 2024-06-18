<?php

namespace Tests\Feature\Dticket;

use App\Models\Dticket\DticketRequest;
use App\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class DticketViewTest extends TestCase
{
    #[Test]
    public function dticket_view_page_redirects_if_not_authenticated(): void
    {
        $response = $this->get('/requests/dticket');

        $response->assertRedirect('/');
    }

    #[Test]
    public function dticket_view_page_redirects_if_not_your_submission(): void
    {
        $this->actingAs(User::factory()->create());

        $dticketRequest = DticketRequest::factory()
            ->for(User::factory())
            ->create();

        $response = $this->get('/requests/dticket/'.$dticketRequest->getRouteKey());

        $response->assertNotFound();
    }

    #[Test]
    public function dticket_view_page_can_be_viewed_with_permission(): void
    {
        $user = User::factory()->withDticket()->create();
        $this->actingAs($user);

        $dticketRequest = DticketRequest::factory()
            ->for($user)
            ->create();

        $response = $this->get('/requests/dticket/'.$dticketRequest->getRouteKey());

        $response->assertOk();
        $response->assertSee($dticketRequest->monthsLabel());
    }
}
