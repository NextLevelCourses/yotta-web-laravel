@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    {{-- Breadcrumb --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h5 class="m-0 text-muted">Dashboard / <span class="fw-bold text-success">Monitoring Greenhouse</span></h5>
        <div>
            <a href="{{ route('dashboard') }}" class="btn btn-sm btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    {{-- Card Greenhouse --}}
    <div class="card shadow-sm rounded-3">
        <div class="card-header bg-success text-white">
            <h4 class="card-title mb-0">Monitoring Greenhouse</h4>
        </div>
        <div class="card-body">
            <p class="text-muted">
                Berikut adalah data simulasi monitoring Greenhouse dalam bentuk grafik.
            </p>
            <div id="greenhouse-chart" style="height: 400px;"></div>
        </div>
    </div>
</div>

{{-- ECharts --}}
<script src="https://cdn.jsdelivr.net/npm/echarts@5.5.0/dist/echarts.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        var chartDom = document.getElementById('greenhouse-chart');
        var myChart = echarts.init(chartDom);

        var option = {
            tooltip: { trigger: 'axis' },
            legend: { data: ['Suhu (°C)', 'Kelembaban (%)', 'CO₂ (ppm)'] },
            grid: { left: '3%', right: '4%', bottom: '3%', containLabel: true },
            xAxis: { type: 'category', boundaryGap: false, data: @json($dummyData['labels']) },
            yAxis: { type: 'value' },
            series: [
                {
                    name: 'Suhu (°C)',
                    type: 'line',
                    smooth: true,
                    data: @json($dummyData['temperature']),
                    color: '#f46a6a'
                },
                {
                    name: 'Kelembaban (%)',
                    type: 'line',
                    smooth: true,
                    data: @json($dummyData['humidity']),
                    color: '#34c38f'
                },
                {
                    name: 'CO₂ (ppm)',
                    type: 'line',
                    smooth: true,
                    data: @json($dummyData['co2']),
                    color: '#50a5f1'
                }
            ]
        };

        myChart.setOption(option);
    });
</script>
@endsection
