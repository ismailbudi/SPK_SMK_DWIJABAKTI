@extends('layouts.app')

@section('title', 'Perhitungan ROC')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Perhitungan Bobot ROC</h1>
        <p class="page-subtitle">Rank Order Centroid (ROC) menentukan bobot dari urutan prioritas kriteria.</p>
    </div>
    <form action="{{ route('roc.hitung') }}" method="POST" class="mb-0">
        @csrf
        <button type="submit" class="btn btn-primary" {{ $n < 2 ? 'disabled' : '' }}>
            <i class="bi bi-calculator-fill me-1"></i> Hitung Bobot ROC
        </button>
    </form>
</div>

@if ($n < 2)
    <div class="alert alert-warning">
        <i class="bi bi-exclamation-triangle-fill me-1"></i>
        Minimal 2 kriteria diperlukan untuk menghitung bobot ROC.
    </div>
@endif

<div class="card mb-3">
    <div class="card-body">
        <div class="alert alert-info mb-0">
            <strong>Rumus ROC:</strong>
            W<sub>k</sub> = (1/n) &times; &Sigma;<sub>i=k..n</sub> (1/i),
            dengan n = jumlah kriteria ({{ $n }}) dan k = urutan prioritas.
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th class="text-center" style="width:100px;">Prioritas</th>
                        <th>Kriteria</th>
                        <th>Rumus ROC</th>
                        <th class="text-center" style="width:130px;">Bobot (W)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($kriterias as $kriteria)
                        @php
                            $bobot = $kriteria->bobot_roc !== null ? (float) $kriteria->bobot_roc : ($preview[$kriteria->prioritas] ?? 0);
                        @endphp
                        <tr>
                            <td class="text-center">{{ $kriteria->prioritas }}</td>
                            <td>
                                <span class="badge badge-soft-blue me-1">{{ $kriteria->kode }}</span>
                                {{ $kriteria->nama }}
                            </td>
                            <td>
                                <span class="text-muted">(1/{{ $n }}) &times;</span>
                                [ {{ $formulas[$kriteria->prioritas] ?? '-' }} ]
                            </td>
                            <td class="text-center fw-semibold">{{ number_format($bobot, 4, '.', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">
                                <div class="empty-state">
                                    <i class="bi bi-list-check"></i>
                                    Belum ada data kriteria.
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                @if ($kriterias->isNotEmpty())
                    <tfoot>
                        <tr>
                            <th colspan="3" class="text-end">Total Bobot</th>
                            <th class="text-center">
                                @php
                                    $total = $kriterias->sum(fn ($k) => $k->bobot_roc !== null ? (float) $k->bobot_roc : ($preview[$k->prioritas] ?? 0));
                                @endphp
                                {{ number_format($total, 4, '.', '.') }}
                                @if (abs($total - 1) < 0.0001)
                                    <span class="badge badge-soft-green ms-1"><i class="bi bi-check-circle me-1"></i>Valid</span>
                                @else
                                    <span class="badge badge-soft-red ms-1">Belum valid</span>
                                @endif
                            </th>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>

        <p class="text-muted mt-3 mb-0" style="font-size:12px;">
            <i class="bi bi-info-circle me-1"></i>
            Bobot disimpan ke data kriteria (read-only). Mengubah kriteria atau prioritas akan menandai bobot perlu dihitung ulang.
        </p>
    </div>
</div>
@endsection
