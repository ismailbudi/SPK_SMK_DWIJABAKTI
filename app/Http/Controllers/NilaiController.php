<?php

namespace App\Http\Controllers;

use App\Models\Kriteria;
use App\Models\Penilaian;
use App\Models\Periode;
use App\Models\Siswa;
use Illuminate\Http\Request;

class NilaiController extends Controller
{
    public function index(Request $request)
    {
        $periode = Periode::getActive() ?? Periode::first();
        $kriterias = Kriteria::orderBy('prioritas')->get();
        $siswas = Siswa::orderBy('nis')->get();

        $selectedId = (int) $request->query('siswa', 0);
        $selected = $siswas->firstWhere('id_siswa', $selectedId) ?? $siswas->first();

        $nilai = [];
        if ($selected && $periode) {
            $nilai = Penilaian::where('periode_id', $periode->id)
                ->where('id_siswa', $selected->id_siswa)
                ->pluck('nilai', 'id_kriteria')
                ->all();
        }

        $lengkap = $selected && $periode
            ? count($nilai) === $kriterias->count() && $kriterias->count() > 0
            : false;

        return view('nilai.index', compact('periode', 'kriterias', 'siswas', 'selected', 'nilai', 'lengkap'));
    }

    public function store(Request $request)
    {
        $periode = Periode::getActive() ?? Periode::first();
        if (! $periode) {
            return back()->withErrors(['periode' => 'Belum ada periode aktif.']);
        }

        $kriterias = Kriteria::orderBy('prioritas')->get();
        if ($kriterias->isEmpty()) {
            return back()->withErrors(['kriteria' => 'Belum ada data kriteria.']);
        }

        $data = $request->validate([
            'id_siswa' => ['required', 'exists:siswas,id_siswa'],
            'nilai' => ['required', 'array'],
            'nilai.*' => ['required', 'numeric', 'min:0', 'max:100'],
        ], [], [
            'id_siswa' => 'Siswa',
            'nilai.*' => 'Nilai',
        ]);

        foreach ($kriterias as $kriteria) {
            $value = $data['nilai'][$kriteria->id_kriteria] ?? null;
            if ($value === null) {
                return back()->withInput()->withErrors([
                    'nilai' => 'Semua kriteria wajib diisi.',
                ]);
            }

            Penilaian::updateOrCreate(
                [
                    'periode_id' => $periode->id,
                    'id_siswa' => $data['id_siswa'],
                    'id_kriteria' => $kriteria->id_kriteria,
                ],
                ['nilai' => $value]
            );
        }

        return redirect()
            ->route('nilai.index', ['siswa' => $data['id_siswa']])
            ->with('success', 'Nilai siswa berhasil disimpan.');
    }
}
