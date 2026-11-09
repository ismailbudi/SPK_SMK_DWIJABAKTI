<?php

namespace App\Http\Controllers;

use App\Models\Ranking;
use App\Services\SeleksiService;

class MooraController extends Controller
{
    public function index(SeleksiService $seleksi)
    {
        $periode = $seleksi->periodeAktif();
        $kriterias = $seleksi->kriterias();

        $readiness = $periode
            ? $seleksi->readiness($periode, $kriterias)
            : ['ready' => false, 'messages' => ['Belum ada periode aktif.'], 'siswa_belum_lengkap' => []];

        $result = $readiness['ready'] ? $seleksi->compute($periode, $kriterias) : null;

        $sudahDisimpan = $periode
            ? Ranking::where('periode_id', $periode->id)->exists()
            : false;

        return view('moora.index', compact('periode', 'kriterias', 'readiness', 'result', 'sudahDisimpan'));
    }

    public function hitung(SeleksiService $seleksi)
    {
        $periode = $seleksi->periodeAktif();
        if (! $periode) {
            return back()->withErrors(['periode' => 'Belum ada periode aktif.']);
        }

        $kriterias = $seleksi->kriterias();
        $readiness = $seleksi->readiness($periode, $kriterias);

        if (! $readiness['ready']) {
            return back()->withErrors($readiness['messages']);
        }

        $seleksi->hitungDanSimpan($periode, $kriterias);

        return redirect()->route('moora.index')
            ->with('success', 'Perhitungan MOORA berhasil disimpan.');
    }
}
