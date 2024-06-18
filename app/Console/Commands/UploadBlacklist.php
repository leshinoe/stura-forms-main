<?php

namespace App\Console\Commands;

use App\Models\Dticket\DticketExclude;
use App\Models\Dticket\DticketRequest;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Storage;
use Spatie\SimpleExcel\SimpleExcelWriter;

class UploadBlacklist extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:upload-blacklist';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Upload the blacklist to the server.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Get all non-rejected requests
        $requests = DticketRequest::query()
            ->where('status', '!=', 'rejected')
            ->whereDate('exclude_ends_at', '>=', today())
            ->with([
                'user' => fn ($query) => $query->select('id', 'btu_id', 'email'),
            ])
            ->get(['user_id', 'exclude_starts_at', 'exclude_ends_at']);

        // Get all manual excludes
        $excludes = DticketExclude::query()
            ->where('is_active', true)
            ->whereDate('exclude_ends_at', '>=', today())
            ->get(['btu_id', 'exclude_starts_at', 'exclude_ends_at']);

        // Create a CSV file
        SimpleExcelWriter::create(
            storage_path('app/blacklist.csv'),
            delimiter: ';',
            shouldAddBom: false
        )
            ->addHeader([
                'Matrikelnummer',
                'Vorname',
                'Nachname',
                'DatumVon',
                'DatumBis',
            ])
            ->addRows($this->mappedData($requests))
            ->addRows($this->mappedData($excludes))
            ->close();

        // Upload the CSV file to the server
        Storage::disk('sftp')->put(
            'blacklist.csv',
            file_get_contents(storage_path('app/blacklist.csv'))
        );
    }

    protected function mappedData(Collection $excludes): array
    {
        return $excludes->map(function ($exclude) {
            $btuId = $exclude->user->btu_id ?? $exclude->btu_id;

            if ($btuId === $exclude->user?->email) {
                return null;
            }

            return [
                'Matrikelnummer' => $btuId,
                'Vorname' => '',
                'Nachname' => '',
                'DatumVon' => Carbon::parse($exclude->exclude_starts_at)->format('Y-m-d'),
                'DatumBis' => Carbon::parse($exclude->exclude_ends_at)->format('Y-m-d'),
            ];
        })->filter()->values()->all();
    }
}
