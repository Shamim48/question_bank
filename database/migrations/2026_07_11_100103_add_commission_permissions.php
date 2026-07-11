<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private array $permissions = [
        ['name' => 'commissions-list',     'label' => 'Referrals & Commissions', 'group' => 'Commissions'],
        ['name' => 'commissions-mark-paid', 'label' => 'Mark Commission Paid',   'group' => 'Commissions'],
    ];

    public function up(): void
    {
        $now = now();

        foreach ($this->permissions as $perm) {
            if (DB::table('permissions')->where('name', $perm['name'])->exists()) {
                continue;
            }

            $id = DB::table('permissions')->insertGetId($perm + [
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            $role = DB::table('roles')->where('name', 'Coordinator')->first();
            if ($role) {
                DB::table('role_permissions')->insert([
                    'role_id'       => $role->id,
                    'permission_id' => $id,
                    'created_at'    => $now,
                    'updated_at'    => $now,
                ]);
            }
        }
    }

    public function down(): void
    {
        $names = array_column($this->permissions, 'name');
        $ids   = DB::table('permissions')->whereIn('name', $names)->pluck('id');

        DB::table('role_permissions')->whereIn('permission_id', $ids)->delete();
        DB::table('permissions')->whereIn('name', $names)->delete();
    }
};
