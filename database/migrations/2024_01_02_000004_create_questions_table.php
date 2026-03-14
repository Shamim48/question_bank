<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subject_id')->constrained()->cascadeOnDelete();
            $table->string('type')->default('text'); // text, image, audio, video
            $table->text('content'); // question text
            $table->string('media_url')->nullable(); // file path or YouTube URL
            $table->integer('time_limit')->default(30); // seconds
            $table->integer('points')->default(1);
            $table->integer('options_count')->default(4);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
