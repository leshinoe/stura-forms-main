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
        Schema::table('dticket_requests', function (Blueprint $table) {
            $table->dropColumn(['months']);
            $table->date('exclude_starts_at')->nullable();
            $table->date('exclude_ends_at')->nullable();
        });
    }
};
