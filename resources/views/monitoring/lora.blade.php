@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">

        <div class="d-flex align-items-center justify-content-between mb-4">
            <h5 class="m-0 text-muted">Monitoring /
                <span class="fw-bold text-success">LoRaWAN</span>
            </h5>
            <div class="d-flex align-items-center gap-2">
                @if (Auth::user()->isAdmin())
                    <select class="form-select form-select-sm" name="export-month" id="export-month"
                        aria-label="Pilih Bulan untuk Ekspor">
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

@push('scripts')
    <script type="text/javascript">
        //export data
        function getEndOfMonth(year, month) {
            return new Date(year, month + 1, 0); // last day of given month
        }

        function loadMonths() {
            const select = document.getElementById("export-month");
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

                if (i === 0) {
                    option.selected = true; // default current month
                }
                select.appendChild(option);
            }
        }

        function exportData() {
            const select = document.getElementById("export-month");
            const selectedValue = select.value;
            const exportUrl = "{{ route('lora-test.export', ['date' => 'DATE_SELECT']) }}".replace(
                'DATE_SELECT', selectedValue);
            window.location.href = exportUrl
        }

        // Load months on page load
        loadMonths();
    </script>

    
@endpush
