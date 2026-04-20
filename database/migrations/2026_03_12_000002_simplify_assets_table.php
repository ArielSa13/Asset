<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            // Hapus kolom location (tidak dipakai)
            if (Schema::hasColumn('assets', 'location')) {
                $table->dropColumn('location');
            }

            // Set category ke IT Equipment untuk semua data lama, lalu drop
            // (jalankan update dulu sebelum drop jika ingin tetap simpan)
        });

        // Update semua data lama ke IT Equipment
        \DB::table('assets')->update(['category' => 'IT Equipment']);
    }

    public function down(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->string('location')->nullable()->after('status');
        });
    }
};
