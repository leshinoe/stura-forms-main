<?php

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
        Schema::table('one_time_tokens', function (Blueprint $table) {
            $table->dropColumn([
                'semester',
                'reason',
                'exclude_starts_at',
                'exclude_ends_at',
            ]);
        });
    }
};
