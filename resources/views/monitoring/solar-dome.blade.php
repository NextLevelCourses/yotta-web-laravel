@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">

    <div class="d-flex align-items-center justify-content-between mb-4">
        <h5 class="m-0 text-muted">Monitoring / 
            <span class="fw-bold text-success">Solar Dome</span>
        </h5>
        <div class="d-flex align-items-center gap-2">
    @if (Auth::user()->isAdmin())
        <select class="form-select form-select-sm" name="export-month-select" id="export-month-select"
            aria-label="Pilih Bulan untuk Ekspor">
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

    <a href="{{ route('dashboard') }}" class="btn btn-sm btn-secondary">
        <i class="fas fa-arrow-left me-1"></i>
    </a>
</div>
</div>

    {{-- Card Monitoring --}}
    <div class="card shadow-lg rounded-3 border-0">
        <div class="card-header bg-success text-white rounded-top-3">
            <h4 class="card-title mb-0 fw-bold text-center text-md-start">
                ☀️ Solar Dome Sensor Data (Latest)
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
</div>

    {{-- Card Solar Dome --}}
    <div class="card shadow-sm rounded-3">
        <div class="card-header">
            <h4 class="card-title mb-0">Solar Dome</h4>
        </div>
        <div class="card-body">
            <p class="text-muted">
                Berikut adalah simulasi data Solar Dome dalam bentuk grafik line chart.
            </p>
            <div id="greenhouse-chart" 
                 data-colors='["#556ee6", "#50a5f1"]' 
                 class="e-charts" 
                 style="height: 400px;">
            </div>
        </div>
    </div>
</div>

{{-- Script ECharts --}}
<script src="https://cdn.jsdelivr.net/npm/echarts@5.5.0/dist/echarts.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        var chartDom = document.getElementById('greenhouse-chart');
        var myChart = echarts.init(chartDom);

        var option = {
            tooltip: { trigger: 'axis' },
            legend: { data: ['Suhu Greenhouse (°C)', 'Kelembaban (%)'] },
            grid: { left: '3%', right: '4%', bottom: '3%', containLabel: true },
            xAxis: {
                type: 'category',
                boundaryGap: false,
                data: ['08:00', '10:00', '12:00', '14:00', '16:00', '18:00']
            },
            yAxis: { type: 'value' },
            series: [
                {
                    name: 'Suhu Greenhouse (°C)',
                    type: 'line',
                    smooth: true,
                    data: [26, 28, 32, 34, 31, 27],
                    color: '#556ee6'
                },
                {
                    name: 'Kelembaban (%)',
                    type: 'line',
                    smooth: true,
                    data: [80, 78, 74, 70, 76, 82],
                    color: '#50a5f1'
                }
            ]
        };

        myChart.setOption(option);
    });
</script>
@endsection
