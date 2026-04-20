<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_id')->constrained()->onDelete('cascade');
            $table->string('borrower_name');
            $table->string('borrower_department');
            $table->string('borrower_phone')->nullable();
            $table->datetime('borrowed_at');
            $table->datetime('expected_return_at')->nullable();
            $table->datetime('returned_at')->nullable();
            $table->enum('condition_before', ['good', 'fair', 'poor', 'broken']);
            $table->enum('condition_after', ['good', 'fair', 'poor', 'broken'])->nullable();
            $table->string('purpose');
            $table->string('approved_by')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};
