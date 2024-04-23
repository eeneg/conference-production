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
        Schema::create('file_comments', function (Blueprint $table) {
            $table->uuid('id')->primary()->unique();
            $table->uuid('user_id');
            $table->uuid('file_id');
            $table->foreign('file_id')
                ->references('id')
                ->on('files')
                ->onDelete('cascade');
            $table->text('comment');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('file_comments');
    }
};
