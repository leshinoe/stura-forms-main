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
        Schema::create('dticket_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->string('semester');
            $table->string('months');
            $table->string('banking_name');
            $table->string('banking_iban');
            $table->string('banking_bic');
            $table->string('reason');
            $table->string('attachments')->nullable();
            $table->string('attachment_filenames')->nullable();
            $table->text('comment')->nullable();
            $table->text('admin_note')->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();
        });
    }
};
