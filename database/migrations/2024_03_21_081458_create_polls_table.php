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
        Schema::create('polls', function (Blueprint $table) {
            $table->uuid('id')->primary()->unique();
            $table->uuid('conference_id');
            $table->foreign('conference_id')
                ->references('id')
                ->on('conferences');
            $table->string('title');
            $table->integer('agree_count');
            $table->integer('disagree_count');
            $table->integer('undecided_count');
            $table->string('result');
            $table->string('details');
            $table->boolean('concluded')->default(false);
            $table->boolean('active')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('polls');
    }
};
