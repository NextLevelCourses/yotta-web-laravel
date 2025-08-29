@extends('layouts.guest')

@section('content')
<div class="auth-content my-auto">
    <div class="text-center">
        <h5 class="mb-0">Buat Akun Baru</h5>
        <p class="text-muted mt-2">Dapatkan akun gratis Anda sekarang.</p>
    </div>
    <form class="mt-4 pt-2" method="POST" action="{{ route('register') }}">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Nama</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="Masukkan nama lengkap" value="{{ old('name') }}" required>
             @error('name')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" placeholder="Masukkan email" value="{{ old('email') }}" required>
             @error('email')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="Masukkan password" required>
             @error('password')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Konfirmasi password" required>
        </div>
        
        <div class="mb-3">
            <button class="btn btn-primary w-100 waves-effect waves-light" type="submit">Daftar</button>
        </div>
    </form>

    <div class="mt-5 text-center">
        <p class="text-muted mb-0">Sudah punya akun? <a href="{{ route('login') }}"
                class="text-primary fw-semibold"> Login </a> </p>
    </div>
</div>
@endsection
