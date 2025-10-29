@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">

    {{-- Header Section --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h5 class="m-0 text-muted">
            Monitoring /
            <span class="fw-bold text-success">Solar Dome</span>
        </h5>

        <div class="d-flex align-items-center gap-2">
            @if (Auth::check() && method_exists(Auth::user(), 'isAdmin') && Auth::user()->isAdmin())
                <select class="form-select form-select-sm" id="export-month-select" aria-label="Pilih Bulan untuk Ekspor">
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

            <a href="{{ route('dashboard') }}" class="btn btn-sm btn-secondary d-flex align-items-center">
                <i class="fas fa-arrow-left me-1"></i> Back
            </a>
        </div>
    </div>

    {{-- Card: Solar Dome Sensor Data --}}
    <div class="card shadow-lg border-0 rounded-3 mb-4">
        <div class="card-header bg-success text-white rounded-top-3">
            <h4 class="card-title mb-0 fw-bold text-center text-md-start">
                ☀️ Solar Dome Sensor Data (Live)
            </h4>
        </div>
        <div class="card-body p-4">
            <div id="solar-dome-data" class="row g-4 text-center">
                <div class="col-md-3">
                    <div class="p-3 bg-light rounded shadow-sm">
                        <h6 class="text-muted">🌡 Dome Temp</h6>
                        <h4 id="dome_temperature" class="fw-bold text-dark">-- °C</h4>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="p-3 bg-light rounded shadow-sm">
                        <h6 class="text-muted">💧 Dome Humidity</h6>
                        <h4 id="dome_humidity" class="fw-bold text-dark">-- %</h4>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="p-3 bg-light rounded shadow-sm">
                        <h6 class="text-muted">☀️ Light Intensity</h6>
                        <h4 id="light_intensity" class="fw-bold text-dark">-- lux</h4>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="p-3 bg-light rounded shadow-sm">
                        <h6 class="text-muted">💦 Water Temp</h6>
                        <h4 id="water_temperature" class="fw-bold text-dark">-- °C</h4>
                    </div>
                </div>
            </div>

            <div class="row g-4 text-center mt-3">
                <div class="col-md-6">
                    <div class="p-3 bg-light rounded shadow-sm">
                        <h6 class="text-muted">⚡ Panel Voltage</h6>
                        <h4 id="panel_voltage" class="fw-bold text-dark">-- V</h4>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="p-3 bg-light rounded shadow-sm">
                        <h6 class="text-muted">🔋 Power Output</h6>
                        <h4 id="power_output" class="fw-bold text-dark">-- W</h4>
                    </div>
                </div>
            </div>

            <div class="mt-4 text-center">
                <small class="text-muted">
                    ⏱ Last Updated: <span id="updated_at">--</span>
                </small>
            </div>
        </div>
    </div>

    {{-- Card: Solar Dome Chart --}}
    <div class="card shadow-sm rounded-3 mt-4">
        <div class="card-header bg-primary text-white">
            <h4 class="card-title mb-0 fw-bold">
                📈 Solar Dome Temperature & Humidity Chart
            </h4>
        </div>
        <div class="card-body">
            <p class="text-muted text-center mb-3">
                Simulasi data Solar Dome dalam bentuk grafik garis (line chart).
            </p>
            <div id="solar-chart" class="e-charts" style="height: 400px;"></div>
        </div>
    </div>

</div>

{{-- Scripts --}}
<script src="https://cdn.jsdelivr.net/npm/echarts@5.5.0/dist/echarts.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        var chartDom = document.getElementById('solar-chart');
        var myChart = echarts.init(chartDom);

        var option = {
            tooltip: { trigger: 'axis' },
            legend: { data: ['Dome Temp (°C)', 'Humidity (%)'] },
            grid: { left: '3%', right: '4%', bottom: '3%', containLabel: true },
            xAxis: {
                type: 'category',
                boundaryGap: false,
                data: ['08:00', '10:00', '12:00', '14:00', '16:00', '18:00']
            },
            yAxis: { type: 'value' },
            series: [
                {
                    name: 'Dome Temp (°C)',
                    type: 'line',
                    smooth: true,
                    data: [26, 28, 32, 34, 31, 27],
                    color: '#28a745'
                },
                {
                    name: 'Humidity (%)',
                    type: 'line',
                    smooth: true,
                    data: [80, 78, 74, 70, 76, 82],
                    color: '#17a2b8'
                }
            ]
        };
        myChart.setOption(option);
    });
</script>
@endsection
