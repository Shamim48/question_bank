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
        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('season_id')->nullable()->constrained('seasons')->nullOnDelete();
            $table->string('role');
            $table->string('image')->nullable();
            $table->string('whatsapp', 20)->nullable();
            $table->string('telegram', 20)->nullable();
            $table->string('institute_name')->nullable();
            $table->string('designation')->nullable();
            $table->string('department')->nullable();
            $table->string('institute_mobile', 20)->nullable();
            $table->string('institute_email', 100)->nullable();
            $table->string('eiin_no')->nullable();
            $table->foreignId('division_id')->nullable()->constrained('divisions')->nullOnDelete();
            $table->foreignId('district_id')->nullable()->constrained('districts')->nullOnDelete();
            $table->foreignId('thana_id')->nullable()->constrained('thanas')->nullOnDelete();
            $table->string('address')->nullable();
            $table->json('permissions')->nullable();
            $table->tinyInteger('status')->default(0); // 0=pending, 1=approved, 2=rejected
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teams');
    }
};
