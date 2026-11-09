<?php

namespace App\Http\Controllers;

use App\Models\Perhitungan;
use App\Models\Ranking;
use App\Services\RocService;
use App\Services\SeleksiService;
use Illuminate\Support\Facades\DB;

class RocController extends Controller
{
    public function index(SeleksiService $seleksi, RocService $roc)
    {
        $kriterias = $seleksi->kriterias();
        $n = $kriterias->count();
        $preview = $roc->weights($n);
        $formulas = $roc->formulas($n);

        return view('roc.index', compact('kriterias', 'n', 'preview', 'formulas'));
    }

    public function hitung(SeleksiService $seleksi, RocService $roc)
    {
        $kriterias = $seleksi->kriterias();
        $n = $kriterias->count();

        if ($n < 2) {
            return back()->withErrors(['roc' => 'Minimal 2 kriteria diperlukan untuk menghitung bobot ROC.']);
        }

        $weights = $roc->weights($n);

        DB::transaction(function () use ($kriterias, $weights) {
            foreach ($kriterias as $kriteria) {
                $kriteria->update(['bobot_roc' => $weights[$kriteria->prioritas]]);
            }

            Perhitungan::query()->delete();
            Ranking::query()->delete();
        });

        return redirect()->route('roc.index')
            ->with('success', 'Bobot ROC berhasil dihitung dan disimpan.');
    }
}
