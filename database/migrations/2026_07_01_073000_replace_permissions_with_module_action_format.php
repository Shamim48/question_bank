<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    // All permissions in {module}-{action} format
    private array $permissions = [
        // Dashboard
        ['name' => 'dashboard-access',          'label' => 'Dashboard Access',           'group' => 'General'],
        ['name' => 'profile-access',             'label' => 'Profile Access',             'group' => 'General'],

        // Rounds
        ['name' => 'rounds-list',                'label' => 'Rounds List',                'group' => 'Rounds'],
        ['name' => 'rounds-create',              'label' => 'Rounds Create',              'group' => 'Rounds'],
        ['name' => 'rounds-edit',                'label' => 'Rounds Edit',                'group' => 'Rounds'],
        ['name' => 'rounds-delete',              'label' => 'Rounds Delete',              'group' => 'Rounds'],

        // Subjects
        ['name' => 'subjects-list',              'label' => 'Subjects List',              'group' => 'Subjects'],
        ['name' => 'subjects-create',            'label' => 'Subjects Create',            'group' => 'Subjects'],
        ['name' => 'subjects-edit',              'label' => 'Subjects Edit',              'group' => 'Subjects'],
        ['name' => 'subjects-delete',            'label' => 'Subjects Delete',            'group' => 'Subjects'],

        // Groups
        ['name' => 'groups-list',                'label' => 'Groups List',                'group' => 'Groups'],
        ['name' => 'groups-create',              'label' => 'Groups Create',              'group' => 'Groups'],
        ['name' => 'groups-edit',                'label' => 'Groups Edit',                'group' => 'Groups'],
        ['name' => 'groups-delete',              'label' => 'Groups Delete',              'group' => 'Groups'],

        // Questions
        ['name' => 'questions-list',             'label' => 'Questions List',             'group' => 'Questions'],
        ['name' => 'questions-create',           'label' => 'Questions Create',           'group' => 'Questions'],
        ['name' => 'questions-edit',             'label' => 'Questions Edit',             'group' => 'Questions'],
        ['name' => 'questions-delete',           'label' => 'Questions Delete',           'group' => 'Questions'],

        // Exams
        ['name' => 'exams-list',                 'label' => 'Exams List',                 'group' => 'Exams'],
        ['name' => 'exams-control',              'label' => 'Exams Control',              'group' => 'Exams'],

        // Marks (manual)
        ['name' => 'marks-list',                 'label' => 'Marks List',                 'group' => 'Marks'],
        ['name' => 'marks-create',               'label' => 'Marks Create',               'group' => 'Marks'],
        ['name' => 'marks-edit',                 'label' => 'Marks Edit',                 'group' => 'Marks'],

        // Offline Marks
        ['name' => 'offline-marks-list',         'label' => 'Offline Marks List',         'group' => 'Offline Marks'],
        ['name' => 'offline-marks-create',       'label' => 'Offline Marks Create',       'group' => 'Offline Marks'],
        ['name' => 'offline-marks-edit',         'label' => 'Offline Marks Edit',         'group' => 'Offline Marks'],
        ['name' => 'offline-marks-delete',       'label' => 'Offline Marks Delete',       'group' => 'Offline Marks'],

        // Certificates
        ['name' => 'certificates-list',          'label' => 'Certificates List',          'group' => 'Certificates'],
        ['name' => 'certificates-create',        'label' => 'Certificates Create',        'group' => 'Certificates'],
        ['name' => 'certificates-delete',        'label' => 'Certificates Delete',        'group' => 'Certificates'],

        // PDF Books
        ['name' => 'pdf-books-list',             'label' => 'PDF Books List',             'group' => 'PDF Books'],
        ['name' => 'pdf-books-create',           'label' => 'PDF Books Create',           'group' => 'PDF Books'],
        ['name' => 'pdf-books-edit',             'label' => 'PDF Books Edit',             'group' => 'PDF Books'],
        ['name' => 'pdf-books-delete',           'label' => 'PDF Books Delete',           'group' => 'PDF Books'],

        // Seasons
        ['name' => 'seasons-list',               'label' => 'Seasons List',               'group' => 'Seasons'],
        ['name' => 'seasons-create',             'label' => 'Seasons Create',             'group' => 'Seasons'],
        ['name' => 'seasons-edit',               'label' => 'Seasons Edit',               'group' => 'Seasons'],
        ['name' => 'seasons-delete',             'label' => 'Seasons Delete',             'group' => 'Seasons'],

        // Ambassadors
        ['name' => 'ambassadors-list',           'label' => 'Ambassadors List',           'group' => 'Ambassadors'],

        // Team management (admin-only by middleware but listed for completeness)
        ['name' => 'team-list',                  'label' => 'Team List',                  'group' => 'Team'],
        ['name' => 'team-approve',               'label' => 'Team Approve',               'group' => 'Team'],
        ['name' => 'team-reject',                'label' => 'Team Reject',                'group' => 'Team'],
    ];

    // Default permissions per role
    private array $roleDefaults = [
        'Coordinator' => '__ALL__',

        'Mentor' => [
            'dashboard-access', 'profile-access',
            'rounds-list', 'subjects-list',
            'questions-list', 'questions-create', 'questions-edit', 'questions-delete',
            'exams-list',
            'marks-list', 'marks-create', 'marks-edit',
            'offline-marks-list', 'offline-marks-create', 'offline-marks-edit', 'offline-marks-delete',
            'pdf-books-list',
        ],

        'Volunteer' => [
            'dashboard-access', 'profile-access',
            'questions-list', 'exams-list',
            'marks-list', 'marks-create',
        ],

        'Ambassador' => [
            'dashboard-access', 'profile-access',
            'ambassadors-list', 'pdf-books-list',
        ],

        'Moderator' => [
            'dashboard-access', 'profile-access',
            'exams-list', 'exams-control',
            'marks-list', 'marks-create', 'marks-edit',
            'offline-marks-list', 'offline-marks-create', 'offline-marks-edit', 'offline-marks-delete',
        ],
    ];

    public function up(): void
    {
        // Clear existing data
        DB::table('role_permissions')->delete();
        DB::table('permissions')->delete();

        // Insert new permissions
        $now = now();
        foreach ($this->permissions as &$perm) {
            $perm['created_at'] = $now;
            $perm['updated_at'] = $now;
        }
        DB::table('permissions')->insert($this->permissions);

        // Rebuild role_permissions
        $perms = DB::table('permissions')->get()->keyBy('name');

        foreach ($this->roleDefaults as $roleName => $allowed) {
            $role = DB::table('roles')->where('name', $roleName)->first();
            if (!$role) continue;

            $permNames = $allowed === '__ALL__'
                ? $perms->keys()->toArray()
                : $allowed;

            foreach ($permNames as $name) {
                if (isset($perms[$name])) {
                    DB::table('role_permissions')->insert([
                        'role_id'       => $role->id,
                        'permission_id' => $perms[$name]->id,
                        'created_at'    => $now,
                        'updated_at'    => $now,
                    ]);
                }
            }
        }
    }

    public function down(): void
    {
        // Cannot restore old permissions - irreversible
        DB::table('role_permissions')->delete();
        DB::table('permissions')->delete();
    }
};
