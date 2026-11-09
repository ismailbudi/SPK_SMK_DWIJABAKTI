@extends('layouts.app')

@section('title', 'Data Kriteria')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Data Kriteria</h1>
        <p class="page-subtitle">Atur kriteria penilaian dan urutan prioritasnya (1 = paling penting).</p>
    </div>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahKriteria">
        <i class="bi bi-plus-lg me-1"></i> Tambah Kriteria
    </button>
</div>

@if ($perluHitung)
    <div class="alert alert-warning">
        <i class="bi bi-exclamation-triangle-fill me-1"></i>
        Bobot ROC belum lengkap atau data kriteria baru berubah.
        Silakan buka halaman <a href="{{ route('roc.index') }}" class="fw-semibold">Perhitungan ROC</a> untuk menghitung ulang bobot.
    </div>
@endif

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th style="width:80px;">Kode</th>
                        <th>Nama Kriteria</th>
                        <th class="text-center">Jenis</th>
                        <th class="text-center">Bobot</th>
                        <th class="text-center">Prioritas</th>
                        <th class="text-center" style="width:100px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($kriterias as $kriteria)
                        <tr>
                            <td><span class="badge badge-soft-blue">{{ $kriteria->kode }}</span></td>
                            <td>{{ $kriteria->nama }}</td>
                            <td class="text-center">
                                <span class="badge {{ $kriteria->jenis === 'benefit' ? 'badge-soft-green' : 'badge-soft-red' }}">
                                    {{ ucfirst($kriteria->jenis) }}
                                </span>
                            </td>
                            <td class="text-center">
                                {{ $kriteria->bobot_roc !== null ? number_format((float) $kriteria->bobot_roc, 4, '.', '.') : '—' }}
                            </td>
                            <td class="text-center">{{ $kriteria->prioritas }}</td>
                            <td>
                                <div class="btn-group-actions">
                                    <button type="button" class="btn btn-warning btn-icon" title="Ubah"
                                            data-bs-toggle="modal" data-bs-target="#modalEditKriteria{{ $kriteria->id_kriteria }}">
                                        <i class="bi bi-pencil-fill"></i>
                                    </button>
                                    <button type="button" class="btn btn-danger btn-icon" title="Hapus"
                                            data-bs-toggle="modal" data-bs-target="#modalHapusKriteria{{ $kriteria->id_kriteria }}">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
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
                                {{ number_format((float) $kriterias->sum('bobot_roc'), 4, '.', '.') }}
                            </th>
                            <th colspan="2"></th>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>

        <p class="text-muted mt-3 mb-0" style="font-size:12px;">
            <i class="bi bi-info-circle me-1"></i>
            Jumlah kriteria minimal 2 dan maksimal 10. Prioritas harus unik dan berurutan mulai dari 1. dan Jumlah total bobot ROC harus sama dengan 1. Jika tidak, silakan lakukan perhitungan ulang di halaman <a href="{{ route('roc.index') }}">Perhitungan ROC</a>.
        </p>
    </div>
</div>

<div class="modal fade" id="modalTambahKriteria" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('kriteria.store') }}" method="POST" class="modal-content">
            @csrf
            <input type="hidden" name="form_mode" value="create">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Kriteria</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Kode</label>
                    <input type="text" class="form-control" name="kode" placeholder="Contoh: C6"
                           value="{{ old('form_mode') === 'create' ? old('kode') : '' }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Nama Kriteria</label>
                    <input type="text" class="form-control" name="nama"
                           value="{{ old('form_mode') === 'create' ? old('nama') : '' }}" required>
                </div>
                <div class="row g-3">
                    <div class="col-6">
                        <label class="form-label">Jenis</label>
                        <select class="form-select" name="jenis" required>
                            <option value="benefit" {{ old('form_mode') === 'create' && old('jenis') === 'cost' ? '' : 'selected' }}>Benefit</option>
                            <option value="cost" {{ old('form_mode') === 'create' && old('jenis') === 'cost' ? 'selected' : '' }}>Cost</option>
                        </select>
                    </div>
                    <div class="col-6">
                        <label class="form-label">Prioritas</label>
                        <input type="number" class="form-control" name="prioritas" min="1"
                               value="{{ old('form_mode') === 'create' ? old('prioritas', $kriterias->count() + 1) : $kriterias->count() + 1 }}" required>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

@foreach ($kriterias as $kriteria)
    <div class="modal fade" id="modalEditKriteria{{ $kriteria->id_kriteria }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form action="{{ route('kriteria.update', $kriteria) }}" method="POST" class="modal-content">
                @csrf
                @method('PUT')
                <input type="hidden" name="form_mode" value="edit">
                <input type="hidden" name="edit_id" value="{{ $kriteria->id_kriteria }}">
                <div class="modal-header">
                    <h5 class="modal-title">Ubah Kriteria</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    @php
                        $match = old('form_mode') === 'edit' && (int) old('edit_id') === $kriteria->id_kriteria;
                    @endphp
                    <div class="mb-3">
                        <label class="form-label">Kode</label>
                        <input type="text" class="form-control" name="kode" value="{{ $match ? old('kode') : $kriteria->kode }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Kriteria</label>
                        <input type="text" class="form-control" name="nama" value="{{ $match ? old('nama') : $kriteria->nama }}" required>
                    </div>
                    <div class="row g-3">
                        <div class="col-6">
                            <label class="form-label">Jenis</label>
                            @php $jenis = $match ? old('jenis') : $kriteria->jenis; @endphp
                            <select class="form-select" name="jenis" required>
                                <option value="benefit" {{ $jenis === 'benefit' ? 'selected' : '' }}>Benefit</option>
                                <option value="cost" {{ $jenis === 'cost' ? 'selected' : '' }}>Cost</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Prioritas</label>
                            <input type="number" class="form-control" name="prioritas" min="1"
                                   value="{{ $match ? old('prioritas') : $kriteria->prioritas }}" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="modalHapusKriteria{{ $kriteria->id_kriteria }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form action="{{ route('kriteria.destroy', $kriteria) }}" method="POST" class="modal-content">
                @csrf
                @method('DELETE')
                <div class="modal-header">
                    <h5 class="modal-title">Hapus Kriteria</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    Yakin ingin menghapus kriteria <strong>{{ $kriteria->nama }}</strong>?
                    Nilai dan hasil perhitungan terkait kriteria ini akan dihapus.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </div>
            </form>
        </div>
    </div>
@endforeach
@endsection

@push('scripts')
<script>
    (function () {
        @if ($errors->any() && old('form_mode') === 'create')
            new bootstrap.Modal(document.getElementById('modalTambahKriteria')).show();
        @endif
        @if ($errors->any() && old('form_mode') === 'edit' && old('edit_id'))
            var el = document.getElementById('modalEditKriteria{{ (int) old('edit_id') }}');
            if (el) { new bootstrap.Modal(el).show(); }
        @endif
    })();
</script>
@endpush
