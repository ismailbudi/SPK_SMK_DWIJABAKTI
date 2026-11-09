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
        Schema::create('rankings', function (Blueprint $table) {
            $table->id('id_ranking');
            $table->foreignId('periode_id')->constrained('periodes')->onDelete('cascade');
            $table->foreignId('id_siswa')->constrained('siswas', 'id_siswa')->onDelete('cascade');
            $table->decimal('nilai_akhir', 10, 6);
            $table->unsignedInteger('peringkat');
            $table->enum('keterangan', ['Berprestasi', 'Cadangan']);
            $table->unique(['periode_id', 'id_siswa']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rankings');
    }
};
