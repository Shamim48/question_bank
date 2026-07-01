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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('student_id')->nullable();
            $table->enum('known_from', ['Facebook', 'Mentor', 'Ambassador'])->nullable();
            $table->foreignId('season_id')->nullable()->constrained('seasons')->nullOnDelete();
            $table->string('name');
            $table->string('email', 100)->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('nid_birth')->nullable();
            $table->enum('gender', ['male', 'female', 'others'])->nullable();
            $table->date('dob')->nullable();
            $table->string('institute_name')->nullable();
            $table->string('institute_mobile', 20)->nullable();
            $table->string('institute_email', 100)->nullable();
            $table->foreignId('class_id')->nullable()->constrained('class_levels')->nullOnDelete();
            $table->foreignId('group_id')->nullable()->constrained('groups')->nullOnDelete();
            $table->string('board')->nullable();
            $table->foreignId('thana_id')->nullable()->constrained('thanas')->nullOnDelete();
            $table->foreignId('district_id')->nullable()->constrained('districts')->nullOnDelete();
            $table->foreignId('division_id')->nullable()->constrained('divisions')->nullOnDelete();
            $table->string('address')->nullable();
            $table->string('father_name')->nullable();
            $table->string('mother_name')->nullable();
            $table->string('guardian_phone', 20)->nullable();
            $table->enum('hobby', ['Reading', 'Sports', 'Music', 'Travel', 'Art'])->nullable();
            $table->enum('aim_in_life', ['Engineer', 'Doctor', 'Teacher', 'Entrepreneur', 'Other'])->nullable();
            $table->string('favourite_quote')->nullable();
            $table->string('idol', 100)->nullable();
            $table->string('image')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('referrer_id')->nullable()->constrained('users');
            $table->tinyInteger('status')->nullable()->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
