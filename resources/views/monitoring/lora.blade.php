@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">
        {{-- Header Page --}}
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h5 class="m-0 text-muted">
                Monitoring / <span class="fw-bold text-primary">LoRaWAN</span>
            </h5>

            <div class="d-flex align-items-center gap-2">
                @if (Auth::user()->isAdmin())
                    <select class="form-select form-select-sm" id="export-month-select" aria-label="Pilih Bulan"></select>
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
        <livewire:lora-livewire />

        {{-- Card Grafik --}}
        <div class="card shadow-sm rounded-3 mt-4">
            <div class="card-header bg-primary text-white">
                <h4 class="card-title mb-0 fw-bold">📊 Grafik Sensor LoRaWAN</h4>
            </div>
            <div class="card-body">
                <p class="text-muted">Visualisasi data sensor LoRaWAN secara real-time</p>
                <div id="lorawan-chart" wire:ignore style="height: 400px;"></div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- Library ECharts --}}
    <script src="https://cdn.jsdelivr.net/npm/echarts@5/dist/echarts.min.js"></script>

    <script type="text/javascript">
        document.addEventListener('livewire:init', () => {
            console.log('✅ Livewire: ECharts siap digunakan', window.Livewire);
            let loraChart;

            Livewire.on('chartDataLoraWan', (payload) => {
                console.log('📡 Data diterima:', payload);
                const chartDom = document.getElementById('lorawan-chart');
                if (!chartDom) return;

                // Reuse chart instance agar tidak crash setiap 3 detik
                if (!loraChart) {
                    loraChart = echarts.init(chartDom);
                }
                // --- KONFIGURASI CHART ---
                var option = {
                    backgroundColor: "#fff",
                    tooltip: {
                        trigger: "axis",
                        axisPointer: {
                            type: "cross",
                            label: {
                                backgroundColor: "#6a7985"
                            }
                        }
                    },
                    legend: {
                        data: [
                            "Suhu Udara", "Kelembaban Udara", "pH Tanah",
                            "Suhu Tanah", "PAR", "Konduktivitas",
                            "Kelembaban Tanah", "Nitrogen", "Fosfor", "Kalium"
                        ],
                        bottom: 10,
                        icon: "circle"
                    },
                    grid: {
                        left: "3%",
                        right: "4%",
                        bottom: "15%",
                        containLabel: true
                    },
                    xAxis: {
                        type: "category",
                        boundaryGap: false,
                        data: payload.data.labels
                    },
                    yAxis: {
                        type: "value"
                    },
                    series: [{
                            name: "Suhu Udara",
                            type: "line",
                            smooth: true,
                            data: payload.data.air_temperature,
                            color: "#ff6b6b"
                        },
                        {
                            name: "Kelembaban Udara",
                            type: "line",
                            smooth: true,
                            data: payload.data.air_humidity,
                            color: "#4ecdc4"
                        },
                        {
                            name: "pH Tanah",
                            type: "line",
                            smooth: true,
                            data: payload.data.soil_pH,
                            color: "#f9c74f"
                        },
                        {
                            name: "Suhu Tanah",
                            type: "line",
                            smooth: true,
                            data: payload.data.soil_temperature,
                            color: "#d62828"
                        },
                        {
                            name: "PAR",
                            type: "line",
                            smooth: true,
                            data: payload.data.par_value,
                            color: "#f77f00"
                        },
                        {
                            name: "Konduktivitas",
                            type: "line",
                            smooth: true,
                            data: payload.data.soil_conductivity,
                            color: "#8338ec"
                        },
                        {
                            name: "Kelembaban Tanah",
                            type: "line",
                            smooth: true,
                            data: payload.data.soil_humidity,
                            color: "#3a86ff"
                        },
                        {
                            name: "Nitrogen",
                            type: "line",
                            smooth: true,
                            data: payload.data.nitrogen,
                            color: "#2a9d8f"
                        },
                        {
                            name: "Fosfor",
                            type: "line",
                            smooth: true,
                            data: payload.data.phosphorus,
                            color: "#e76f51"
                        },
                        {
                            name: "Kalium",
                            type: "line",
                            smooth: true,
                            data: payload.data.potassium,
                            color: "#577590"
                        },
                    ]
                };

                loraChart.setOption(option);
                loraChart.resize();
            })

        });

        // Dropdown bulan export
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
            const exportUrl = "{{ route('lora-test.export', ['date' => 'DATE_SELECT']) }}".replace(
                'DATE_SELECT', selectedValue);
            window.location.href = exportUrl
        }

        loadMonths();
    </script>
@endpush
