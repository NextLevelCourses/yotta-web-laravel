@extends('layouts.guest')

@section('content')
<div class="auth-content my-auto">
    <div class="text-center mb-4">
        <h4 class="fw-bold text-success mb-1">Selamat Datang Kembali!</h4>
        <p class="text-muted">Masuk untuk melanjutkan ke Dashboard.</p>
    </div>

    @session('error')
        <div class="alert alert-danger" role="alert">
            {{ $value }}
        </div>
    @endsession

    <form class="mt-4 pt-2" method="POST" action="{{ route('login') }}">
        @csrf
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
            <label class="form-label fw-semibold">Password</label>
            <div class="input-group auth-pass-inputgroup">
                <input type="password" class="form-control"
                    placeholder="Masukkan password" name="password" required>
                <button class="btn btn-light border" type="button" id="password-addon">
                    <i class="mdi mdi-eye-outline text-success"></i>
                </button>
            </div>
        </div>

        <div class="mb-3">
            <button class="btn w-100 text-white fw-semibold"
                style="background: linear-gradient(90deg, #3CB043, #FCD116); border:none;"
                type="submit">
                <i class="mdi mdi-login"></i> Masuk
            </button>
        </div>
    </form>

    <div class="mt-5 text-center">
        <p class="text-muted mb-0">
            Belum punya akun?
            <a href="{{ route('register') }}" class="fw-semibold text-success">Daftar Sekarang</a>
        </p>
    </div>
</div>

{{-- Script toggle password --}}
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const toggleBtn = document.getElementById("password-addon");
        const passwordInput = document.querySelector("input[name='password']");

        toggleBtn.addEventListener("click", function() {
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                this.innerHTML = '<i class="mdi mdi-eye-off-outline text-success"></i>';
            } else {
                passwordInput.type = "password";
                this.innerHTML = '<i class="mdi mdi-eye-outline text-success"></i>';
            }
        });
    });
</script>
@endsection
