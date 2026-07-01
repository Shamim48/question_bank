<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $writePerms = [
            ['name' => 'admin.rounds.write',        'label' => 'Manage Rounds',        'group' => 'Exam Management'],
            ['name' => 'admin.subjects.write',       'label' => 'Manage Subjects',      'group' => 'Exam Management'],
            ['name' => 'admin.groups.write',         'label' => 'Manage Groups',        'group' => 'Exam Management'],
            ['name' => 'admin.questions.write',      'label' => 'Manage Questions',     'group' => 'Exam Management'],
            ['name' => 'admin.exams.write',          'label' => 'Control Exam Rounds',  'group' => 'Exam Management'],
            ['name' => 'admin.marks.write',          'label' => 'Enter Manual Marks',   'group' => 'Assessment'],
            ['name' => 'admin.offline-marks.write',  'label' => 'Enter Offline Marks',  'group' => 'Assessment'],
            ['name' => 'admin.certificates.write',   'label' => 'Issue / Delete Certificates', 'group' => 'Content'],
            ['name' => 'admin.pdf-books.write',      'label' => 'Manage PDF Books',     'group' => 'Content'],
        ];

        foreach ($writePerms as &$perm) {
            $perm['created_at'] = now();
            $perm['updated_at'] = now();
        }

        DB::table('permissions')->insert($writePerms);

        // Assign write permissions to roles
        $perms       = DB::table('permissions')->get()->keyBy('name');
        $coordinator = DB::table('roles')->where('name', 'Coordinator')->first();
        $mentor      = DB::table('roles')->where('name', 'Mentor')->first();
        $moderator   = DB::table('roles')->where('name', 'Moderator')->first();

        $assign = function ($role, array $permNames) use ($perms) {
            if (!$role) return;
            foreach ($permNames as $name) {
                if (isset($perms[$name])) {
                    DB::table('role_permissions')->insertOrIgnore([
                        'role_id'       => $role->id,
                        'permission_id' => $perms[$name]->id,
                        'created_at'    => now(),
                        'updated_at'    => now(),
                    ]);
                }
            }
        };

        // Coordinator gets all write permissions
        $assign($coordinator, array_column($writePerms, 'name'));

        // Mentor: can manage content they teach
        $assign($mentor, [
            'admin.questions.write',
            'admin.marks.write',
            'admin.offline-marks.write',
        ]);

        // Moderator: marks only
        $assign($moderator, [
            'admin.marks.write',
            'admin.offline-marks.write',
        ]);
    }

    public function down(): void
    {
        DB::table('permissions')->whereIn('name', [
            'admin.rounds.write', 'admin.subjects.write', 'admin.groups.write',
            'admin.questions.write', 'admin.exams.write', 'admin.marks.write',
            'admin.offline-marks.write', 'admin.certificates.write', 'admin.pdf-books.write',
        ])->delete();
    }
};
