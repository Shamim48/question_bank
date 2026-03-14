<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('marks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('round_id')->constrained()->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained()->cascadeOnDelete();
            $table->decimal('online_marks', 8, 2)->default(0);
            $table->decimal('manual_marks', 8, 2)->default(0);
            $table->decimal('total_marks', 8, 2)->default(0);
            $table->timestamps();

            $table->unique(['user_id', 'round_id', 'subject_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('marks');
    }
};
