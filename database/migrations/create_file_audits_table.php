<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration
{
    public function up(): void
    {
        Schema::create('file_audits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('file_storage_id')->constrained('file_storages')->noActionOnDelete();
            $table->string('action');
            $table->string('actor');
            $table->json('details')->nullable();
            $table->ipAddress('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->string('session_id')->nullable();
            $table->json('metadata')->nullable();
            $table->string('status')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('file_audits');
    }
};
