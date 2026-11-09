<?php

namespace App\Http\Controllers;

use App\Models\Periode;
use App\Models\Ranking;
use App\Services\SeleksiService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index(Request $request, SeleksiService $seleksi)
    {
        $data = $this->prepare($request, $seleksi);

        return view('laporan.index', $data);
    }

    public function cetak(Request $request, SeleksiService $seleksi)
    {
        $data = $this->prepare($request, $seleksi);

        return view('laporan.cetak', $data);
    }

    public function exportPdf(Request $request, SeleksiService $seleksi)
    {
        $data = $this->prepare($request, $seleksi);

        $namaFile = 'laporan-seleksi-' . str_replace(['/', ' '], '-', $data['periode']->nama ?? 'periode') . '.pdf';

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('laporan.pdf', $data)
            ->setPaper('a4', 'portrait');

        return $pdf->download($namaFile);
    }

    public function exportCsv(Request $request, SeleksiService $seleksi)
    {
        $data = $this->prepare($request, $seleksi);

        $namaFile = 'laporan-seleksi-' . str_replace(['/', ' '], '-', $data['periode']->nama ?? 'periode') . '.csv';

        return response()->streamDownload(function () use ($data) {
            $out = fopen('php://output', 'w');
            fwrite($out, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($out, ['LAPORAN HASIL SELEKSI SISWA BERPRESTASI'], ',', '"', '\\');
            fputcsv($out, ['SMK DWIJA BHAKTI'], ',', '"', '\\');
            fputcsv($out, ['Metode: ROC - MOORA'], ',', '"', '\\');
            fputcsv($out, ['Periode', $data['periode']->nama ?? '-'], ',', '"', '\\');
            fputcsv($out, ['Tanggal Cetak', $data['tanggal']->format('d/m/Y')], ',', '"', '\\');
            fputcsv($out, [], ',', '"', '\\');

            if (in_array('roc', $data['metode'], true)) {
                fputcsv($out, ['KRITERIA DAN BOBOT'], ',', '"', '\\');
                fputcsv($out, ['Kode', 'Kriteria', 'Jenis', 'Prioritas', 'Bobot'], ',', '"', '\\');
                foreach ($data['kriterias'] as $kriteria) {
                    fputcsv($out, [
                        $kriteria->kode,
                        $kriteria->nama,
                        ucfirst($kriteria->jenis),
                        $kriteria->prioritas,
                        number_format((float) $kriteria->bobot_roc, 4, ',', '.'),
                    ], ',', '"', '\\');
                }
                fputcsv($out, [], ',', '"', '\\');
            }

            fputcsv($out, ['HASIL PERANGKINGAN'], ',', '"', '\\');
            fputcsv($out, ['Rank', 'NIS', 'Nama', 'Kelas', 'Nilai Yi', 'Keterangan'], ',', '"', '\\');
            foreach ($data['rankings'] as $row) {
                fputcsv($out, [
                    $row['peringkat'],
                    $row['nis'],
                    $row['nama'],
                    $row['kelas'],
                    number_format((float) $row['nilai_akhir'], 4, ',', '.'),
                    $row['keterangan'],
                ], ',', '"', '\\');
            }

            fclose($out);
        }, $namaFile, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    protected function prepare(Request $request, SeleksiService $seleksi): array
    {
        $periodeList = Periode::orderByDesc('tahun_ajaran')->orderBy('semester')->get();
        $periode = null;

        if ($request->filled('periode_id')) {
            $periode = $periodeList->firstWhere('id', (int) $request->query('periode_id'));
        }

        $periode = $periode ?? $seleksi->periodeAktif() ?? $periodeList->first();

        $tanggal = $request->filled('tanggal')
            ? Carbon::parse($request->query('tanggal'))
            : Carbon::now();

        $metode = $request->has('metode')
            ? (array) $request->query('metode')
            : ['roc', 'moora'];

        $kriterias = $seleksi->kriterias();

        $rankings = [];
        if ($periode) {
            $rows = Ranking::with('siswa')
                ->where('periode_id', $periode->id)
                ->orderBy('peringkat')
                ->get();

            foreach ($rows as $row) {
                $rankings[] = [
                    'peringkat' => $row->peringkat,
                    'nis' => $row->siswa->nis ?? '-',
                    'nama' => $row->siswa->nama ?? '-',
                    'kelas' => $row->siswa->kelas ?? '-',
                    'nilai_akhir' => (float) $row->nilai_akhir,
                    'keterangan' => $row->keterangan,
                ];
            }

            if (empty($rankings)) {
                $readiness = $seleksi->readiness($periode, $kriterias);
                if ($readiness['ready']) {
                    $result = $seleksi->compute($periode, $kriterias);
                    $alternatif = collect($result['alternatives'])->keyBy('id_siswa');
                    $batas = (int) $periode->jumlah_berprestasi;

                    foreach ($result['ranking'] as $item) {
                        $alt = $alternatif->get($item['id_siswa']);
                        $rankings[] = [
                            'peringkat' => $item['peringkat'],
                            'nis' => $alt['nis'] ?? '-',
                            'nama' => $alt['nama'] ?? '-',
                            'kelas' => $alt['kelas'] ?? '-',
                            'nilai_akhir' => (float) $item['nilai_akhir'],
                            'keterangan' => $item['peringkat'] <= $batas ? 'Berprestasi' : 'Cadangan',
                        ];
                    }
                }
            }
        }

        return [
            'periodeList' => $periodeList,
            'periode' => $periode,
            'tanggal' => $tanggal,
            'metode' => $metode,
            'kriterias' => $kriterias,
            'rankings' => $rankings,
        ];
    }
}
