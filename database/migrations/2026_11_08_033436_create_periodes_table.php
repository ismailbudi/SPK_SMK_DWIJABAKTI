<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('periodes', function (Blueprint $table) {
            $table->id();
            $table->string('nama'); // e.g., "2026/2027 - Ganjil"
            $table->string('tahun_ajaran'); // e.g., "2026/2027"
            $table->enum('semester', ['Ganjil', 'Genap']);
            $table->integer('jumlah_berprestasi')->default(2);
            $table->boolean('aktif')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('periodes');
    }
};
