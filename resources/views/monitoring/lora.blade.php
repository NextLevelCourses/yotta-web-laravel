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
            <p class="text-muted">Visualisasi data sensor LoRaWAN secara real-time. (dummy)</p>
            <div id="lorawan-chart" wire:ignore style="height: 400px;"></div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- Library ECharts --}}
<script src="https://cdn.jsdelivr.net/npm/echarts@5/dist/echarts.min.js"></script>

<script type="text/javascript">
document.addEventListener('DOMContentLoaded', () => {
    console.log('✅ ECharts siap digunakan');
    const chartDom = document.getElementById('lorawan-chart');
    const loraWanChart = echarts.init(chartDom);

    // --- DATA DUMMY ---
    const dummyData = {
        labels: ['08:00', '09:00', '10:00', '11:00', '12:00', '13:00'],
        air_temperature: [28, 29, 30, 31, 32, 33],
        air_humidity: [70, 68, 65, 63, 61, 60],
        soil_pH: [6.8, 6.9, 7.0, 6.9, 7.1, 7.0],
        soil_temperature: [27, 28, 28, 29, 30, 31],
        par_value: [100, 200, 400, 600, 500, 300],
        soil_conductivity: [1.2, 1.4, 1.6, 1.5, 1.3, 1.2],
        soil_humidity: [40, 42, 43, 45, 44, 42],
        nitrogen: [20, 22, 21, 23, 22, 20],
        phosphorus: [10, 12, 11, 12, 13, 11],
        potassium: [15, 16, 15, 17, 16, 15],
    };

    // --- KONFIGURASI CHART ---
    const option = {
        backgroundColor: "#fff",
        tooltip: {
            trigger: "axis",
            axisPointer: { type: "cross", label: { backgroundColor: "#6a7985" } }
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
        grid: { left: "3%", right: "4%", bottom: "15%", containLabel: true },
        xAxis: { type: "category", boundaryGap: false, data: dummyData.labels },
        yAxis: { type: "value" },
        series: [
            { name: "Suhu Udara", type: "line", smooth: true, data: dummyData.air_temperature, color: "#ff6b6b" },
            { name: "Kelembaban Udara", type: "line", smooth: true, data: dummyData.air_humidity, color: "#4ecdc4" },
            { name: "pH Tanah", type: "line", smooth: true, data: dummyData.soil_pH, color: "#f9c74f" },
            { name: "Suhu Tanah", type: "line", smooth: true, data: dummyData.soil_temperature, color: "#d62828" },
            { name: "PAR", type: "line", smooth: true, data: dummyData.par_value, color: "#f77f00" },
            { name: "Konduktivitas", type: "line", smooth: true, data: dummyData.soil_conductivity, color: "#8338ec" },
            { name: "Kelembaban Tanah", type: "line", smooth: true, data: dummyData.soil_humidity, color: "#3a86ff" },
            { name: "Nitrogen", type: "line", smooth: true, data: dummyData.nitrogen, color: "#2a9d8f" },
            { name: "Fosfor", type: "line", smooth: true, data: dummyData.phosphorus, color: "#e76f51" },
            { name: "Kalium", type: "line", smooth: true, data: dummyData.potassium, color: "#577590" },
        ]
    };

    // Tampilkan data dummy
    loraWanChart.setOption(option);
    window.addEventListener('resize', () => loraWanChart.resize());

    // --- Livewire listener (jika nanti ada data real-time) ---
    document.addEventListener('livewire:init', () => {
        Livewire.on('chartDataLoraWan', (payload) => {
            console.log('📡 Data grafik LoRaWAN diterima:', payload);
            option.xAxis.data = payload.data.labels || [];
            option.series.forEach((s, i) => {
                s.data = Object.values(payload.data)[i + 1] || [];
            });
            loraWanChart.setOption(option);
        });
    });
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
        option.textContent = d.toLocaleString("default", { month: "long", year: "numeric" });
        if (i === 0) option.selected = true;
        select.appendChild(option);
    }
}
loadMonths();
</script>
@endpush
