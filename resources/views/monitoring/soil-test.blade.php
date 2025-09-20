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
            </div>
        </div>

        <script src="{{ asset('assets/js/pages/soil-test.js') }}"></script>

        <livewire:soil-test-livewire />
        <div class="d-flex justify-content-end">
            <div class="card col-lg-6 col-md-8">
                <div class="card-body">
                    <div class="row align-items-end">
                        <div class="col-md-8">
                            <div class="mb-3 mb-md-0">
                                <label for="export-month-select" class="form-label font-size-13 text-muted">Pilih
                                    Bulan</label>
                                <select class="form-control" name="export-month-select" id="export-month-select">
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 d-flex align-items-end justify-content-end">
                            {{-- <a href="{{ route('soil-test.export') }}" class="btn btn-success w-100 w-md-auto">
                                <i class="fas fa-file-export me-1"></i> Export Data
                            </a> --}}
                            <button onclick="exportData()" class="btn btn-success w-100 w-md-auto"><i
                                    class="fas fa-file-export me-1"></i>Export Data</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript">
        function getEndOfMonth(year, month) {
            return new Date(year, month + 1, 0); // last day of given month
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

                if (i === 0) {
                    option.selected = true; // default current month
                }

                select.appendChild(option);
            }
        }

        function exportData() {
            const select = document.getElementById("export-month-select");
            const selectedValue = select.value;
            const exportUrl = "{{ route('soil-test.export', ['date' => 'DATE_SELECT']) }}".replace(
                'DATE_SELECT', selectedValue);
            window.location.href = exportUrl
        }

        // Load months on page load
        loadMonths();
    </script>
@endpush
