<?php

namespace Tests\Feature\Commands;

use App\Models\Dticket\DticketExclude;
use App\Models\Dticket\DticketRequest;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UploadBlacklistCommandTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        DticketRequest::factory()
            ->for(User::factory()->create(['btu_id' => 'request_1']))
            ->create();

        DticketRequest::factory()
            ->for(User::factory()->create(['btu_id' => 'request_2']))
            ->create([
                'exclude_starts_at' => '2099-05-01',
                'exclude_ends_at' => '2099-08-31',
            ]);

        DticketRequest::factory()
            ->for(User::factory()->create(['btu_id' => 'request_3']))
            ->create([
                'exclude_starts_at' => '2022-05-01',
                'exclude_ends_at' => '2022-08-31',
            ]);

        DticketRequest::factory()
            ->for(User::factory()->create(['btu_id' => 'request_4']))
            ->create([
                'exclude_starts_at' => '2099-05-01',
                'exclude_ends_at' => '2099-08-31',
                'status' => 'rejected',
            ]);

        DticketRequest::factory()
            ->for(User::factory()->create(['btu_id' => 'request_5']))
            ->create([
                'exclude_starts_at' => '2099-05-01',
                'exclude_ends_at' => '2099-08-31',
                'status' => 'approved',
            ]);

        DticketRequest::factory()
            ->for(User::factory()->create(['btu_id' => 'request_6']))
            ->create([
                'exclude_starts_at' => '2099-05-01',
                'exclude_ends_at' => '2099-08-31',
                'status' => 'paid',
            ]);

        DticketRequest::factory()
            ->for(User::factory()->create(['btu_id' => 'test@example.com', 'email' => 'test@example.com']))
            ->create([
                'exclude_starts_at' => '2099-05-01',
                'exclude_ends_at' => '2099-08-31',
                'status' => 'paid',
            ]);

        DticketExclude::factory()->create([
            'btu_id' => 'manual_1',
            'exclude_starts_at' => '2099-04-01',
            'exclude_ends_at' => '2099-09-30',
        ]);

        DticketExclude::factory()->create([
            'btu_id' => 'manual_2',
            'exclude_starts_at' => '2099-05-01',
            'exclude_ends_at' => '2099-08-31',
        ]);

        DticketExclude::factory()->create([
            'btu_id' => 'manual_3',
            'exclude_starts_at' => '2022-05-01',
            'exclude_ends_at' => '2022-08-31',
        ]);

        DticketExclude::factory()->create([
            'btu_id' => 'manual_4',
            'exclude_starts_at' => '2022-05-01',
            'exclude_ends_at' => '2022-08-31',
            'is_active' => false,
        ]);

        Storage::fake('sftp');

        $this->artisan('app:upload-blacklist')
            ->assertExitCode(0);

        $this->assertSame(
            file_get_contents(__DIR__.'/blacklist.csv'),
            file_get_contents(storage_path('app/blacklist.csv'))
        );
    }
}
