@extends('layouts.guest')

@section('content')
    <div class="auth-content my-auto">
        <div class="text-center">
            <h5 class="mb-0">Selamat Datang Kembali!</h5>
            <p class="text-muted mt-2">Masuk untuk melanjutkan ke Dashboard.</p>
        </div>
        @session('error')
            <div class="alert alert-danger" role="alert">
                {{ $value }}
            </div>
        @endsession
        <form class="mt-4 pt-2" method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                    name="email" placeholder="Masukkan email" value="{{ old('email') }}" required>
                @error('email')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="mb-3">
                <div class="d-flex align-items-start">
                    <div class="flex-grow-1">
                        <label class="form-label">Password</label>
                    </div>
                </div>

                <div class="input-group auth-pass-inputgroup">
                    <input type="password" class="form-control" placeholder="Masukkan password" aria-label="Password"
                        name="password" required>
                    <button class="btn btn-light ms-0" type="button" id="password-addon"><i
                            class="mdi mdi-eye-outline"></i></button>
                </div>
            </div>
            <div class="mb-3">
                <button class="btn btn-primary w-100 waves-effect waves-light" type="submit">Log In</button>
            </div>
        </form>

        <div class="mt-5 text-center">
            <p class="text-muted mb-0">Belum punya akun? <a href="{{ route('register') }}" class="text-primary fw-semibold">
                    Daftar Sekarang </a> </p>
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
                    this.innerHTML =
                        '<i class="mdi mdi-eye-off-outline"></i>'; // ubah ikon jadi "mata tertutup"
                } else {
                    passwordInput.type = "password";
                    this.innerHTML = '<i class="mdi mdi-eye-outline"></i>'; // kembali jadi "mata terbuka"
                }
            });
        });
    </script>
@endsection
