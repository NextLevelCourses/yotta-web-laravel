@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">
        {{-- Breadcrumbs Minimalis --}}
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h5 class="m-0 text-muted">Monitoring / <span class="fw-bold text-primary">Soil Management</span></h5>
            <a href="{{ route('dashboard') }}" class="btn btn-sm btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>
@endsection