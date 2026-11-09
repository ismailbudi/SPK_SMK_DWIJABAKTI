@extends('layouts.guest')

@section('title', 'Login')

@section('content')
<div class="login-page">
    <div class="login-card">
        <div class="login-illustration">
            <div class="emblem">
                <i class="bi bi-mortarboard-fill"></i>
            </div>
            <h4>SISTEM PENDUKUNG KEPUTUSAN</h4>
            <p>Penentuan Siswa Berprestasi<br>Metode ROC - MOORA</p>
            <p class="mt-3 mb-0" style="font-size:12px;">SMK Dwija Bhakti</p>
        </div>

        <div class="login-form">
            <h2>Login Sistem</h2>
            <p class="sub">Masukkan akun Anda untuk mengelola penilaian siswa berprestasi.</p>

            @if ($errors->any())
                <div class="alert alert-danger py-2">
                    <i class="bi bi-exclamation-circle-fill me-1"></i>
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" id="loginForm">
                @csrf

                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control @error('username') is-invalid @enderror"
                           id="username" name="username" value="{{ old('username') }}"
                           placeholder="Masukkan username" required autofocus autocomplete="username">
                </div>

                <div class="mb-4">
                    <label for="password" class="form-label">Password</label>
                    <div class="position-relative">
                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                               id="password" name="password" placeholder="Masukkan password"
                               required autocomplete="current-password" style="padding-right:38px;">
                        <button type="button" class="password-toggle" id="togglePassword" aria-label="Tampilkan password">
                            <i class="bi bi-eye" id="toggleIcon"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 mb-2" id="loginButton">
                    LOGIN
                </button>
                <button type="reset" class="btn btn-outline-secondary w-100">
                    RESET
                </button>
            </form>

            <p class="text-center mt-4 mb-0" style="font-size:11.5px;color:var(--color-muted);">
                &copy; {{ date('Y') }} SMK Dwija Bhakti &middot; SPK ROC-MOORA
            </p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    (function () {
        const toggle = document.getElementById('togglePassword');
        const input = document.getElementById('password');
        const icon = document.getElementById('toggleIcon');

        toggle.addEventListener('click', function () {
            const show = input.type === 'password';
            input.type = show ? 'text' : 'password';
            icon.className = show ? 'bi bi-eye-slash' : 'bi bi-eye';
        });

        document.getElementById('loginForm').addEventListener('submit', function () {
            const button = document.getElementById('loginButton');
            button.disabled = true;
            button.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memproses...';
        });
    })();
</script>
@endpush
