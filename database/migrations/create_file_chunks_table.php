<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration
{
    public function up(): void
    {
        Schema::create('file_chunks', function (Blueprint $table) {
            $table->id();
            $table->string('upload_id')->unique();
            $table->foreignId('file_storage_id')->nullable()->constrained('file_storages');
            $table->integer('chunk_number');
            $table->integer('total_chunks');
            $table->string('chunk_path');
            $table->bigInteger('chunk_size');
            $table->timestamp('expires_at');
            $table->string('chunk_hash')->nullable();
            $table->boolean('is_validated')->default(false);
            $table->json('validation_errors')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('file_chunks');
    }
};
