<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asset_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();         // e.g. Monitor
            $table->string('prefix', 10)->unique();   // e.g. MON
            $table->string('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Tambah foreign key category_id ke tabel assets
        Schema::table('assets', function (Blueprint $table) {
            $table->foreignId('category_id')
                  ->nullable()
                  ->after('code')
                  ->constrained('asset_categories')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn('category_id');
        });
        Schema::dropIfExists('asset_categories');
    }
};
