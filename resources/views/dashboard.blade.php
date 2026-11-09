@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Dashboard</h1>
        <p class="page-subtitle">
            Ringkasan sistem pendukung keputusan pemilihan siswa berprestasi
            @if ($periode) &middot; Periode {{ $periode->nama }} @endif
        </p>
    </div>
    @if ($periode)
        <span class="badge {{ $periode->aktif ? 'badge-soft-green' : 'badge-soft-gray' }}">
            <i class="bi bi-calendar-check me-1"></i>{{ $periode->aktif ? 'Periode Aktif' : 'Periode' }}
        </span>
    @endif
</div>

<div class="row g-3 mb-3">
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card stat-card">
            <div class="stat-icon blue"><i class="bi bi-people-fill"></i></div>
            <div>
                <div class="stat-value">{{ $totalSiswa }}</div>
                <div class="stat-label">Jumlah Siswa</div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card stat-card">
            <div class="stat-icon green"><i class="bi bi-list-check"></i></div>
            <div>
                <div class="stat-value">{{ $totalKriteria }}</div>
                <div class="stat-label">Jumlah Kriteria</div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card stat-card">
            <div class="stat-icon yellow"><i class="bi bi-clipboard-data-fill"></i></div>
            <div>
                <div class="stat-value">{{ $totalPenilaian }}</div>
                <div class="stat-label">Data Penilaian</div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card stat-card">
            <div class="stat-icon purple"><i class="bi bi-trophy-fill"></i></div>
            <div>
                <div class="stat-value" style="font-size:24px;">
                    <span class="badge {{ $hasilSeleksi ? 'badge-soft-green' : 'badge-soft-red' }}">
                        {{ $hasilSeleksi ? 'Siap' : 'Belum Siap' }}
                    </span>
                </div>
                <div class="stat-label">Hasil Seleksi</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-12 col-lg-7">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <span><i class="bi bi-bar-chart-fill me-2 text-primary"></i>Grafik Penilaian Siswa</span>
                <small class="text-muted">Rata-rata per kriteria</small>
            </div>
            <div class="card-body">
                @if ($kriterias->isEmpty())
                    <div class="empty-state">
                        <i class="bi bi-bar-chart"></i>
                        Belum ada data kriteria.
                    </div>
                @else
                    <canvas id="chartPenilaian" height="110"></canvas>
                @endif
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-5">
        <div class="card h-100">
            <div class="card-header"><i class="bi bi-info-circle-fill me-2 text-primary"></i>Informasi Sistem</div>
            <div class="card-body">
                <div class="info-block">
                    <h6><i class="bi bi-calculator text-primary"></i> Metode ROC</h6>
                    <p>Digunakan untuk menentukan bobot kriteria berdasarkan prioritas.</p>
                </div>
                <div class="info-block">
                    <h6><i class="bi bi-diagram-3 text-primary"></i> Metode MOORA</h6>
                    <p>Digunakan untuk perangkingan alternatif (siswa).</p>
                </div>
                <div class="info-block">
                    <h6><i class="bi bi-trophy text-warning"></i> Hasil Seleksi</h6>
                    <p>
                        @if ($totalRanking > 0)
                            {{ $totalRanking }} siswa sudah memiliki hasil perangkingan.
                        @else
                            Belum ada hasil perangkingan yang tersimpan.
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card mt-3">
    <div class="card-header"><i class="bi bi-signpost-split-fill me-2 text-primary"></i>Progres Langkah Seleksi</div>
    <div class="card-body">
        <div class="stepper">
            @foreach ($langkah as $nama => $selesai)
                <div class="step-item {{ $selesai ? 'done' : '' }}">
                    <i class="bi {{ $selesai ? 'bi-check-circle-fill' : 'bi-circle' }}"></i>
                    {{ $nama }}
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    (function () {
        const canvas = document.getElementById('chartPenilaian');
        if (!canvas) return;

        new Chart(canvas, {
            type: 'bar',
            data: {
                labels: @json($kriterias->map(fn ($k) => $k->kode . ' - ' . $k->nama)->values()),
                datasets: [{
                    label: 'Rata-rata Nilai',
                    data: @json($rataRata),
                    backgroundColor: '#1E6FD9',
                    borderRadius: 6,
                    maxBarThickness: 46
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                    tooltip: { backgroundColor: '#11264A' }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        ticks: { color: '#6B778C' },
                        grid: { color: '#EDF1F7' }
                    },
                    x: {
                        ticks: { color: '#6B778C', maxRotation: 0, autoSkip: false },
                        grid: { display: false }
                    }
                }
            }
        });
    })();
</script>
@endpush
