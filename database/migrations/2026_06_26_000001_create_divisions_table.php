<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('divisions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('country_id')->index();
            $table->string('name')->unique();
            $table->string('bn_name')->unique();
            $table->boolean('status')->default(1)->comment('1 = active, 0 = inactive')->index();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('divisions');
    }
};
