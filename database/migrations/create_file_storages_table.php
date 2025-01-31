<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration
{
    public function up(): void
    {
        Schema::create('file_storages', function (Blueprint $table) {
            $table->id();
            $table->string('path');
            $table->string('disk');
            $table->string('mime_type');
            $table->bigInteger('size');
            $table->string('hash')->nullable();
            $table->json('metadata')->nullable();
            $table->string('encryption_key')->nullable();
            $table->boolean('is_compressed')->default(false);
            $table->nullableMorphs('storable');
            $table->foreignId('original_file_id')->nullable()->constrained('file_storages');
            $table->integer('reference_count')->default(1);
            $table->timestamp('last_backup_at')->nullable();
            $table->string('backup_status')->nullable();
            $table->string('status')->default('active');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('file_storages');
    }
};
