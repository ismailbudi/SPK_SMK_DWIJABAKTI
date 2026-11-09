<?php

namespace Database\Seeders;

use App\Models\Kriteria;
use App\Models\Penilaian;
use App\Models\Periode;
use App\Models\Siswa;
use App\Models\User;
use App\Services\RocService;
use App\Services\SeleksiService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'Administrator',
                'email' => 'admin@smkdwijabhakti.sch.id',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        $periode = Periode::updateOrCreate(
            ['nama' => '2026/2027 - Ganjil'],
            [
                'tahun_ajaran' => '2026/2027',
                'semester' => 'Ganjil',
                'jumlah_berprestasi' => 2,
                'aktif' => true,
            ]
        );

        $kriteriaData = [
            ['kode' => 'C1', 'nama' => 'Nilai Akademik', 'jenis' => 'benefit', 'prioritas' => 1],
            ['kode' => 'C2', 'nama' => 'Kehadiran', 'jenis' => 'benefit', 'prioritas' => 2],
            ['kode' => 'C3', 'nama' => 'Sikap', 'jenis' => 'benefit', 'prioritas' => 3],
            ['kode' => 'C4', 'nama' => 'Prestasi Non Akademik', 'jenis' => 'benefit', 'prioritas' => 4],
            ['kode' => 'C5', 'nama' => 'Keaktifan Organisasi', 'jenis' => 'benefit', 'prioritas' => 5],
        ];

        $weights = (new RocService())->weights(count($kriteriaData));

        foreach ($kriteriaData as $data) {
            $data['bobot_roc'] = $weights[$data['prioritas']];
            Kriteria::updateOrCreate(['kode' => $data['kode']], $data);
        }

        $siswaData = [
            ['nis' => '001', 'nama' => 'Intan Permata Sari', 'kelas' => 'XI IPA 1', 'jenis_kelamin' => 'P'],
            ['nis' => '002', 'nama' => 'Gina Lestari', 'kelas' => 'XI IPA 1', 'jenis_kelamin' => 'P'],
            ['nis' => '003', 'nama' => 'Aisyah Nur Fadilah', 'kelas' => 'XI IPA 1', 'jenis_kelamin' => 'P'],
            ['nis' => '004', 'nama' => 'Elsa Maharani', 'kelas' => 'XI IPA 1', 'jenis_kelamin' => 'P'],
            ['nis' => '005', 'nama' => 'Citra Ayu Lestari', 'kelas' => 'XI IPA 1', 'jenis_kelamin' => 'P'],
            ['nis' => '006', 'nama' => 'Ahmad Rizki', 'kelas' => 'XI IPA 1', 'jenis_kelamin' => 'L'],
            ['nis' => '007', 'nama' => 'Hendra Pratama', 'kelas' => 'XI IPA 1', 'jenis_kelamin' => 'L'],
            ['nis' => '008', 'nama' => 'Budi Santoso', 'kelas' => 'XI IPA 1', 'jenis_kelamin' => 'L'],
            ['nis' => '009', 'nama' => 'Farhan Maulana', 'kelas' => 'XI IPA 1', 'jenis_kelamin' => 'L'],
            ['nis' => '010', 'nama' => 'Dimas Aditya', 'kelas' => 'XI IPA 1', 'jenis_kelamin' => 'L'],
        ];

        foreach ($siswaData as $data) {
            Siswa::updateOrCreate(['nis' => $data['nis']], $data);
        }

        $nilaiData = [
            '001' => [95, 98, 94, 92, 90],
            '002' => [94, 97, 93, 91, 89],
            '003' => [93, 96, 92, 90, 88],
            '004' => [92, 95, 91, 89, 87],
            '005' => [91, 94, 90, 88, 86],
            '006' => [90, 93, 89, 87, 85],
            '007' => [89, 92, 88, 86, 84],
            '008' => [88, 91, 87, 85, 83],
            '009' => [87, 90, 86, 84, 82],
            '010' => [86, 89, 85, 83, 81],
        ];

        $kriterias = Kriteria::orderBy('prioritas')->get();
        $siswas = Siswa::all()->keyBy('nis');

        foreach ($nilaiData as $nis => $values) {
            $siswa = $siswas[$nis] ?? null;
            if (! $siswa) {
                continue;
            }

            foreach ($kriterias as $index => $kriteria) {
                Penilaian::updateOrCreate(
                    [
                        'periode_id' => $periode->id,
                        'id_siswa' => $siswa->id_siswa,
                        'id_kriteria' => $kriteria->id_kriteria,
                    ],
                    ['nilai' => $values[$index]]
                );
            }
        }

        $seleksi = app(SeleksiService::class);
        $kriterias = Kriteria::orderBy('prioritas')->get();
        if ($seleksi->readiness($periode, $kriterias)['ready']) {
            $seleksi->hitungDanSimpan($periode, $kriterias);
        }
    }
}
