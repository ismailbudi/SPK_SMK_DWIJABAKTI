@extends('layouts.app')

@section('title', 'Data Siswa')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Data Siswa</h1>
        <p class="page-subtitle">Kelola data siswa calon penerima predikat berprestasi.</p>
    </div>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahSiswa">
        <i class="bi bi-plus-lg me-1"></i> Tambah Data
    </button>
</div>

<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-end mb-3">
            <form method="GET" action="{{ route('siswa.index') }}" class="search-box w-100">
                <i class="bi bi-search"></i>
                <input type="text" name="q" value="{{ $q }}" class="form-control" placeholder="Cari siswa...">
            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th class="text-center" style="width:60px;">No</th>
                        <th>NIS</th>
                        <th>Nama</th>
                        <th>Kelas</th>
                        <th class="text-center">Jenis Kelamin</th>
                        <th class="text-center" style="width:100px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($siswas as $siswa)
                        <tr>
                            <td class="text-center">{{ ($siswas->currentPage() - 1) * $siswas->perPage() + $loop->iteration }}</td>
                            <td>{{ $siswa->nis }}</td>
                            <td>{{ $siswa->nama }}</td>
                            <td>{{ $siswa->kelas }}</td>
                            <td class="text-center">{{ $siswa->jenis_kelamin }}</td>
                            <td>
                                <div class="btn-group-actions">
                                    <button type="button" class="btn btn-warning btn-icon" title="Ubah"
                                            data-bs-toggle="modal" data-bs-target="#modalEditSiswa{{ $siswa->id_siswa }}">
                                        <i class="bi bi-pencil-fill"></i>
                                    </button>
                                    <button type="button" class="btn btn-danger btn-icon" title="Hapus"
                                            data-bs-toggle="modal" data-bs-target="#modalHapusSiswa{{ $siswa->id_siswa }}">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="empty-state">
                                    <i class="bi bi-people"></i>
                                    {{ $q !== '' ? 'Siswa tidak ditemukan.' : 'Belum ada data siswa.' }}
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $siswas->links() }}
        </div>
    </div>
</div>

{{-- Modal tambah --}}
<div class="modal fade" id="modalTambahSiswa" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('siswa.store') }}" method="POST" class="modal-content">
            @csrf
            <input type="hidden" name="form_mode" value="create">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Data Siswa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label" for="add_nis">NIS</label>
                    <input type="text" class="form-control" id="add_nis" name="nis" value="{{ old('form_mode') === 'create' ? old('nis') : '' }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="add_nama">Nama</label>
                    <input type="text" class="form-control" id="add_nama" name="nama" value="{{ old('form_mode') === 'create' ? old('nama') : '' }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="add_kelas">Kelas</label>
                    <input type="text" class="form-control" id="add_kelas" name="kelas" value="{{ old('form_mode') === 'create' ? old('kelas') : '' }}" required>
                </div>
                <div class="mb-1">
                    <label class="form-label d-block">Jenis Kelamin</label>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="jenis_kelamin" id="add_l" value="L"
                               {{ old('form_mode') === 'create' && old('jenis_kelamin') === 'L' ? 'checked' : '' }} required>
                        <label class="form-check-label" for="add_l">Laki-laki (L)</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="jenis_kelamin" id="add_p" value="P"
                               {{ old('form_mode') === 'create' && old('jenis_kelamin') === 'P' ? 'checked' : '' }}>
                        <label class="form-check-label" for="add_p">Perempuan (P)</label>
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

@foreach ($siswas as $siswa)
    <div class="modal fade" id="modalEditSiswa{{ $siswa->id_siswa }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form action="{{ route('siswa.update', $siswa) }}" method="POST" class="modal-content">
                @csrf
                @method('PUT')
                <input type="hidden" name="form_mode" value="edit">
                <input type="hidden" name="edit_id" value="{{ $siswa->id_siswa }}">
                <div class="modal-header">
                    <h5 class="modal-title">Ubah Data Siswa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">NIS</label>
                        <input type="text" class="form-control" name="nis"
                               value="{{ old('form_mode') === 'edit' && (int) old('edit_id') === $siswa->id_siswa ? old('nis') : $siswa->nis }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama</label>
                        <input type="text" class="form-control" name="nama"
                               value="{{ old('form_mode') === 'edit' && (int) old('edit_id') === $siswa->id_siswa ? old('nama') : $siswa->nama }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kelas</label>
                        <input type="text" class="form-control" name="kelas"
                               value="{{ old('form_mode') === 'edit' && (int) old('edit_id') === $siswa->id_siswa ? old('kelas') : $siswa->kelas }}" required>
                    </div>
                    <div class="mb-1">
                        <label class="form-label d-block">Jenis Kelamin</label>
                        @php
                            $jk = old('form_mode') === 'edit' && (int) old('edit_id') === $siswa->id_siswa ? old('jenis_kelamin') : $siswa->jenis_kelamin;
                        @endphp
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="jenis_kelamin" id="edit_l_{{ $siswa->id_siswa }}" value="L" {{ $jk === 'L' ? 'checked' : '' }} required>
                            <label class="form-check-label" for="edit_l_{{ $siswa->id_siswa }}">Laki-laki (L)</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="jenis_kelamin" id="edit_p_{{ $siswa->id_siswa }}" value="P" {{ $jk === 'P' ? 'checked' : '' }}>
                            <label class="form-check-label" for="edit_p_{{ $siswa->id_siswa }}">Perempuan (P)</label>
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

    <div class="modal fade" id="modalHapusSiswa{{ $siswa->id_siswa }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form action="{{ route('siswa.destroy', $siswa) }}" method="POST" class="modal-content">
                @csrf
                @method('DELETE')
                <div class="modal-header">
                    <h5 class="modal-title">Hapus Data Siswa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    Yakin ingin menghapus data <strong>{{ $siswa->nama }}</strong> ({{ $siswa->nis }})?
                    Seluruh nilai dan hasil perhitungan siswa ini juga akan dihapus.
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
            new bootstrap.Modal(document.getElementById('modalTambahSiswa')).show();
        @endif
        @if ($errors->any() && old('form_mode') === 'edit' && old('edit_id'))
            var el = document.getElementById('modalEditSiswa{{ (int) old('edit_id') }}');
            if (el) { new bootstrap.Modal(el).show(); }
        @endif
    })();
</script>
@endpush
