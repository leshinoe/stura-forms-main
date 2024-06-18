<?php

namespace Tests\Feature\Dticket;

use App\Livewire\Dticket\SubmitDticketRequest;
use App\Models\Dticket\DticketRequest;
use App\Models\OneTimeToken;
use App\Models\User;
use App\Notifications\DticketRequestSubmitted;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class DticketFormSubmissionTest extends TestCase
{
    #[Test]
    public function dticket_form_can_show_correct_information(): void
    {
        App::setLocale('en');
        $user = User::factory()->withDticket()->create();

        Livewire::actingAs($user)->test(SubmitDticketRequest::class)
            ->assertSee('Exemption information')

            ->assertSee('Semester')
            ->assertSee('SoSe 2099')

            ->assertSee('Exemption starts at')
            ->assertSee(['01.04.2099', '01.05.2099', '01.06.2099', '01.07.2099', '01.08.2099', '01.09.2099'])

            ->assertSee('Exemption ends at')
            ->assertSee(['30.04.2099', '31.05.2099', '30.06.2099', '31.07.2099', '31.08.2099', '30.09.2099'])

            ->assertSee('Reason')
            ->assertSee('a) Stay outside the validity area for at least three consecutive months of the semester')
            ->assertSee('due to their studies, a practical semester, a semester abroad or as part of the final thesis (appropriate evidence must be provided).')
            ->assertSee('e) Severely disabled people who are entitled to transportation according to SGB IX')
            ->assertSee('Possession of the supplement to the severely disabled pass and the associated value mark must be attached accordingly.')
            ->assertSee('Comment')
            ->assertSee('Attachments')

            ->assertSee('Reimbursement information')
            ->assertSee('Account Holder')
            ->assertSee('IBAN')
            ->assertSee('BIC')

            ->assertSee('Consents')
            ->assertSee('I agree that my data may be stored and processed for the exemption and reimbursment process.')
            ->assertSee('I agree that my ticket authorization for the selected timeframe will be cancelled immediately after submitting the form. If the application is rejected, the ticket authorization will be restored.')
            ->assertSee('I confirm that all information provided is true and that I will not submit two exemption applications for the same semester.');
    }

    #[Test]
    public function dticket_form_can_be_filled_in_completly(): void
    {
        Storage::fake();
        Notification::fake();
        $user = User::factory()->withDticket()->create();

        $file1 = UploadedFile::fake()->image('file-1.jpg');
        $file2 = UploadedFile::fake()->create('file-2.pdf', 1000, 'application/pdf');
        $file3 = UploadedFile::fake()->image('file-3.png');

        Livewire::actingAs($user)->test(SubmitDticketRequest::class)
            ->assertSet('data.semester', 'SoSe 2099')
            ->set('data.exclude_starts_at', '2099-05-01')
            ->set('data.exclude_ends_at', '2099-08-31')
            ->set('data.reason', 'a')
            ->set('data.comment', 'This is a comment')
            ->set('data.banking_name', 'Max Mustermann')
            ->set('data.banking_iban', 'DE31500105172153735839')
            ->set('data.banking_bic', 'INGDDEFFXXX')
            ->set('data.attachments.0', $file1)
            ->set('data.attachments.1', $file2)
            ->set('data.attachments.2', $file3)
            ->set('data.consent', true)
            ->set('data.consent2', true)
            ->set('data.consent3', true)
            ->call('store')
            ->assertHasNoErrors();

        $this->assertDatabaseCount('dticket_requests', 1);
        $this->assertDatabaseHas('dticket_requests', [
            'user_id' => $user->id,
            'semester' => 'SoSe 2099',
            'exclude_starts_at' => '2099-05-01',
            'exclude_ends_at' => '2099-08-31',
            'reason' => 'a',
            'comment' => 'This is a comment',
            'banking_name' => 'Max Mustermann',
            'banking_iban' => 'DE31 5001 0517 2153 7358 39',
            'banking_bic' => 'INGDDEFFXXX',
            'status' => 'pending',
            'number_of_months' => 4,
        ]);

        $dticketRequest = DticketRequest::first();

        $this->assertSame(
            'file-1.jpg',
            $dticketRequest->attachment_filenames[$dticketRequest->attachments[0]]
        );

        $this->assertSame(
            'file-2.pdf',
            $dticketRequest->attachment_filenames[$dticketRequest->attachments[1]]
        );

        $this->assertSame(
            'file-3.png',
            $dticketRequest->attachment_filenames[$dticketRequest->attachments[2]]
        );

        Storage::assertExists($dticketRequest->attachments[0]);
        Storage::assertExists($dticketRequest->attachments[1]);
        Storage::assertExists($dticketRequest->attachments[2]);

        Notification::assertSentTo($user, DticketRequestSubmitted::class);
    }

    #[Test]
    public function dticket_form_can_be_filled_in_completly_with_no_dticket_entitlement(): void
    {
        Storage::fake();
        Notification::fake();
        $user = User::factory()->create();

        $file1 = UploadedFile::fake()->image('file-1.jpg');
        $file2 = UploadedFile::fake()->create('file-2.pdf', 1000, 'application/pdf');
        $file3 = UploadedFile::fake()->image('file-3.png');

        Livewire::actingAs($user)->test(SubmitDticketRequest::class)
            ->assertSet('data.semester', 'SoSe 2099')
            ->set('data.exclude_starts_at', '2099-05-01')
            ->set('data.exclude_ends_at', '2099-08-31')
            ->set('data.reason', 'a')
            ->set('data.comment', 'This is a comment')
            ->set('data.banking_name', 'Max Mustermann')
            ->set('data.banking_iban', 'DE31500105172153735839')
            ->set('data.banking_bic', 'INGDDEFFXXX')
            ->set('data.attachments.0', $file1)
            ->set('data.attachments.1', $file2)
            ->set('data.attachments.2', $file3)
            ->set('data.consent', true)
            ->set('data.consent2', true)
            ->set('data.consent3', true)
            ->call('store')
            ->assertHasNoErrors();

        $this->assertDatabaseCount('dticket_requests', 1);
        $this->assertDatabaseHas('dticket_requests', [
            'user_id' => $user->id,
            'semester' => 'SoSe 2099',
            'exclude_starts_at' => '2099-05-01',
            'exclude_ends_at' => '2099-08-31',
            'reason' => 'a',
            'comment' => 'This is a comment',
            'banking_name' => 'Max Mustermann',
            'banking_iban' => 'DE31 5001 0517 2153 7358 39',
            'banking_bic' => 'INGDDEFFXXX',
            'status' => 'pending',
            'number_of_months' => 4,
            'alerts' => 'no-dticket-entitlement',
        ]);

        $dticketRequest = DticketRequest::first();

        $this->assertSame(
            'file-1.jpg',
            $dticketRequest->attachment_filenames[$dticketRequest->attachments[0]]
        );

        $this->assertSame(
            'file-2.pdf',
            $dticketRequest->attachment_filenames[$dticketRequest->attachments[1]]
        );

        $this->assertSame(
            'file-3.png',
            $dticketRequest->attachment_filenames[$dticketRequest->attachments[2]]
        );

        Storage::assertExists($dticketRequest->attachments[0]);
        Storage::assertExists($dticketRequest->attachments[1]);
        Storage::assertExists($dticketRequest->attachments[2]);

        Notification::assertSentTo($user, DticketRequestSubmitted::class);
    }

    #[Test]
    public function dticket_form_can_be_filled_in_completly_with_existing_login_links(): void
    {
        Storage::fake();
        Notification::fake();
        $user = User::factory()->create();
        OneTimeToken::factory()->for($user)->create();

        $file1 = UploadedFile::fake()->image('file-1.jpg');
        $file2 = UploadedFile::fake()->create('file-2.pdf', 1000, 'application/pdf');
        $file3 = UploadedFile::fake()->image('file-3.png');

        Livewire::actingAs($user)->test(SubmitDticketRequest::class)
            ->assertSet('data.semester', 'SoSe 2099')
            ->set('data.exclude_starts_at', '2099-05-01')
            ->set('data.exclude_ends_at', '2099-08-31')
            ->set('data.reason', 'a')
            ->set('data.comment', 'This is a comment')
            ->set('data.banking_name', 'Max Mustermann')
            ->set('data.banking_iban', 'DE31500105172153735839')
            ->set('data.banking_bic', 'INGDDEFFXXX')
            ->set('data.attachments.0', $file1)
            ->set('data.attachments.1', $file2)
            ->set('data.attachments.2', $file3)
            ->set('data.consent', true)
            ->set('data.consent2', true)
            ->set('data.consent3', true)
            ->call('store')
            ->assertHasNoErrors();

        $this->assertDatabaseCount('dticket_requests', 1);
        $this->assertDatabaseHas('dticket_requests', [
            'user_id' => $user->id,
            'semester' => 'SoSe 2099',
            'exclude_starts_at' => '2099-05-01',
            'exclude_ends_at' => '2099-08-31',
            'reason' => 'a',
            'comment' => 'This is a comment',
            'banking_name' => 'Max Mustermann',
            'banking_iban' => 'DE31 5001 0517 2153 7358 39',
            'banking_bic' => 'INGDDEFFXXX',
            'status' => 'pending',
            'number_of_months' => 4,
            'alerts' => 'has-login-link',
        ]);

        $dticketRequest = DticketRequest::first();

        $this->assertSame(
            'file-1.jpg',
            $dticketRequest->attachment_filenames[$dticketRequest->attachments[0]]
        );

        $this->assertSame(
            'file-2.pdf',
            $dticketRequest->attachment_filenames[$dticketRequest->attachments[1]]
        );

        $this->assertSame(
            'file-3.png',
            $dticketRequest->attachment_filenames[$dticketRequest->attachments[2]]
        );

        Storage::assertExists($dticketRequest->attachments[0]);
        Storage::assertExists($dticketRequest->attachments[1]);
        Storage::assertExists($dticketRequest->attachments[2]);

        Notification::assertSentTo($user, DticketRequestSubmitted::class);
    }

    #[Test]
    public function dticket_form_requires_semester(): void
    {
        $user = User::factory()->withDticket()->create();

        Livewire::actingAs($user)->test(SubmitDticketRequest::class)
            ->set('data.semester', '')
            ->call('store')
            ->assertHasErrors([
                'data.semester' => ['required'],
            ]);
    }

    #[Test]
    public function dticket_form_requires_exclude_starts_at(): void
    {
        $user = User::factory()->withDticket()->create();

        Livewire::actingAs($user)->test(SubmitDticketRequest::class)
            ->call('store')
            ->assertHasErrors([
                'data.exclude_starts_at' => ['required'],
            ]);
    }

    #[Test]
    public function dticket_form_valid_option_for_exclude_starts_at(): void
    {
        $user = User::factory()->withDticket()->create();

        Livewire::actingAs($user)->test(SubmitDticketRequest::class)
            ->set('data.exclude_starts_at', '2099-02-01')
            ->call('store')
            ->assertHasErrors([
                'data.exclude_starts_at' => ['in'],
            ]);
    }

    #[Test]
    public function dticket_form_requires_exclude_ends_at(): void
    {
        $user = User::factory()->withDticket()->create();

        Livewire::actingAs($user)->test(SubmitDticketRequest::class)
            ->call('store')
            ->assertHasErrors([
                'data.exclude_ends_at' => ['required'],
            ]);
    }

    #[Test]
    public function dticket_form_valid_option_for_exclude_ends_at(): void
    {
        $user = User::factory()->withDticket()->create();

        Livewire::actingAs($user)->test(SubmitDticketRequest::class)
            ->set('data.exclude_ends_at', '2099-10-31')
            ->call('store')
            ->assertHasErrors([
                'data.exclude_ends_at' => ['in'],
            ]);
    }

    #[Test]
    public function dticket_form_exclude_ends_at_is_after_exclude_starts_at(): void
    {
        $user = User::factory()->withDticket()->create();

        Livewire::actingAs($user)->test(SubmitDticketRequest::class)
            ->set('data.exclude_starts_at', '2099-08-01')
            ->set('data.exclude_ends_at', '2099-07-31')
            ->call('store')
            ->assertHasErrors([
                'data.exclude_ends_at' => ['after'],
            ]);
    }

    #[Test]
    public function dticket_form_requires_reason(): void
    {
        $user = User::factory()->withDticket()->create();

        Livewire::actingAs($user)->test(SubmitDticketRequest::class)
            ->call('store')
            ->assertHasErrors([
                'data.reason' => ['required'],
            ]);
    }

    #[Test]
    public function dticket_form_valid_option_for_reason(): void
    {
        $user = User::factory()->withDticket()->create();

        Livewire::actingAs($user)->test(SubmitDticketRequest::class)
            ->set('data.reason', 'z')
            ->call('store')
            ->assertHasErrors([
                'data.reason' => ['in'],
            ]);
    }

    #[Test]
    public function dticket_form_optional_comments_field(): void
    {
        $user = User::factory()->withDticket()->create();

        Livewire::actingAs($user)->test(SubmitDticketRequest::class)
            ->set('data.comment', null)
            ->call('store')
            ->assertHasNoErrors(['data.comment']);
    }

    #[Test]
    public function dticket_form_requires_attachments(): void
    {
        $user = User::factory()->withDticket()->create();

        Livewire::actingAs($user)->test(SubmitDticketRequest::class)
            ->call('store')
            ->assertHasErrors([
                'data.attachments' => ['required'],
            ]);
    }

    #[Test]
    public function dticket_form_max_5_attachments(): void
    {
        $user = User::factory()->withDticket()->create();

        Livewire::actingAs($user)->test(SubmitDticketRequest::class)
            ->set('data.attachments', [
                UploadedFile::fake()->image('file-1.jpg'),
                UploadedFile::fake()->image('file-2.jpg'),
                UploadedFile::fake()->image('file-3.jpg'),
                UploadedFile::fake()->image('file-4.jpg'),
                UploadedFile::fake()->image('file-5.jpg'),
                UploadedFile::fake()->image('file-6.jpg'),
            ])
            ->call('store')
            ->assertHasErrors([
                'data.attachments' => ['max:5'],
            ]);
    }

    #[Test]
    public function dticket_form_max_5_mb_attachments(): void
    {
        $user = User::factory()->withDticket()->create();

        Livewire::actingAs($user)->test(SubmitDticketRequest::class)
            ->set('data.attachments', [
                UploadedFile::fake()->image('file-1.jpg')->size(1024 * 5 + 1),
            ])
            ->call('store')
            ->assertHasErrors([
                'data.attachments' => ['The attachments must not be greater than 5120 kilobytes.'],
            ]);
    }

    #[Test]
    public function dticket_form_only_allowed_typed_attachments(): void
    {
        $user = User::factory()->withDticket()->create();

        Livewire::actingAs($user)->test(SubmitDticketRequest::class)
            ->set('data.attachments', [
                UploadedFile::fake()->create('file-1.jpg', 1000, 'text/plain'),
            ])
            ->call('store')
            ->assertHasErrors([
                'data.attachments',
            ]);
    }

    #[Test]
    public function dticket_form_requires_banking_name(): void
    {
        $user = User::factory()->withDticket()->create();

        Livewire::actingAs($user)->test(SubmitDticketRequest::class)
            ->call('store')
            ->assertHasErrors([
                'data.banking_name' => ['required'],
            ]);
    }

    #[Test]
    public function dticket_form_max_255_chars_for_banking_name(): void
    {
        $user = User::factory()->withDticket()->create();

        Livewire::actingAs($user)->test(SubmitDticketRequest::class)
            ->set('data.banking_name', str_repeat('a', 256))
            ->call('store')
            ->assertHasErrors([
                'data.banking_name' => ['max'],
            ]);
    }

    #[Test]
    public function dticket_form_requires_banking_iban(): void
    {
        $user = User::factory()->withDticket()->create();

        Livewire::actingAs($user)->test(SubmitDticketRequest::class)
            ->call('store')
            ->assertHasErrors([
                'data.banking_iban' => ['required'],
            ]);
    }

    #[Test]
    public function dticket_form_max_255_chars_for_banking_iban(): void
    {
        $user = User::factory()->withDticket()->create();

        Livewire::actingAs($user)->test(SubmitDticketRequest::class)
            ->set('data.banking_iban', str_repeat('a', 256))
            ->call('store')
            ->assertHasErrors([
                'data.banking_iban' => ['max'],
            ]);
    }

    #[Test]
    public function dticket_form_valid_iban_for_banking_iban(): void
    {
        $user = User::factory()->withDticket()->create();

        Livewire::actingAs($user)->test(SubmitDticketRequest::class)
            ->set('data.banking_iban', 'abc')
            ->call('store')
            ->assertHasErrors([
                'data.banking_iban' => ['iban'],
            ]);
    }

    #[Test]
    public function dticket_form_requires_banking_bic(): void
    {
        $user = User::factory()->withDticket()->create();

        Livewire::actingAs($user)->test(SubmitDticketRequest::class)
            ->call('store')
            ->assertHasErrors([
                'data.banking_bic' => ['required'],
            ]);
    }

    #[Test]
    public function dticket_form_max_255_chars_for_banking_bic(): void
    {
        $user = User::factory()->withDticket()->create();

        Livewire::actingAs($user)->test(SubmitDticketRequest::class)
            ->set('data.banking_bic', str_repeat('a', 256))
            ->call('store')
            ->assertHasErrors([
                'data.banking_bic' => ['max'],
            ]);
    }

    #[Test]
    public function dticket_form_valid_bic_for_banking_bic(): void
    {
        $user = User::factory()->withDticket()->create();

        Livewire::actingAs($user)->test(SubmitDticketRequest::class)
            ->set('data.banking_bic', 'abc')
            ->call('store')
            ->assertHasErrors([
                'data.banking_bic' => ['bic'],
            ]);
    }

    #[Test]
    public function dticket_form_requires_consent_1(): void
    {
        $user = User::factory()->withDticket()->create();

        Livewire::actingAs($user)->test(SubmitDticketRequest::class)
            ->call('store')
            ->assertHasErrors([
                'data.consent' => ['accepted'],
            ]);
    }

    #[Test]
    public function dticket_form_requires_consent_2(): void
    {
        $user = User::factory()->withDticket()->create();

        Livewire::actingAs($user)->test(SubmitDticketRequest::class)
            ->call('store')
            ->assertHasErrors([
                'data.consent2' => ['accepted'],
            ]);
    }

    #[Test]
    public function dticket_form_requires_consent_3(): void
    {
        $user = User::factory()->withDticket()->create();

        Livewire::actingAs($user)->test(SubmitDticketRequest::class)
            ->call('store')
            ->assertHasErrors([
                'data.consent3' => ['accepted'],
            ]);
    }

    #[Test]
    public function dticket_form_cannot_set_some_attributes(): void
    {
        Storage::fake();
        $user = User::factory()->withDticket()->create();

        Livewire::actingAs($user)->test(SubmitDticketRequest::class)
            ->assertSet('data.semester', 'SoSe 2099')
            ->set('data.exclude_starts_at', '2099-05-01')
            ->set('data.exclude_ends_at', '2099-08-31')
            ->set('data.reason', 'a')
            ->set('data.comment', 'This is a comment')
            ->set('data.banking_name', 'Max Mustermann')
            ->set('data.banking_iban', 'DE31500105172153735839')
            ->set('data.banking_bic', 'INGDDEFFXXX')
            ->set('data.attachments', [
                UploadedFile::fake()->image('file-1.jpg'),
            ])
            ->set('data.consent', true)
            ->set('data.consent2', true)
            ->set('data.consent3', true)
            ->set('data.number_of_months', 10) // Should not be set
            ->set('data.status', 'approved') // Should not be set
            ->set('data.user_id', 999) // Should not be set
            ->call('store')
            ->assertHasNoErrors();

        $this->assertDatabaseCount('dticket_requests', 1);
        $this->assertDatabaseHas('dticket_requests', [
            'user_id' => $user->id,
            'semester' => 'SoSe 2099',
            'exclude_starts_at' => '2099-05-01',
            'exclude_ends_at' => '2099-08-31',
            'reason' => 'a',
            'comment' => 'This is a comment',
            'banking_name' => 'Max Mustermann',
            'banking_iban' => 'DE31 5001 0517 2153 7358 39',
            'banking_bic' => 'INGDDEFFXXX',
            'status' => 'pending',
            'number_of_months' => 4,
        ]);
    }
}
