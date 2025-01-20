<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('file_storage', function (Blueprint $table) {
            $table->id();
            $table->string('path');
            $table->string('disk');
            $table->string('mime_type');
            $table->bigInteger('size');
            $table->string('hash')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('file_storage');
    }
};
