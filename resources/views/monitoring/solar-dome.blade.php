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

        {{-- Komponen Livewire --}}
        <livewire:solar-dome-livewire />

        {{-- Kontrol Manual --}}
        <div class="card shadow-lg border-0 rounded-3 mb-4">
            <div class="card-body">
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
