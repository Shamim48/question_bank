<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('display_name')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });

        // Seed default roles
        DB::table('roles')->insert([
            ['name' => 'admin',       'display_name' => 'Administrator',  'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'student',     'display_name' => 'Student',        'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Coordinator', 'display_name' => 'Coordinator',    'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Mentor',      'display_name' => 'Mentor',         'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Volunteer',   'display_name' => 'Volunteer',      'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Ambassador',  'display_name' => 'Ambassador',     'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Moderator',   'display_name' => 'Moderator',      'status' => 1, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
