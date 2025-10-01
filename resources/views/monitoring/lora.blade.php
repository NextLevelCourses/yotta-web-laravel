@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">

        <div class="d-flex align-items-center justify-content-between mb-4">
            <h5 class="m-0 text-muted">Monitoring /
                <span class="fw-bold text-success">LoRaWAN</span>
            </h5>
            <div class="d-flex align-items-center gap-2">
                @if (Auth::user()->isAdmin())
                    <select class="form-select form-select-sm" name="export-month-select" id="export-month-select"
                        aria-label="Pilih Bulan untuk Ekspor">
                        <option value="" selected disabled>Pilih Bulan</option>
                        @foreach (range(1, 12) as $month)
                            <option value="{{ $month }}">
                                {{ \Carbon\Carbon::create()->month($month)->translatedFormat('F') }}
                            </option>
                        @endforeach
                    </select>

                    <button onclick="exportData()" class="btn btn-sm btn-success d-flex align-items-center">
                        <i class="fas fa-file-export me-1"></i> Export
                    </button>
                @endif

                <a href="{{ route('dashboard') }}" class="btn btn-sm btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i>
                </a>
            </div>

        </div>

        <script src="{{ asset('assets/js/pages/soil-test.js') }}"></script>

        <livewire:lora-livewire />
    </div>
@endsection

@section('scripts')
    <script type="text-javascript"></script>
@endsection
