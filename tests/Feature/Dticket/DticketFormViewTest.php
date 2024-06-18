<?php

namespace Tests\Feature\Dticket;

use App\Livewire\Dticket\SubmitDticketRequest;
use App\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class DticketFormViewTest extends TestCase
{
    #[Test]
    public function dticket_form_page_redirects_if_not_authenticated(): void
    {
        $response = $this->get('/requests/dticket');

        $response->assertRedirect('/');
    }

    #[Test]
    public function dticket_form_page_can_be_viewed_with_permission(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);
        $response = $this->get('/requests/dticket');

        $response->assertOk();
        $response->assertSeeLivewire(SubmitDticketRequest::class);
        $response->assertDontSeeText(__('stura.dticket.not_eligable.message'));
        $response->assertDontSeeText(__('stura.dticket.missing_config.message'));
    }
}
