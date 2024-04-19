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
            $table->uuid('file_id');
            $table->string('category');
            $table->integer('category_order');
            $table->integer('file_order');
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
