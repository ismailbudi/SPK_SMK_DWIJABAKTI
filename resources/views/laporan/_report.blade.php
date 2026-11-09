<div class="report-paper">
    <div class="kop">
        <div class="kop-logo">
            <i class="bi bi-mortarboard-fill"></i>
        </div>
        <div>
            <h4>SEKOLAH MENENGAH KEJURUAN DWIJA BHAKTI</h4>
            <h5>SISTEM PENDUKUNG KEPUTUSAN PEMILIHAN SISWA BERPRESTASI</h5>
            <p>Jl. Pendidikan No. 1, Kecamatan Contoh, Kabupaten Contoh &middot; Telp. (000) 000-0000</p>
        </div>
    </div>

    <h3>LAPORAN HASIL SELEKSI SISWA BERPRESTASI</h3>
    <div class="report-period">
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
        Periode: {{ $periode->nama ?? '-' }}
        &middot; Tanggal Cetak: {{ $tanggal->format('d/m/Y') }}
    </div>

    @if (empty($rankings) && $kriterias->isEmpty())
        <p style="text-align:center;padding:30px 0;">Belum ada data untuk ditampilkan pada periode ini.</p>
    @endif

    @if (in_array('roc', $metode, true) && $kriterias->isNotEmpty())
        <h5 style="font-size:13px;font-weight:700;margin:12px 0 6px;">A. Kriteria dan Bobot (ROC)</h5>
        <table>
            <thead>
                <tr>
                    <th style="width:60px;">Kode</th>
                    <th>Kriteria</th>
                    <th style="width:80px;">Jenis</th>
                    <th style="width:80px;">Prioritas</th>
                    <th style="width:90px;">Bobot</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($kriterias as $kriteria)
                    <tr>
                        <td style="text-align:center;">{{ $kriteria->kode }}</td>
                        <td>{{ $kriteria->nama }}</td>
                        <td style="text-align:center;">{{ ucfirst($kriteria->jenis) }}</td>
                        <td style="text-align:center;">{{ $kriteria->prioritas }}</td>
                        <td style="text-align:center;">{{ $kriteria->bobot_roc !== null ? number_format((float) $kriteria->bobot_roc, 4, '.', '.') : '-' }}</td>
                    </tr>
                @endforeach
                <tr>
                    <th colspan="4" style="text-align:right;">Total Bobot</th>
                    <th style="text-align:center;">{{ number_format((float) $kriterias->sum('bobot_roc'), 4, '.', '.') }}</th>
                </tr>
            </tbody>
        </table>
    @endif

    @if (in_array('moora', $metode, true))
        <h5 style="font-size:13px;font-weight:700;margin:12px 0 6px;">
            {{ in_array('roc', $metode, true) ? 'B.' : 'A.' }} Hasil Perangkingan (MOORA)
        </h5>
        <table>
            <thead>
                <tr>
                    <th style="width:55px;">Rank</th>
                    <th style="width:80px;">NIS</th>
                    <th>Nama Siswa</th>
                    <th style="width:90px;">Kelas</th>
                    <th style="width:90px;">Nilai Yi</th>
                    <th style="width:110px;">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($rankings as $row)
                    <tr>
                        <td style="text-align:center;">{{ $row['peringkat'] }}</td>
                        <td style="text-align:center;">{{ $row['nis'] }}</td>
                        <td>{{ $row['nama'] }}</td>
                        <td style="text-align:center;">{{ $row['kelas'] }}</td>
                        <td style="text-align:center;">{{ number_format((float) $row['nilai_akhir'], 4, '.', '.') }}</td>
                        <td style="text-align:center;">{{ $row['keterangan'] }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align:center;">Belum ada hasil perangkingan.</td>
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

    <div class="report-watermark">
        Dicetak oleh Sistem Pendukung Keputusan ROC-MOORA &middot; {{ $tanggal->format('d/m/Y H:i') }}
    </div>
</div>
