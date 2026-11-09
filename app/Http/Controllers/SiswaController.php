<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q'));

        $siswas = Siswa::query()
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('nis', 'like', "%{$q}%")
                        ->orWhere('nama', 'like', "%{$q}%")
                        ->orWhere('kelas', 'like', "%{$q}%");
                });
            })
            ->orderBy('nis')
            ->paginate(10)
            ->withQueryString();

        return view('siswa.index', compact('siswas', 'q'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nis' => ['required', 'string', 'max:30', 'unique:siswas,nis'],
            'nama' => ['required', 'string', 'max:120'],
            'kelas' => ['required', 'string', 'max:50'],
            'jenis_kelamin' => ['required', Rule::in(['L', 'P'])],
        ], [], [
            'nis' => 'NIS',
            'nama' => 'Nama',
            'kelas' => 'Kelas',
            'jenis_kelamin' => 'Jenis Kelamin',
        ]);

        Siswa::create($data);

        return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil ditambahkan.');
    }

    public function update(Request $request, Siswa $siswa)
    {
        $data = $request->validate([
            'nis' => ['required', 'string', 'max:30', Rule::unique('siswas', 'nis')->ignore($siswa->id_siswa, 'id_siswa')],
            'nama' => ['required', 'string', 'max:120'],
            'kelas' => ['required', 'string', 'max:50'],
            'jenis_kelamin' => ['required', Rule::in(['L', 'P'])],
        ], [], [
            'nis' => 'NIS',
            'nama' => 'Nama',
            'kelas' => 'Kelas',
            'jenis_kelamin' => 'Jenis Kelamin',
        ]);

        $siswa->update($data);

        return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil diubah.');
    }

    public function destroy(Siswa $siswa)
    {
        $siswa->delete();

        return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil dihapus.');
    }
}
