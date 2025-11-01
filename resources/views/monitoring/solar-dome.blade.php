@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">

        <div class="d-flex align-items-center justify-content-between mb-4">
            <h5 class="m-0 text-muted">Monitoring / <span class="fw-bold text-primary">Solar Dome
            </h5>
            <div class="d-flex align-items-center gap-2">
                @if (Auth::user()->isAdmin())
                    <select class="form-select form-select-sm" name="export-month-select" id="export-month-select"
                        aria-label="Pilih Bulan untuk Ekspor"></select>
                    <button onclick="exportData()" class="btn btn-sm btn-success d-flex align-items-center">
                        <i class="fas fa-file-export me-1"></i> Export
                    </button>
                @endif
                <a href="{{ route('dashboard') }}" class="btn btn-sm btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i>
                </a>
            </div>
        </div>
        {{-- Header --}}
        <div class="card shadow-lg border-0 rounded-3 mb-4">
            <div class="card-header bg-primary text-white rounded-top-3">
                <h3 class="m-0 fw-bold text-white text-start">Solar Dome Controller</h3>
                <p class="mb-0 small text-light text-start">Cloud Control System</p>
            </div>

            <div class="card-body">

                {{-- Status Sistem --}}
                <h5 class="fw-bold text-primary mb-3">
                    <i class="fas fa-microchip me-2"></i>Status Sistem
                </h5>

                <div class="row g-4 text-center">
                    <div class="col-md-3 col-6">
                        <div class="p-3 bg-light rounded shadow-sm">
                            <h6 class="text-muted">SUHU</h6>
                            <h3 id="temperature" class="fw-bold text-dark">27.2</h3>
                            <span class="small text-secondary">°C</span>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="p-3 bg-light rounded shadow-sm">
                            <h6 class="text-muted">KELEMBAPAN</h6>
                            <h3 id="humidity" class="fw-bold text-dark">75.6</h3>
                            <span class="small text-secondary">%</span>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="p-3 bg-light rounded shadow-sm">
                            <h6 class="text-muted">MODE</h6>
                            <h4 id="mode" class="fw-bold text-primary">
                                <i class="fas fa-circle text-info me-1"></i>AUTO
                            </h4>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="p-3 bg-light rounded shadow-sm">
                            <h6 class="text-muted">KIPAS</h6>
                            <h4 id="fan_status" class="fw-bold text-success">
                                <i class="fas fa-circle text-success me-1"></i>NYALA
                            </h4>
                        </div>
                    </div>
                </div>

                <div class="text-end mt-3">
                    <small class="text-muted">Terakhir update: <span id="last_update">0 detik lalu</span></small>
                </div>

                {{-- Kontrol Manual --}}
                <hr class="my-4">
                <h5 class="fw-bold text-primary mb-3">
                    <i class="fas fa-sliders-h me-2"></i>Kontrol Manual
                </h5>

                <div class="d-flex flex-wrap justify-content-center gap-2 mb-3">
                    <button id="modeAutoBtn" class="btn btn-info text-white fw-bold px-4">
                        <i class="fas fa-robot me-1"></i> Mode AUTO
                    </button>
                    <button id="manualOnBtn" class="btn btn-success fw-bold px-4">
                        <i class="fas fa-play me-1"></i> Manual ON
                    </button>
                    <button id="manualOffBtn" class="btn btn-danger fw-bold px-4">
                        <i class="fas fa-stop me-1"></i> Manual OFF
                    </button>
                </div>

                <div class="d-flex flex-wrap justify-content-center align-items-center gap-2">
                    <input type="number" class="form-control w-auto" id="targetHumidity"
                        placeholder="Target Kelembapan (%)">
                    <button id="setTargetBtn" class="btn btn-warning fw-bold">
                        <i class="fas fa-bullseye me-1"></i> Set Target
                    </button>
                </div>

            </div>
        </div>

    </div>
@endsection

@push('scripts')
    <script type="text/javascript">
        // === Export Data ===
        function getEndOfMonth(year, month) {
            return new Date(year, month + 1, 0);
        }

        function loadMonths() {
            const select = document.getElementById("export-month-select");
            const now = new Date();

            for (let i = 0; i < 12; i++) {
                const d = new Date(now.getFullYear(), now.getMonth() - i, 1);
                const end = getEndOfMonth(d.getFullYear(), d.getMonth());
                const option = document.createElement("option");

                option.value = end.toISOString().split("T")[0];
                option.textContent = d.toLocaleString("default", {
                    month: "long",
                    year: "numeric"
                });

                if (i === 0) option.selected = true;
                select.appendChild(option);
            }
        }

        function exportData() {
            const select = document.getElementById("export-month-select");
            const selectedValue = select.value;
            const exportUrl = "{{ route('solar-dome.export', ['date' => 'DATE_SELECT']) }}"
                .replace('DATE_SELECT', selectedValue);
            window.location.href = exportUrl;
        }

        loadMonths();
    </script>
@endpush
