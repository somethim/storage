<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration
{
    public function up(): void
    {
        Schema::create('file_shares', function (Blueprint $table) {
            $table->id();
            $table->foreignId('file_storage_id')->constrained('file_storages');
            $table->string('token')->unique();
            $table->timestamp('expires_at')->nullable();
            $table->integer('download_limit')->nullable();
            $table->integer('download_count')->default(0);
            $table->json('access_restrictions')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('file_shares');
    }
};
