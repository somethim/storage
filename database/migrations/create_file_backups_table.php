<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration
{
    public function up(): void
    {
        Schema::create('file_backups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('file_storage_id')->constrained('file_storages');
            $table->string('backup_path');
            $table->string('backup_disk');
            $table->timestamp('backed_up_at');
            $table->timestamp('expires_at')->nullable();
            $table->string('status');
            $table->json('metadata')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('file_backups');
    }
}; 