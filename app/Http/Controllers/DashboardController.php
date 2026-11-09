<?php

namespace App\Http\Controllers;

use App\Models\Kriteria;
use App\Models\Penilaian;
use App\Models\Periode;
use App\Models\Ranking;
use App\Models\Siswa;

class DashboardController extends Controller
{
    public function index()
    {
        $periode = Periode::getActive() ?? Periode::first();
        $totalSiswa = Siswa::count();
        $totalKriteria = Kriteria::count();
        $totalPenilaian = $periode
            ? Penilaian::where('periode_id', $periode->id)->count()
            : 0;

        $bobotLengkap = $totalKriteria > 0
            && Kriteria::whereNull('bobot_roc')->count() === 0;

        $nilaiLengkap = $totalSiswa > 0
            && $totalKriteria > 0
            && $totalPenilaian >= $totalSiswa * $totalKriteria;

        $hasilSeleksi = $nilaiLengkap && $bobotLengkap;
        $totalRanking = $periode
            ? Ranking::where('periode_id', $periode->id)->count()
            : 0;

        $kriterias = Kriteria::orderBy('prioritas')->get();
        $rataRata = [];
        foreach ($kriterias as $kriteria) {
            $rataRata[] = $periode
                ? round((float) Penilaian::where('periode_id', $periode->id)
                    ->where('id_kriteria', $kriteria->id_kriteria)
                    ->avg('nilai'), 2)
                : 0;
        }

        $langkah = [
            'Data Siswa' => $totalSiswa > 0,
            'Data Kriteria' => $totalKriteria > 0,
            'Input Nilai' => $nilaiLengkap,
            'Bobot ROC' => $bobotLengkap,
            'Perhitungan MOORA' => $totalRanking > 0,
        ];

        return view('dashboard', compact(
            'periode',
            'totalSiswa',
            'totalKriteria',
            'totalPenilaian',
            'hasilSeleksi',
            'totalRanking',
            'kriterias',
            'rataRata',
            'langkah'
        ));
    }
}
