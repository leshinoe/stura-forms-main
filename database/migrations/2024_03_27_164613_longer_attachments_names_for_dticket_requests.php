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
            $table->text('attachments')->nullable()->change();
            $table->text('attachment_filenames')->nullable()->change();
        });
    }
};
