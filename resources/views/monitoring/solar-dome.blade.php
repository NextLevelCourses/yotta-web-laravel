@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    {{-- Breadcrumbs Minimalis --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h5 class="m-0 text-muted">Monitoring / <span class="fw-bold text-success">Monitoring Greenhouse</span></h5>
        <div>
            <a href="{{ route('dashboard') }}" class="btn btn-sm btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    {{-- Card Monitoring Greenhouse --}}
    <div class="card shadow-sm rounded-3">
        <div class="card-header">
            <h4 class="card-title mb-0">Monitoring Greenhouse</h4>
        </div>
        <div class="card-body">
            <p class="text-muted">
                Berikut adalah simulasi data monitoring Greenhouse dalam bentuk grafik line chart.
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
