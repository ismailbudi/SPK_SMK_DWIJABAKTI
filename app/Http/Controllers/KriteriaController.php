<?php

namespace App\Http\Controllers;

use App\Models\Kriteria;
use App\Models\Perhitungan;
use App\Models\Ranking;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class KriteriaController extends Controller
{
    public function index()
    {
        $kriterias = Kriteria::orderBy('prioritas')->get();
        $perluHitung = $kriterias->contains(fn ($k) => $k->bobot_roc === null);

        return view('kriteria.index', compact('kriterias', 'perluHitung'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'kode' => ['required', 'string', 'max:10', 'unique:kriterias,kode'],
            'nama' => ['required', 'string', 'max:120'],
            'jenis' => ['required', Rule::in(['benefit', 'cost'])],
            'prioritas' => ['required', 'integer', 'min:1'],
        ]);

        $prioritas = Kriteria::pluck('prioritas')->push((int) $data['prioritas'])->sort()->values();

        if (! $this->isSequential($prioritas)) {
            return back()->withInput()->withErrors([
                'prioritas' => 'Prioritas harus unik dan berurutan mulai dari 1 tanpa lompatan.',
            ]);
        }

        $data['bobot_roc'] = null;
        Kriteria::create($data);
        $this->clearResults();

        return redirect()->route('kriteria.index')
            ->with('success', 'Kriteria berhasil ditambahkan. Silakan hitung ulang bobot ROC.');
    }

    public function update(Request $request, Kriteria $kriteria)
    {
        $data = $request->validate([
            'kode' => ['required', 'string', 'max:10', Rule::unique('kriterias', 'kode')->ignore($kriteria->id_kriteria, 'id_kriteria')],
            'nama' => ['required', 'string', 'max:120'],
            'jenis' => ['required', Rule::in(['benefit', 'cost'])],
            'prioritas' => ['required', 'integer', 'min:1'],
        ]);

        $prioritas = Kriteria::where('id_kriteria', '!=', $kriteria->id_kriteria)
            ->pluck('prioritas')
            ->push((int) $data['prioritas'])
            ->sort()
            ->values();

        if (! $this->isSequential($prioritas)) {
            return back()->withInput()->withErrors([
                'prioritas' => 'Prioritas harus unik dan berurutan mulai dari 1 tanpa lompatan.',
            ]);
        }

        $data['bobot_roc'] = null;
        $kriteria->update($data);
        $this->clearResults();

        return redirect()->route('kriteria.index')
            ->with('success', 'Kriteria berhasil diubah. Silakan hitung ulang bobot ROC.');
    }

    public function destroy(Kriteria $kriteria)
    {
        $kriteria->delete();

        $remaining = Kriteria::orderBy('prioritas')->get();
        foreach ($remaining as $index => $item) {
            $item->update(['prioritas' => $index + 1, 'bobot_roc' => null]);
        }

        $this->clearResults();

        return redirect()->route('kriteria.index')
            ->with('success', 'Kriteria berhasil dihapus. Silakan hitung ulang bobot ROC.');
    }

    protected function isSequential($prioritas): bool
    {
        $prioritas = $prioritas->map(fn ($p) => (int) $p)->sort()->values();
        $expected = range(1, $prioritas->count());

        return $prioritas->all() === $expected;
    }

    protected function clearResults(): void
    {
        Perhitungan::query()->delete();
        Ranking::query()->delete();
    }
}
