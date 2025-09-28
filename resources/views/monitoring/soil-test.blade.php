@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h5 class="m-0 text-muted">Dashboard / <span class="fw-bold text-primary">Soil Test:
                </span> @empty($data['device']->device_id)
                    {{ __('Tidak ada device yang terhubung') }}
                @else
                    {{ $data['device']->device_id }}
                @endempty
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

        <script src="{{ asset('assets/js/pages/soil-test.js') }}"></script>

        <livewire:soil-test-livewire />
    </div>

    {{-- Card Line Chart --}}
    <div class="card shadow-sm rounded-3">
        <div class="card-header bg-primary text-white">
            <h4 class="card-title mb-0">Soil Test Chart</h4>
        </div>
        <div class="card-body">
            <p class="text-muted">
                Berikut adalah data simulasi monitoring soil dalam bentuk grafik.
            </p>
            <div id="soiltest-chart" style="height: 400px;"></div>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript">
        document.addEventListener("DOMContentLoaded", function() {
            var chartDom = document.getElementById('soiltest-chart');
            var myChart = echarts.init(chartDom);

            var option = {
                tooltip: {
                    trigger: 'axis'
                },
                legend: {
                    data: ['Suhu (°C)', 'Kelembaban (%)', 'EC (µS/cm)', 'pH', 'Nitrogen (mg/kg)',
                        'Fosfor (mg/kg)', 'Kalium (mg/kg)'
                    ]
                },
                grid: {
                    left: '3%',
                    right: '4%',
                    bottom: '3%',
                    containLabel: true
                },
                xAxis: {
                    type: 'category',
                    boundaryGap: false,
                    data: @json($data['labels']),
                    axisLabel: {
                        align: 'right' // label rata kanan
                    }
                },
                yAxis: {
                    type: 'value'
                },
                series: [{
                        name: 'Suhu (°C)',
                        type: 'line',
                        smooth: true,
                        data: @json($data['temperature']),
                        color: '#f46a6a'
                    },
                    {
                        name: 'Kelembaban (%)',
                        type: 'line',
                        smooth: true,
                        data: @json($data['humidity']),
                        color: '#50a5f1'
                    },
                    {
                        name: 'EC (µS/cm)',
                        type: 'line',
                        smooth: true,
                        data: @json($data['ec']),
                        color: '#34c38f'
                    },
                    {
                        name: 'pH',
                        type: 'line',
                        smooth: true,
                        data: @json($data['ph']),
                        color: '#ffcc00'
                    },
                    {
                        name: 'Nitrogen (mg/kg)',
                        type: 'line',
                        smooth: true,
                        data: @json($data['nitrogen']),
                        color: '#9933ff'
                    },
                    {
                        name: 'Fosfor (mg/kg)',
                        type: 'line',
                        smooth: true,
                        data: @json($data['fosfor']),
                        color: '#ff9933'
                    },
                    {
                        name: 'Kalium (mg/kg)',
                        type: 'line',
                        smooth: true,
                        data: @json($data['kalium']),
                        color: '#009999'
                    },
                ]
            };

            myChart.setOption(option);
        });
        //export data
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
