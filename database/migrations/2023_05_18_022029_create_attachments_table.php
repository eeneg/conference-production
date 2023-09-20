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
        Schema::create('attachments', function (Blueprint $table) {
            $table->uuid('id')->primary()->unique();
            $table->uuid('conference_id');
            $table->foreign('conference_id')
                ->references('id')
                ->on('conferences');
            $table->string('category');
            $table->string('category_order');
            $table->string('file_order');
            $table->string('file_name');
            $table->string('path');
            $table->string('details')->nullable();
            $table->string('storage_location')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attachments');
    }
};
