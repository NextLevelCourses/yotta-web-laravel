@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">
        {{-- Header Page --}}
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h5 class="m-0 text-muted">
                Monitoring / <span class="fw-bold text-primary">Stasiun Cuaca</span>
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
        <livewire:stasiun-cuaca-livewire />

        {{-- Card Grafik --}}
        <div class="card shadow-sm rounded-3 mt-4">
            <div class="card-header bg-primary text-white">
                <h4 class="card-title mb-0 fw-bold">🌤 Grafik Data Stasiun Cuaca</h4>
            </div>
            <div class="card-body">
                <p class="text-muted">Visualisasi semua parameter sensor Stasiun Cuaca secara real-time.</p>
                <div id="weather-chart" wire:ignore style="height: 400px;"></div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- Library ECharts --}}
    <script src="https://cdn.jsdelivr.net/npm/echarts@5/dist/echarts.min.js"></script>

    <script type="text/javascript">
        document.addEventListener('livewire:init', () => {
            console.log('✅ Livewire ECharts Siap');

            let chart;

            Livewire.on('chartDataStasiunCuaca', (payload) => {
                console.log('📡 Data diterima:', payload);
                const chartDom = document.getElementById('weather-chart');
                if (!chartDom) return;

                if (!chart) {
                    chart = echarts.init(chartDom);
                }

                const option = {
                    backgroundColor: "#fff",
                    tooltip: {
                        trigger: "axis",
                        axisPointer: {
                            type: "cross",
                            label: { backgroundColor: "#6a7985" }
                        }
                    },
                    legend: {
                        bottom: 10,
                        icon: "circle",
                        data: [
                            "temperature", "humidity", "co2", "pm10", "pm25", "so2",
                            "no2", "o3", "nh3", "tvoc", "solar_radiation", "rainfall"
                        ]
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
                    series: [
                        { name: "temperature", type: "line", smooth: true, color: "#ff6b6b", data: payload.data.temperature },
                        { name: "humidity", type: "line", smooth: true, color: "#4ecdc4", data: payload.data.humidity },
                        { name: "co2", type: "line", smooth: true, color: "#f9c74f", data: payload.data.co2 },
                        { name: "pm10", type: "line", smooth: true, color: "#d62828", data: payload.data.pm10 },
                        { name: "pm25", type: "line", smooth: true, color: "#f77f00", data: payload.data.pm25 },
                        { name: "so2", type: "line", smooth: true, color: "#8338ec", data: payload.data.so2 },
                        { name: "no2", type: "line", smooth: true, color: "#3a86ff", data: payload.data.no2 },
                        { name: "o3", type: "line", smooth: true, color: "#2a9d8f", data: payload.data.o3 },
                        { name: "nh3", type: "line", smooth: true, color: "#e76f51", data: payload.data.nh3 },
                        { name: "tvoc", type: "line", smooth: true, color: "#577590", data: payload.data.tvoc },
                        { name: "solar_radiation", type: "line", smooth: true, color: "#06d6a0", data: payload.data.solar_radiation },
                        { name: "rainfall", type: "line", smooth: true, color: "#118ab2", data: payload.data.rainfall },
                    ]
                };

                chart.setOption(option);
                chart.resize();
            });
        });

        // === Dropdown Bulan ===
        function getEndOfMonth(year, month) {
            return new Date(year, month + 1, 0);
        }

        function loadMonths() {
            const select = document.getElementById("export-month-select");
            if (!select) return;

            const now = new Date();
            select.innerHTML = "";

            for (let i = 0; i < 12; i++) {
                const d = new Date(now.getFullYear(), now.getMonth() - i, 1);
                const end = getEndOfMonth(d.getFullYear(), d.getMonth());
                const option = document.createElement("option");
                option.value = end.toISOString().split("T")[0];
                option.textContent = d.toLocaleString("default", { month: "long", year: "numeric" });
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

        document.addEventListener("DOMContentLoaded", loadMonths);
    </script>
@endpush
