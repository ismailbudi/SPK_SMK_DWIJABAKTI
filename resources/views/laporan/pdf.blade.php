<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Hasil Seleksi</title>
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 11px;
            color: #111;
            margin: 0;
            padding: 0;
        }
        .kop {
            display: table;
            width: 100%;
            border-bottom: 3px double #333;
            padding-bottom: 8px;
            margin-bottom: 14px;
        }
        .kop-logo {
            display: table-cell;
            width: 64px;
            vertical-align: middle;
        }
        .kop-logo .circle {
            width: 54px;
            height: 54px;
            border: 2px solid #1E6FD9;
            border-radius: 50%;
            color: #1E6FD9;
            text-align: center;
            font-size: 9px;
            font-weight: bold;
            padding-top: 19px;
        }
        .kop-text { display: table-cell; vertical-align: middle; padding-left: 12px; }
        .kop-text h4 { margin: 0; font-size: 16px; }
        .kop-text h5 { margin: 2px 0 0; font-size: 12px; font-weight: normal; }
        .kop-text p { margin: 2px 0 0; font-size: 9.5px; }
        h3 { text-align: center; font-size: 13px; text-decoration: underline; margin: 14px 0 3px; }
        .period { text-align: center; font-size: 11px; margin-bottom: 16px; }
        h5.section { font-size: 11px; margin: 12px 0 5px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 12px; }
        th, td { border: 1px solid #555; padding: 4px 6px; }
        th { background: #EAF2FF; text-align: center; font-size: 10px; }
        td.center, th.center { text-align: center; }
        .ttd { width: 220px; margin-left: auto; margin-top: 26px; text-align: center; font-size: 11px; }
        .ttd .space { height: 52px; }
        .watermark { text-align: center; font-size: 8.5px; color: #888; margin-top: 16px; }
    </style>
</head>
<body>
    <div class="kop">
        <div class="kop-logo">
            <div class="circle">SMK<br>DB</div>
        </div>
        <div class="kop-text">
            <h4>SEKOLAH MENENGAH KEJURUAN DWIJA BHAKTI</h4>
            <h5>SISTEM PENDUKUNG KEPUTUSAN PEMILIHAN SISWA BERPRESTASI</h5>
            <p>Jl. Pendidikan No. 1, Kecamatan Contoh, Kabupaten Contoh &middot; Telp. (000) 000-0000</p>
        </div>
    </div>

    <h3>LAPORAN HASIL SELEKSI SISWA BERPRESTASI</h3>
    <div class="period">
        @if (in_array('roc', $metode, true) && in_array('moora', $metode, true))
            Metode ROC - MOORA
        @elseif (in_array('roc', $metode, true))
            Metode ROC
        @elseif (in_array('moora', $metode, true))
            Metode MOORA
        @else
            -
        @endif
        <br>
        Periode: {{ $periode->nama ?? '-' }} &middot; Tanggal Cetak: {{ $tanggal->format('d/m/Y') }}
    </div>

    @if (in_array('roc', $metode, true) && $kriterias->isNotEmpty())
        <h5 class="section">A. Kriteria dan Bobot (ROC)</h5>
        <table>
            <thead>
                <tr>
                    <th style="width:50px;">Kode</th>
                    <th>Kriteria</th>
                    <th style="width:70px;">Jenis</th>
                    <th style="width:70px;">Prioritas</th>
                    <th style="width:80px;">Bobot</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($kriterias as $kriteria)
                    <tr>
                        <td class="center">{{ $kriteria->kode }}</td>
                        <td>{{ $kriteria->nama }}</td>
                        <td class="center">{{ ucfirst($kriteria->jenis) }}</td>
                        <td class="center">{{ $kriteria->prioritas }}</td>
                        <td class="center">{{ $kriteria->bobot_roc !== null ? number_format((float) $kriteria->bobot_roc, 4, '.', '.') : '-' }}</td>
                    </tr>
                @endforeach
                <tr>
                    <th colspan="4" style="text-align:right;">Total Bobot</th>
                    <th class="center">{{ number_format((float) $kriterias->sum('bobot_roc'), 4, '.', '.') }}</th>
                </tr>
            </tbody>
        </table>
    @endif

    @if (in_array('moora', $metode, true))
        <h5 class="section">{{ in_array('roc', $metode, true) ? 'B.' : 'A.' }} Hasil Perangkingan (MOORA)</h5>
        <table>
            <thead>
                <tr>
                    <th style="width:45px;">Rank</th>
                    <th style="width:70px;">NIS</th>
                    <th>Nama Siswa</th>
                    <th style="width:80px;">Kelas</th>
                    <th style="width:80px;">Nilai Yi</th>
                    <th style="width:90px;">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($rankings as $row)
                    <tr>
                        <td class="center">{{ $row['peringkat'] }}</td>
                        <td class="center">{{ $row['nis'] }}</td>
                        <td>{{ $row['nama'] }}</td>
                        <td class="center">{{ $row['kelas'] }}</td>
                        <td class="center">{{ number_format((float) $row['nilai_akhir'], 4, '.', '.') }}</td>
                        <td class="center">{{ $row['keterangan'] }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="center">Belum ada hasil perangkingan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    @endif

    <div class="ttd">
        Mengetahui,<br>
        Kepala SMK Dwija Bhakti
        <div class="space"></div>
        <strong>__________________________</strong>
    </div>

    <div class="watermark">
        Dicetak oleh Sistem Pendukung Keputusan ROC-MOORA &middot; {{ $tanggal->format('d/m/Y H:i') }}
    </div>
</body>
</html>
