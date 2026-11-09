<?php

namespace App\Http\Controllers;

use App\Models\Ranking;
use App\Models\Siswa;
use App\Services\SeleksiService;
use Illuminate\Http\Request;

class RankingController extends Controller
{
    public function index(SeleksiService $seleksi)
    {
        $periode = $seleksi->periodeAktif();

        $rankings = $periode
            ? Ranking::with('siswa')
                ->where('periode_id', $periode->id)
                ->orderBy('peringkat')
                ->get()
            : collect();

        return view('ranking.index', compact('periode', 'rankings'));
    }

    public function refresh(SeleksiService $seleksi)
    {
        $periode = $seleksi->periodeAktif();
        if (! $periode) {
            return back()->withErrors(['periode' => 'Belum ada periode aktif.']);
        }

        $kriterias = $seleksi->kriterias();
        $readiness = $seleksi->readiness($periode, $kriterias);

        if (! $readiness['ready']) {
            return redirect()->route('moora.index')->withErrors($readiness['messages']);
        }

        $seleksi->hitungDanSimpan($periode, $kriterias);

        return redirect()->route('ranking.index')
            ->with('success', 'Hasil perangkingan berhasil diperbarui.');
    }

    public function updatePengaturan(SeleksiService $seleksi, Request $request)
    {
        $periode = $seleksi->periodeAktif();
        if (! $periode) {
            return back()->withErrors(['periode' => 'Belum ada periode aktif.']);
        }

        $data = $request->validate([
            'jumlah_berprestasi' => ['required', 'integer', 'min:0', 'max:' . max(1, Siswa::count())],
        ], [], [
            'jumlah_berprestasi' => 'Jumlah siswa berprestasi',
        ]);

        $periode->update(['jumlah_berprestasi' => $data['jumlah_berprestasi']]);

        $rankings = Ranking::where('periode_id', $periode->id)->get();
        foreach ($rankings as $ranking) {
            $ranking->update([
                'keterangan' => $ranking->peringkat <= $data['jumlah_berprestasi'] ? 'Berprestasi' : 'Cadangan',
            ]);
        }

        if ($rankings->isEmpty()) {
            $kriterias = $seleksi->kriterias();
            if ($seleksi->readiness($periode, $kriterias)['ready']) {
                $seleksi->hitungDanSimpan($periode, $kriterias);
            }
        }

        return redirect()->route('ranking.index')
            ->with('success', 'Jumlah siswa berprestasi berhasil diperbarui.');
    }
}
