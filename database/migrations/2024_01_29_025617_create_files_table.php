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
        Schema::create('files', function (Blueprint $table) {
            $table->uuid('id')->primary()->unique();
            $table->uuid('storage_id');
            $table->foreign('storage_id')
                ->references('id')
                ->on('storages');
            $table->uuid('category_id');
            $table->foreign('category_id')
                ->references('id')
                ->on('categories');
            $table->string('title');
            $table->string('file_name');
            $table->date('date');
            $table->string('path');
            $table->string('details');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('files');
    }
};
