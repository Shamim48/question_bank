<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bulk_messages', function (Blueprint $table) {
            $table->id();
            $table->enum('channel', ['sms', 'email']);
            $table->string('subject')->nullable();
            $table->text('body');
            $table->enum('status', ['draft', 'sent'])->default('draft');
            $table->json('audience_filters')->nullable();
            $table->unsignedInteger('total_recipients')->default(0);
            $table->unsignedInteger('sent_count')->default(0);
            $table->unsignedInteger('failed_count')->default(0);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bulk_messages');
    }
};
