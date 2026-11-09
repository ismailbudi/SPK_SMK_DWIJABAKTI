@extends('layouts.app')

@section('title', 'Input Nilai')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Input Nilai Siswa</h1>
        <p class="page-subtitle">
            Masukkan nilai setiap kriteria untuk siswa pada periode
            {{ $periode->nama ?? 'aktif' }}.
        </p>
    </div>
</div>

@if (! $periode)
    <div class="alert alert-warning">
        <i class="bi bi-exclamation-triangle-fill me-1"></i> Belum ada periode aktif. Tambahkan periode terlebih dahulu.
    </div>
@elseif ($kriterias->isEmpty())
    <div class="alert alert-warning">
        <i class="bi bi-exclamation-triangle-fill me-1"></i>
        Belum ada data kriteria. Silakan tambahkan kriteria terlebih dahulu di halaman
        <a href="{{ route('kriteria.index') }}" class="fw-semibold">Data Kriteria</a>.
    </div>
@elseif ($siswas->isEmpty())
    <div class="alert alert-warning">
        <i class="bi bi-exclamation-triangle-fill me-1"></i>
        Belum ada data siswa. Silakan tambahkan siswa terlebih dahulu di halaman
        <a href="{{ route('siswa.index') }}" class="fw-semibold">Data Siswa</a>.
    </div>
@else
    <div class="card">
        <div class="card-body">
            <form method="GET" action="{{ route('nilai.index') }}" class="mb-4">
                <label class="form-label" for="pilihSiswa">Pilih Siswa</label>
                <div class="row g-2 align-items-center">
                    <div class="col-12 col-md-6">
                        <select class="form-select" id="pilihSiswa" name="siswa" onchange="this.form.submit()">
                            @foreach ($siswas as $siswa)
                                <option value="{{ $siswa->id_siswa }}" {{ $selected && $selected->id_siswa === $siswa->id_siswa ? 'selected' : '' }}>
                                    {{ $siswa->nama }} ({{ $siswa->nis }}) &ndash; {{ $siswa->kelas }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-md-6">
                        @if ($selected)
                            @if ($lengkap)
                                <span class="badge badge-soft-green"><i class="bi bi-check-circle me-1"></i>Nilai lengkap</span>
                            @else
                                <span class="badge badge-soft-yellow"><i class="bi bi-exclamation-circle me-1"></i>Nilai belum lengkap</span>
                            @endif
                        @endif
                    </div>
                </div>
            </form>

            @if ($selected)
                <form method="POST" action="{{ route('nilai.store') }}" id="formNilai">
                    @csrf
                    <input type="hidden" name="id_siswa" value="{{ $selected->id_siswa }}">

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width:60px;">No</th>
                                    <th>Kriteria</th>
                                    <th class="text-center" style="width:130px;">Jenis</th>
                                    <th class="text-center" style="width:200px;">Nilai</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($kriterias as $kriteria)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>
                                            <span class="badge badge-soft-blue me-1">{{ $kriteria->kode }}</span>
                                            {{ $kriteria->nama }}
                                        </td>
                                        <td class="text-center">
                                            <span class="badge {{ $kriteria->jenis === 'benefit' ? 'badge-soft-green' : 'badge-soft-red' }}">
                                                {{ ucfirst($kriteria->jenis) }}
                                            </span>
                                        </td>
                                        <td>
                                            <input type="number" step="0.01" min="0" max="100"
                                                   class="form-control text-center nilai-input"
                                                   name="nilai[{{ $kriteria->id_kriteria }}]"
                                                   value="{{ old('nilai.' . $kriteria->id_kriteria, $nilai[$kriteria->id_kriteria] ?? '') }}"
                                                   placeholder="0 - 100" required>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-success px-4">
                            <i class="bi bi-save me-1"></i> SIMPAN NILAI
                        </button>
                    </div>
                </form>
            @else
                <div class="empty-state">
                    <i class="bi bi-person-x"></i>
                    Pilih siswa terlebih dahulu untuk mengisi nilai.
                </div>
            @endif
        </div>
    </div>
@endif
@endsection

@push('scripts')
<script>
    (function () {
        document.querySelectorAll('.nilai-input').forEach(function (input) {
            input.addEventListener('input', function () {
                if (this.value === '') return;
                var value = parseFloat(this.value);
                if (value > 100) this.value = 100;
                if (value < 0) this.value = 0;
                this.classList.toggle('is-invalid', value < 0 || value > 100);
            });
        });
    })();
</script>
@endpush
