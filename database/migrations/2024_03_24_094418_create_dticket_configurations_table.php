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
        Schema::create('dticket_configurations', function (Blueprint $table) {
            $table->id();
            $table->string('semester')->unique();
            $table->text('reasons_for_exemption')->nullable();
            $table->text('reasons_for_rejection')->nullable();
            $table->timestamps();
        });
    }
};
