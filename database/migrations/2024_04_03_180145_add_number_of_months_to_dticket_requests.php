<?php

use App\Models\Dticket\DticketRequest;
use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('dticket_requests', function (Blueprint $table) {
            $table->tinyInteger('number_of_months')->nullable();
        });

        $dticketRequests = DticketRequest::all([
            'id', 'exclude_starts_at', 'exclude_ends_at',
        ]);

        foreach ($dticketRequests as $dticketRequest) {

            $startDate = Carbon::parse($dticketRequest->exclude_starts_at);
            $endDate = Carbon::parse($dticketRequest->exclude_ends_at);

            $dticketRequest->update([
                'number_of_months' => ceil($startDate->diffInMonths($endDate)),
            ]);
        }
    }
};
