<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AttachmentTest extends TestCase
{
    #[Test]
    public function attachmentCanBeShown()
    {
        Storage::fake();

        $user = User::factory()->create();
        Storage::put('attachments/'.$user->id.'/example.txt', 'txt-content');

        $response = $this->actingAs($user)->get('/attachments/'.$user->id.'/example.txt');

        $response->assertOk();
    }

    #[Test]
    public function attachmentCanBeShownToAdmins()
    {
        Storage::fake();

        $admin = User::factory()->create(['is_admin' => true]);
        $user = User::factory()->create();
        Storage::put('attachments/'.$user->id.'/example.txt', 'txt-content');

        $response = $this->actingAs($admin)->get('/attachments/'.$user->id.'/example.txt');

        $response->assertOk();
    }

    #[Test]
    public function attachmentCannotBeShownByOtherUser()
    {
        Storage::fake();

        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        Storage::put('attachments/'.$user->id.'/example.txt', 'txt-content');

        $response = $this->actingAs($user)->get('/attachments/'.$otherUser->id.'/example.pdf');
        $response->assertNotFound();
    }

    #[Test]
    public function attachmentCannotBeShownByGuest()
    {
        Storage::fake();

        $user = User::factory()->create();
        Storage::put('attachments/'.$user->id.'/example.txt', 'txt-content');

        $response = $this->get('/attachments/'.$user->id.'/example.pdf');
        $response->assertNotFound();
    }

    #[Test]
    public function attachmentCannotBeShownIfNotExists()
    {
        Storage::fake();

        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/attachments/'.$user->id.'/not-exists.pdf');
        $response->assertNotFound();
    }
}
