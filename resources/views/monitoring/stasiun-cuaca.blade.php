@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h5 class="m-0 text-muted">Monitoring / <span class="fw-bold text-primary">Stasiun Cuaca:
                </span> @empty($device->device_id)
                    {{ __('Tidak ada device yang terhubung') }}
                @else
                    {{ $device->device_id }}
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

        {{-- Script khusus halaman ini --}}
        <script src="{{ asset('assets/js/pages/stasiun-cuaca.js') }}"></script>

        {{-- Komponen Livewire --}}
        <livewire:stasiun-cuaca-livewire />

        {{-- Grafik Data Cuaca --}}
        <div class="card shadow-sm rounded-3 mt-4">
            <div class="card-header bg-primary text-white">
                <h4 class="card-title mb-0">Grafik Stasiun Cuaca</h4>
            </div>
            <div class="card-body">
                <p class="text-muted">
                    Berikut adalah data pemantauan cuaca dalam bentuk grafik (suhu, kelembaban, tekanan, curah hujan, kecepatan dan arah angin).
                </p>
                <div id="stasiuncuaca-chart" wire:ignore style="height: 400px;"></div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript">
        document.addEventListener('livewire:init', () => {
            console.log('✅ Livewire siap: StasiunCuacaLivewire');
            let myChart;

            Livewire.on('chartDataStasiunCuaca', (payload) => {
                console.log('📡 Data grafik diterima:', payload);
                const chartDom = document.getElementById('stasiuncuaca-chart');
                if (!chartDom) return;

                if (!myChart) {
                    myChart = echarts.init(chartDom);
                }

                const option = {
                    tooltip: { trigger: 'axis' },
                    legend: {
                        data: [
                            'Suhu (°C)',
                            'Kelembaban (%)',
                            'Tekanan (hPa)',
                            'Curah Hujan (mm)',
                            'Kecepatan Angin (m/s)',
                            'Arah Angin (°)'
                        ]
                    },
                    grid: { left: '3%', right: '4%', bottom: '3%', containLabel: true },
                    xAxis: { type: 'category', boundaryGap: false, data: payload.data.labels },
                    yAxis: { type: 'value' },
                    series: [
                        {
                            name: 'Suhu (°C)',
                            type: 'line',
                            smooth: true,
                            data: payload.data.temperature,
                            color: '#f46a6a'
                        },
                        {
                            name: 'Kelembaban (%)',
                            type: 'line',
                            smooth: true,
                            data: payload.data.humidity,
                            color: '#50a5f1'
                        },
                        {
                            name: 'Tekanan (hPa)',
                            type: 'line',
                            smooth: true,
                            data: payload.data.pressure,
                            color: '#34c38f'
                        },
                        {
                            name: 'Curah Hujan (mm)',
                            type: 'line',
                            smooth: true,
                            data: payload.data.rainfall,
                            color: '#ffcc00'
                        },
                        {
                            name: 'Kecepatan Angin (m/s)',
                            type: 'line',
                            smooth: true,
                            data: payload.data.wind_speed,
                            color: '#9933ff'
                        },
                        {
                            name: 'Arah Angin (°)',
                            type: 'line',
                            smooth: true,
                            data: payload.data.wind_direction,
                            color: '#009999'
                        }
                    ]
                };

                myChart.setOption(option);
                myChart.resize();
            });
        });

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
            const exportUrl = "{{ route('stasiun-cuaca.export', ['date' => 'DATE_SELECT']) }}"
                .replace('DATE_SELECT', selectedValue);
            window.location.href = exportUrl;
        }

        loadMonths();
    </script>
@endpush
