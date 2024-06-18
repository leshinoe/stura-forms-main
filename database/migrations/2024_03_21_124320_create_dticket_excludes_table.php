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
        Schema::create('dticket_excludes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('btu_id');
            $table->date('exclude_starts_at');
            $table->date('exclude_ends_at');
            $table->string('reason')->nullable();
            $table->timestamps();
        });
    }
};
