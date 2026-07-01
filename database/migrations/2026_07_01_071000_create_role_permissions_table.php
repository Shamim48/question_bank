<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('role_permissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_id')->constrained()->cascadeOnDelete();
            $table->foreignId('permission_id')->constrained()->cascadeOnDelete();
            $table->unique(['role_id', 'permission_id']);
            $table->timestamps();
        });

        // Default permissions per role
        $allPerms      = DB::table('permissions')->get()->keyBy('name');
        $coordinator   = DB::table('roles')->where('name', 'Coordinator')->first();
        $mentor        = DB::table('roles')->where('name', 'Mentor')->first();
        $volunteer     = DB::table('roles')->where('name', 'Volunteer')->first();
        $ambassador    = DB::table('roles')->where('name', 'Ambassador')->first();
        $moderator     = DB::table('roles')->where('name', 'Moderator')->first();

        $assign = function ($role, array $permNames) use ($allPerms) {
            if (!$role) return;
            foreach ($permNames as $perm) {
                if (isset($allPerms[$perm])) {
                    DB::table('role_permissions')->insertOrIgnore([
                        'role_id'       => $role->id,
                        'permission_id' => $allPerms[$perm]->id,
                        'created_at'    => now(),
                        'updated_at'    => now(),
                    ]);
                }
            }
        };

        $allPermNames = $allPerms->keys()->toArray();

        // Coordinator — full access
        $assign($coordinator, $allPermNames);

        // Mentor — teaching/content
        $assign($mentor, [
            'admin.questions', 'admin.exams', 'admin.marks',
            'admin.offline-marks', 'admin.pdf-books', 'admin.rounds', 'admin.subjects',
        ]);

        // Volunteer — limited
        $assign($volunteer, ['admin.questions', 'admin.exams', 'admin.marks']);

        // Ambassador — own section
        $assign($ambassador, ['ambassadors', 'admin.pdf-books']);

        // Moderator — marks & offline marks
        $assign($moderator, ['admin.marks', 'admin.offline-marks', 'admin.exams']);
    }

    public function down(): void
    {
        Schema::dropIfExists('role_permissions');
    }
};
