@extends('layouts.app')

@section('title', 'Perhitungan MOORA')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Perhitungan MOORA</h1>
        <p class="page-subtitle">
            Multi-Objective Optimization on the basis of Ratio Analysis
            @if ($periode) &middot; {{ $periode->nama }} @endif
        </p>
    </div>
    <form action="{{ route('moora.hitung') }}" method="POST" class="mb-0">
        @csrf
        <button type="submit" class="btn btn-primary" {{ ! $readiness['ready'] ? 'disabled' : '' }}>
            <i class="bi bi-play-fill me-1"></i> Hitung &amp; Simpan MOORA
        </button>
    </form>
</div>

@if (! $readiness['ready'])
    <div class="alert alert-warning">
        <div class="fw-semibold mb-1">
            <i class="bi bi-exclamation-triangle-fill me-1"></i> Prasyarat perhitungan belum terpenuhi:
        </div>
        <ul class="mb-0 ps-3">
            @foreach ($readiness['messages'] as $pesan)
                <li>{{ $pesan }}</li>
            @endforeach
        </ul>
    </div>
@elseif ($sudahDisimpan)
    <div class="alert alert-success">
        <i class="bi bi-check-circle-fill me-1"></i>
        Hasil perhitungan MOORA sudah tersimpan untuk periode ini. Menghitung ulang akan mengganti hasil sebelumnya.
    </div>
@endif

@if ($result)
    @php
        $altMap = collect($result['alternatives'])->keyBy('id_siswa');
        $batas = (int) ($periode->jumlah_berprestasi ?? 0);
    @endphp

    <div class="accordion" id="accordionMoora">
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#tahap1">
                    Tahap 1 &ndash; Matriks Keputusan (X)
                </button>
            </h2>
            <div id="tahap1" class="accordion-collapse collapse show" data-bs-parent="#accordionMoora">
                <div class="accordion-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th style="width:60px;" class="text-center">No</th>
                                    <th>NIS</th>
                                    <th>Nama Siswa</th>
                                    @foreach ($result['criteria'] as $kriteria)
                                        <th class="text-center">{{ $kriteria['kode'] }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($result['alternatives'] as $alt)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>{{ $alt['nis'] }}</td>
                                        <td>{{ $alt['nama'] }}</td>
                                        @foreach ($result['criteria'] as $kriteria)
                                            <td class="text-center">{{ number_format((float) ($alt['nilai'][$kriteria['id_kriteria']] ?? 0), 2, '.', '.') }}</td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#tahap2">
                    Tahap 2 &ndash; Normalisasi Matriks (x*)
                </button>
            </h2>
            <div id="tahap2" class="accordion-collapse collapse" data-bs-parent="#accordionMoora">
                <div class="accordion-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th style="width:60px;" class="text-center">No</th>
                                    <th>Nama Siswa</th>
                                    @foreach ($result['criteria'] as $kriteria)
                                        <th class="text-center">{{ $kriteria['kode'] }}</th>
                                    @endforeach
                                </tr>
                                <tr>
                                    <th colspan="2" class="text-end text-muted">Pembagi &radic;&Sigma;x&sup2;</th>
                                    @foreach ($result['criteria'] as $kriteria)
                                        <th class="text-center text-muted" style="font-weight:500;">
                                            {{ number_format($result['divisors'][$kriteria['id_kriteria']] ?? 0, 4, '.', '.') }}
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($result['alternatives'] as $alt)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>{{ $alt['nama'] }}</td>
                                        @foreach ($result['criteria'] as $kriteria)
                                            <td class="text-center">
                                                {{ number_format($result['normalized'][$alt['id_siswa']][$kriteria['id_kriteria']] ?? 0, 4, '.', '.') }}
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#tahap3">
                    Tahap 3 &ndash; Normalisasi Terbobot (y = w &times; x*)
                </button>
            </h2>
            <div id="tahap3" class="accordion-collapse collapse" data-bs-parent="#accordionMoora">
                <div class="accordion-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th style="width:60px;" class="text-center">No</th>
                                    <th>Nama Siswa</th>
                                    @foreach ($result['criteria'] as $kriteria)
                                        <th class="text-center">{{ $kriteria['kode'] }}</th>
                                    @endforeach
                                </tr>
                                <tr>
                                    <th colspan="2" class="text-end text-muted">Bobot</th>
                                    @foreach ($result['criteria'] as $kriteria)
                                        <th class="text-center text-muted" style="font-weight:500;">
                                            {{ number_format((float) $kriteria['bobot_roc'], 4, '.', '.') }}
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($result['alternatives'] as $alt)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>{{ $alt['nama'] }}</td>
                                        @foreach ($result['criteria'] as $kriteria)
                                            <td class="text-center">
                                                {{ number_format($result['weighted'][$alt['id_siswa']][$kriteria['id_kriteria']] ?? 0, 4, '.', '.') }}
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#tahap4">
                    Tahap 4 &ndash; Nilai Optimasi (Yi)
                </button>
            </h2>
            <div id="tahap4" class="accordion-collapse collapse" data-bs-parent="#accordionMoora">
                <div class="accordion-body">
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
                                @foreach ($result['ranking'] as $item)
                                    @php $alt = $altMap->get($item['id_siswa']); @endphp
                                    <tr class="{{ $item['peringkat'] === 1 ? 'ranking-1' : '' }}">
                                        <td class="text-center">
                                            @if ($item['peringkat'] === 1)
                                                <i class="bi bi-trophy-fill trophy me-1"></i>
                                            @endif
                                            {{ $item['peringkat'] }}
                                        </td>
                                        <td>{{ $alt['nis'] ?? '-' }}</td>
                                        <td>{{ $alt['nama'] ?? '-' }}</td>
                                        <td class="text-center fw-semibold">{{ number_format((float) $item['nilai_akhir'], 4, '.', '.') }}</td>
                                        <td class="text-center">
                                            <span class="badge {{ $item['peringkat'] <= $batas ? 'badge-soft-green' : 'badge-soft-gray' }}">
                                                {{ $item['peringkat'] <= $batas ? 'Berprestasi' : 'Cadangan' }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <p class="text-muted mb-0 mt-2" style="font-size:12px;">
                        <i class="bi bi-info-circle me-1"></i>
                        Yi = &Sigma; (benefit) &minus; &Sigma; (cost). Peringkat diurutkan dari Yi tertinggi.
                    </p>
                </div>
            </div>
        </div>
    </div>
@elseif ($readiness['ready'] === false)
    <div class="card">
        <div class="card-body">
            <div class="empty-state">
                <i class="bi bi-diagram-3"></i>
                Lengkapi prasyarat di atas untuk menjalankan perhitungan MOORA.
            </div>
        </div>
    </div>
@endif
@endsection
