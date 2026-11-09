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
        Schema::create('kriterias', function (Blueprint $table) {
            $table->id('id_kriteria');
            $table->string('kode')->unique(); // e.g., C1, C2, C3
            $table->string('nama'); // e.g., Nilai Akademik
            $table->enum('jenis', ['benefit', 'cost'])->default('benefit');
            $table->unsignedInteger('prioritas')->unique(); // 1, 2, 3, ...
            $table->decimal('bobot_roc', 8, 6)->nullable(); // calculated by ROC
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kriterias');
    }
};
