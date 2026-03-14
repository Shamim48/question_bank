<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('student')->after('name'); // admin, student
            $table->string('class')->nullable()->after('role');
            $table->string('group')->nullable()->after('class');
            $table->string('phone')->nullable()->after('group');
            $table->string('division')->nullable()->after('phone');
            $table->string('district')->nullable()->after('division');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'class', 'group', 'phone', 'division', 'district']);
        });
    }
};
