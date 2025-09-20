@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">
        {{-- Breadcrumbs Minimalis --}}
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h5 class="m-0 text-muted">Monitoring / <span class="fw-bold text-primary">Soil Test</span></h5>
            <div>
                <a href="{{ route('dashboard') }}" class="btn btn-sm btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
                <a href="{{ route('soil-test.export') }}" class="btn btn-sm btn-success ms-2">
                    <i class="fas fa-file-export me-1"></i> Export Data
                </a>
            </div>
        </div>
        <div class="card shadow-lg rounded-3 border-0 mt-5">
            <div class="card-body p-4">
        <script src="{{ asset('assets/js/pages/soil-test.js') }}"></script>
        <livewire:soil-test-livewire />
    </div>
    

    {{-- Card Line Chart --}}
        <div class="card mt-4 shadow-sm rounded-3">
            <div class="card-header">
                <h4 class="card-title mb-0">Line Chart</h4>
            </div>
            <div class="card-body">
                <div id="line-chart" 
                     data-colors='["#2ab57d", "#ccc"]' 
                     class="e-charts" 
                     style="height: 350px;">
                </div>
            </div>
        </div>
    </div>

@endsection
