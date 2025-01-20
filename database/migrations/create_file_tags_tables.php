<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration
{
    public function up(): void
    {
        Schema::create('file_tags', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type')->nullable();
            $table->timestamps();
        });

        Schema::create('file_tag_associations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('file_storage_id')->constrained('file_storages');
            $table->foreignId('file_tag_id')->constrained('file_tags');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('file_tag_associations');
        Schema::dropIfExists('file_tags');
    }
};
