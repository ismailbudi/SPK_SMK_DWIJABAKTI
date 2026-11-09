@extends('layouts.app')

@section('title', 'Laporan')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Laporan Hasil Seleksi</h1>
        <p class="page-subtitle">Pratinjau dan cetak laporan hasil seleksi siswa berprestasi.</p>
    </div>
</div>

<div class="row g-3">
    <div class="col-12 col-lg-4">
        <div class="card filter-card">
            <div class="card-header"><i class="bi bi-funnel-fill me-2 text-primary"></i>Filter Laporan</div>
            <div class="card-body">
                <form method="GET" action="{{ route('laporan.index') }}">
                    <div class="mb-3">
                        <label class="form-label" for="periode_id">Periode</label>
                        <select class="form-select" id="periode_id" name="periode_id">
                            @forelse ($periodeList as $item)
                                <option value="{{ $item->id }}" {{ $periode && $periode->id === $item->id ? 'selected' : '' }}>
                                    {{ $item->nama }}
                                </option>
                            @empty
                                <option value="">Belum ada periode</option>
                            @endforelse
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="tanggal">Tanggal Hitung</label>
                        <input type="date" class="form-control" id="tanggal" name="tanggal"
                               value="{{ $tanggal->format('Y-m-d') }}">
                    </div>

                    <div class="mb-4">
                        <label class="form-label d-block">Metode</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="metode[]" value="roc" id="metodeRoc"
                                   {{ in_array('roc', $metode, true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="metodeRoc">ROC (Kriteria &amp; Bobot)</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="metode[]" value="moora" id="metodeMoora"
                                   {{ in_array('moora', $metode, true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="metodeMoora">MOORA (Perangkingan)</label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-eye-fill me-1"></i> Tampilkan Laporan
                    </button>
                </form>
            </div>
        </div>

        <div class="card mt-3 no-print">
            <div class="card-body d-grid gap-2">
                <a href="{{ route('laporan.csv', request()->query()) }}" class="btn btn-success">
                    <i class="bi bi-file-earmark-excel me-1"></i> Export Excel
                </a>
                <a href="{{ route('laporan.pdf', request()->query()) }}" class="btn btn-danger">
                    <i class="bi bi-file-earmark-pdf me-1"></i> Export PDF
                </a>
                <a href="{{ route('laporan.cetak', request()->query()) }}" target="_blank" class="btn btn-primary">
                    <i class="bi bi-printer me-1"></i> Cetak
                </a>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-8">
        @if (! $periode)
            <div class="alert alert-warning">
                <i class="bi bi-exclamation-triangle-fill me-1"></i> Belum ada periode. Tambahkan periode terlebih dahulu.
            </div>
        @else
            <div id="previewLaporan">
                @include('laporan._report')
            </div>
        @endif
    </div>
</div>
@endsection
