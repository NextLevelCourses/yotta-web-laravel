@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">
        {{-- Breadcrumbs Minimalis --}}
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h5 class="m-0 text-muted">Dashboard / <span class="fw-bold text-primary">Soil Test</span></h5>
            <div>
                <a href="{{ route('dashboard') }}" class="btn btn-sm btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
                <a href="{{ route('soil-test.export') }}" class="btn btn-sm btn-success ms-2">
                    <i class="fas fa-file-export me-1"></i> Export Data
                </a>
            </div>
        </div>

        <script src="{{ asset('assets/js/pages/soil-test.js') }}"></script>

        <livewire:soil-test-livewire />
    </div>
@endsection
