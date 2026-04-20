<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            // Hapus unique constraint dari kolom code
            // agar soft-deleted records tidak memblokir kode yang sama
            $table->dropUnique('assets_code_unique');

            // Tambahkan index biasa (untuk performa query)
            $table->index('code', 'assets_code_index');
        });
    }

    public function down(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->dropIndex('assets_code_index');
            $table->unique('code', 'assets_code_unique');
        });
    }
};
