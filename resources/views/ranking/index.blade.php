@extends('layouts.app')

@section('title', 'Hasil Perangkingan')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Hasil Perangkingan Siswa</h1>
        <p class="page-subtitle">
            {{ $periode ? 'Periode ' . $periode->nama : 'Belum ada periode aktif' }}
            @if ($periode)
                &middot; {{ $periode->jumlah_berprestasi }} siswa berprestasi
            @endif
        </p>
    </div>
    <div class="d-flex flex-wrap gap-2">
        @if ($periode)
            <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalPengaturanRanking">
                <i class="bi bi-sliders me-1"></i> Atur Jumlah Berprestasi
            </button>
        @endif
        <form action="{{ route('ranking.refresh') }}" method="POST" class="mb-0">
            @csrf
            <button type="submit" class="btn btn-success">
                <i class="bi bi-arrow-clockwise me-1"></i> Refresh Hasil
            </button>
        </form>
    </div>
</div>

@if ($rankings->isEmpty())
    <div class="alert alert-warning">
        <i class="bi bi-exclamation-triangle-fill me-1"></i>
        Belum ada hasil perangkingan. Pastikan nilai lengkap dan bobot ROC sudah dihitung,
        lalu jalankan perhitungan MOORA.
    </div>
@endif

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th class="text-center" style="width:80px;">Rank</th>
                        <th>NIS</th>
                        <th>Nama Siswa</th>
                        <th class="text-center">Nilai Yi</th>
                        <th class="text-center">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($rankings as $ranking)
                        <tr class="{{ $ranking->peringkat === 1 ? 'ranking-1' : '' }}">
                            <td class="text-center">
                                @if ($ranking->peringkat === 1)
                                    <i class="bi bi-trophy-fill trophy me-1"></i>
                                @endif
                                {{ $ranking->peringkat }}
                            </td>
                            <td>{{ $ranking->siswa->nis ?? '-' }}</td>
                            <td>{{ $ranking->siswa->nama ?? '-' }}</td>
                            <td class="text-center fw-semibold">{{ number_format((float) $ranking->nilai_akhir, 4, '.', '.') }}</td>
                            <td class="text-center">
                                <span class="badge {{ $ranking->keterangan === 'Berprestasi' ? 'badge-soft-green' : 'badge-soft-gray' }}">
                                    {{ $ranking->keterangan }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                <div class="empty-state">
                                    <i class="bi bi-bar-chart"></i>
                                    Belum ada data hasil perangkingan.
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($rankings->isNotEmpty())
            <div class="d-flex flex-wrap gap-2 mt-4">
                <a href="{{ route('laporan.csv', ['periode_id' => $periode->id]) }}" class="btn btn-success">
                    <i class="bi bi-file-earmark-excel me-1"></i> Export Excel
                </a>
                <a href="{{ route('laporan.pdf', ['periode_id' => $periode->id]) }}" class="btn btn-danger">
                    <i class="bi bi-file-earmark-pdf me-1"></i> Export PDF
                </a>
                <a href="{{ route('laporan.cetak', ['periode_id' => $periode->id]) }}" target="_blank" class="btn btn-primary">
                    <i class="bi bi-printer me-1"></i> Cetak
                </a>
            </div>
        @endif
    </div>
</div>

@if ($periode)
    <div class="modal fade" id="modalPengaturanRanking" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form action="{{ route('ranking.pengaturan') }}" method="POST" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Atur Jumlah Siswa Berprestasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted" style="font-size:13px;">
                        Tentukan berapa siswa teratas pada periode <strong>{{ $periode->nama }}</strong>
                        yang berstatus <em>Berprestasi</em>. Sisanya berstatus <em>Cadangan</em>.
                    </p>
                    <label class="form-label" for="jumlah_berprestasi">Jumlah siswa berprestasi</label>
                    <input type="number" class="form-control" id="jumlah_berprestasi" name="jumlah_berprestasi"
                           min="0" max="{{ max(1, \App\Models\Siswa::count()) }}"
                           value="{{ old('jumlah_berprestasi', $periode->jumlah_berprestasi) }}" required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
@endif
@endsection

@push('scripts')
<script>
    (function () {
        @if ($errors->has('jumlah_berprestasi'))
            var el = document.getElementById('modalPengaturanRanking');
            if (el) { new bootstrap.Modal(el).show(); }
        @endif
    })();
</script>
@endpush
