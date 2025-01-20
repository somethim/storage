<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration
{
    public function up(): void
    {
        Schema::create('file_scan_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('file_storage_id')->constrained('file_storages');
            $table->boolean('is_clean');
            $table->json('scan_details')->nullable();
            $table->timestamp('scanned_at');
            $table->timestamps();
            $table->index('scanned_at');
        });

        Schema::table('file_storages', function (Blueprint $table) {
            $table->timestamp('last_scanned_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('file_scan_results');

        Schema::table('file_storages', function (Blueprint $table) {
            $table->dropColumn('last_scanned_at');
        });
    }
};
