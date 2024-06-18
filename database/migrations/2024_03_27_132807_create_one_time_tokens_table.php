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
        Schema::create('one_time_tokens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->string('token')->unique();
            $table->date('expires_at');
            $table->string('semester');
            $table->string('reason')->nullable();
            $table->string('exclude_starts_at')->nullable();
            $table->string('exclude_ends_at')->nullable();
            $table->timestamps();
        });
    }
};
