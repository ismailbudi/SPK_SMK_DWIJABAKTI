<?php

namespace App\Services;

use App\Models\Kriteria;
use App\Models\Penilaian;
use App\Models\Perhitungan;
use App\Models\Periode;
use App\Models\Ranking;
use App\Models\Siswa;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class SeleksiService
{
    public function __construct(
        protected MooraService $moora
    ) {
    }

    public function periodeAktif(): ?Periode
    {
        return Periode::getActive() ?? Periode::first();
    }

    public function kriterias(): Collection
    {
        return Kriteria::orderBy('prioritas')->get();
    }

    public function siswas(): Collection
    {
        return Siswa::orderBy('nis')->get();
    }

    public function alternatives(Periode $periode, Collection $kriterias): array
    {
        $penilaians = Penilaian::where('periode_id', $periode->id)
            ->get()
            ->groupBy('id_siswa');

        $alternatives = [];
        foreach ($this->siswas() as $siswa) {
            $nilai = [];
            foreach ($penilaians->get($siswa->id_siswa, collect()) as $penilaian) {
                $nilai[$penilaian->id_kriteria] = (float) $penilaian->nilai;
            }

            $alternatives[] = [
                'id_siswa' => $siswa->id_siswa,
                'nis' => $siswa->nis,
                'nama' => $siswa->nama,
                'kelas' => $siswa->kelas,
                'nilai' => $nilai,
            ];
        }

        return $alternatives;
    }

    public function criteriaArray(Collection $kriterias): array
    {
        return $kriterias->map(fn ($k) => [
            'id_kriteria' => $k->id_kriteria,
            'kode' => $k->kode,
            'nama' => $k->nama,
            'prioritas' => $k->prioritas,
            'jenis' => $k->jenis,
            'bobot_roc' => (float) $k->bobot_roc,
        ])->all();
    }

    public function readiness(Periode $periode, Collection $kriterias): array
    {
        $messages = [];

        if ($kriterias->count() < 2) {
            $messages[] = 'Minimal 2 kriteria harus tersedia.';
        }

        if ($kriterias->count() > 10) {
            $messages[] = 'Maksimal 10 kriteria yang didukung.';
        }

        if ($kriterias->contains(fn ($k) => $k->bobot_roc === null)) {
            $messages[] = 'Bobot ROC belum lengkap. Hitung bobot ROC terlebih dahulu.';
        }

        $siswas = $this->siswas();
        if ($siswas->isEmpty()) {
            $messages[] = 'Belum ada data siswa.';
        }

        $belumLengkap = [];
        $counts = Penilaian::where('periode_id', $periode->id)
            ->selectRaw('id_siswa, count(*) as total')
            ->groupBy('id_siswa')
            ->pluck('total', 'id_siswa');

        foreach ($siswas as $siswa) {
            if ((int) ($counts[$siswa->id_siswa] ?? 0) < $kriterias->count()) {
                $belumLengkap[] = $siswa->nama;
            }
        }

        if (! empty($belumLengkap)) {
            $messages[] = 'Nilai siswa belum lengkap: ' . implode(', ', $belumLengkap) . '.';
        }

        return [
            'ready' => empty($messages),
            'messages' => $messages,
            'siswa_belum_lengkap' => $belumLengkap,
        ];
    }

    public function compute(Periode $periode, Collection $kriterias): array
    {
        return $this->moora->calculate(
            $this->alternatives($periode, $kriterias),
            $this->criteriaArray($kriterias)
        );
    }

    public function hitungDanSimpan(Periode $periode, Collection $kriterias): array
    {
        $result = $this->compute($periode, $kriterias);

        DB::transaction(function () use ($periode, $result) {
            Perhitungan::where('periode_id', $periode->id)->delete();
            Ranking::where('periode_id', $periode->id)->delete();

            $now = now();
            $rows = [];
            foreach ($result['alternatives'] as $alternatif) {
                $idSiswa = $alternatif['id_siswa'];
                foreach ($result['criteria'] as $kriteria) {
                    $idKriteria = $kriteria['id_kriteria'];
                    $rows[] = [
                        'periode_id' => $periode->id,
                        'id_siswa' => $idSiswa,
                        'id_kriteria' => $idKriteria,
                        'nilai_normalisasi' => $result['normalized'][$idSiswa][$idKriteria] ?? 0,
                        'nilai_terbobot' => $result['weighted'][$idSiswa][$idKriteria] ?? 0,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }
            }

            if (! empty($rows)) {
                Perhitungan::insert($rows);
            }

            $rankingRows = [];
            $batas = (int) $periode->jumlah_berprestasi;
            foreach ($result['ranking'] as $item) {
                $rankingRows[] = [
                    'periode_id' => $periode->id,
                    'id_siswa' => $item['id_siswa'],
                    'nilai_akhir' => $item['nilai_akhir'],
                    'peringkat' => $item['peringkat'],
                    'keterangan' => $item['peringkat'] <= $batas ? 'Berprestasi' : 'Cadangan',
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            if (! empty($rankingRows)) {
                Ranking::insert($rankingRows);
            }
        });

        return $result;
    }
}
