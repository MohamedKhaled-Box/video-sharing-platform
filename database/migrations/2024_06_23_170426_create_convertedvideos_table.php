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
        Schema::create('convertedvideos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('video_id')->references('id')->on('videos')->onDelete('cascade');
            $table->string('mp4_format_240')->nullable();
            $table->string('mp4_format_360')->nullable();
            $table->string('mp4_format_480')->nullable();
            $table->string('mp4_format_720')->nullable();
            $table->string('mp4_format_1080')->nullable();
            $table->string('webm_format_240')->nullable();
            $table->string('webm_format_360')->nullable();
            $table->string('webm_format_480')->nullable();
            $table->string('webm_format_720')->nullable();
            $table->string('webm_format_1080')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('convertedvideos');
    }
};