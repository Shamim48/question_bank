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
            // $table->string('student_id')->nullable();
            // $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            // $table->unsignedBigInteger('class_id');
            // $table->unsignedBigInteger('season_id');
            // $table->foreignId('referrer_id')->nullable()->constrained('admins')->nullOnDelete();
            // $table->string('known_from')->nullable();
            // $table->string('institute_name')->nullable();
            // $table->string('institute_mobile')->nullable();
            // $table->string('institute_email')->nullable();
            // $table->string('board')->nullable();
            // $table->tinyInteger('gender')->nullable()->comment('1=male, 2=female, 3=others');
            // $table->date('date_of_birth')->nullable();
            // $table->string('father_name')->nullable();
            // $table->string('mother_name')->nullable();
            // $table->string('guardian_phone')->nullable();
            // $table->unsignedBigInteger('upazilla_id')->nullable();
            // $table->unsignedBigInteger('district_id')->nullable();
            // $table->unsignedBigInteger('division_id')->nullable();
            // $table->string('address')->nullable();
            // $table->string('nid_birth')->nullable();
            // $table->string('hobby')->nullable();
            // $table->string('aim_in_life')->nullable();
            // $table->string('favourite_quote')->nullable();
            // $table->string('idol')->nullable();
            // $table->tinyInteger('status')->nullable()->default(1);
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
