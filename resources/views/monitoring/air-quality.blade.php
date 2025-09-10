@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">
        {{-- Breadcrumbs Minimalis --}}
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h5 class="m-0 text-muted">Dashboard / <span class="fw-bold text-primary">Soil Test</span></h5>
            <a href="{{ route('dashboard') }}" class="btn btn-sm btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
        </div>
        
        <script src="{{ asset('assets/js/pages/soil-test.js') }}"></script>

        <livewire:air-quality-livewire />
    </div>
@endsection