<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('label');
            $table->string('group')->default('General');
            $table->timestamps();
        });

        DB::table('permissions')->insert([
            // Exam Management
            ['name' => 'admin.rounds',       'label' => 'Exam Rounds',   'group' => 'Exam Management', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'admin.subjects',     'label' => 'Subjects',      'group' => 'Exam Management', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'admin.groups',       'label' => 'Groups',        'group' => 'Exam Management', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'admin.questions',    'label' => 'Question Bank', 'group' => 'Exam Management', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'admin.exams',        'label' => 'Exam Control',  'group' => 'Exam Management', 'created_at' => now(), 'updated_at' => now()],
            // Assessment
            ['name' => 'admin.marks',        'label' => 'Manual Marks',  'group' => 'Assessment',      'created_at' => now(), 'updated_at' => now()],
            ['name' => 'admin.offline-marks','label' => 'Offline Marks', 'group' => 'Assessment',      'created_at' => now(), 'updated_at' => now()],
            // Content
            ['name' => 'admin.certificates', 'label' => 'Certificates',  'group' => 'Content',         'created_at' => now(), 'updated_at' => now()],
            ['name' => 'admin.pdf-books',    'label' => 'PDF Books',     'group' => 'Content',         'created_at' => now(), 'updated_at' => now()],
            // Administration
            ['name' => 'admin.seasons.index','label' => 'Seasons',       'group' => 'Administration',  'created_at' => now(), 'updated_at' => now()],
            ['name' => 'ambassadors',        'label' => 'Ambassadors',   'group' => 'Administration',  'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('permissions');
    }
};
