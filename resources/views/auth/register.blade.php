@extends('layouts.guest')

@section('content')
<div class="auth-content my-auto">
    <div class="text-center mb-4">
        <h4 class="fw-bold text-success mb-1">Buat Akun Baru</h4>
        <p class="text-muted">Dapatkan akses ke dashboard IoT Yotta Aksara Energy.</p>
    </div>

    <form class="mt-4 pt-2" method="POST" action="{{ route('register') }}">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label fw-semibold">Nama Lengkap</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror"
                   id="name" name="name" placeholder="Masukkan nama lengkap"
                   value="{{ old('name') }}" required>
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="email" class="form-label fw-semibold">Email</label>
            <input type="email" class="form-control @error('email') is-invalid @enderror"
                   id="email" name="email" placeholder="Masukkan email"
                   value="{{ old('email') }}" required>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label fw-semibold">Password</label>
            <div class="input-group auth-pass-inputgroup">
                <input type="password" class="form-control @error('password') is-invalid @enderror"
                       id="password" name="password" placeholder="Masukkan password" required>
                <button class="btn btn-light border" type="button" id="toggle-password">
                    <i class="mdi mdi-eye-outline text-success"></i>
                </button>
                @error('password')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="mb-3">
            <label for="password_confirmation" class="form-label fw-semibold">Konfirmasi Password</label>
            <div class="input-group auth-pass-inputgroup">
                <input type="password" class="form-control" id="password_confirmation"
                       name="password_confirmation" placeholder="Konfirmasi password" required>
                <button class="btn btn-light border" type="button" id="toggle-password-confirm">
                    <i class="mdi mdi-eye-outline text-success"></i>
                </button>
            </div>
        </div>

        <div class="mb-3">
            <button class="btn w-100 text-white fw-semibold"
                style="background: linear-gradient(90deg, #3CB043, #FCD116); border:none;"
                type="submit">
                <i class="mdi mdi-account-plus"></i> Daftar
            </button>
        </div>
    </form>

    <div class="mt-5 text-center">
        <p class="text-muted mb-0">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="fw-semibold text-success">Login</a>
        </p>
    </div>
</div>

{{-- Script toggle password --}}
<script>
    document.addEventListener("DOMContentLoaded", function () {
        function togglePassword(btnId, inputId) {
            const btn = document.getElementById(btnId);
            const input = document.getElementById(inputId);

            btn.addEventListener("click", function () {
                if (input.type === "password") {
                    input.type = "text";
                    this.innerHTML = '<i class="mdi mdi-eye-off-outline text-success"></i>';
                } else {
                    input.type = "password";
                    this.innerHTML = '<i class="mdi mdi-eye-outline text-success"></i>';
                }
            });
        }

        togglePassword("toggle-password", "password");
        togglePassword("toggle-password-confirm", "password_confirmation");
    });
</script>
@endsection
