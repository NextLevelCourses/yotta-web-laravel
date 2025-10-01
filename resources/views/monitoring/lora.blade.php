@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">

    <div class="d-flex align-items-center justify-content-between mb-4">
        <h5 class="m-0 text-muted">Monitoring / 
            <span class="fw-bold text-success">LoRaWAN</span>
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

    <script src="{{ asset('assets/js/pages/soil-test.js') }}"></script>

        <livewire:lora-monitoring />

@endsection

@section('scripts')
<script>
async function fetchLoRaData() {
    try {
        const response = await fetch("{{ url('/api/loras/latest') }}");
        const result = await response.json();

        if (result.status === "success") {
            const data = result.data;
            document.getElementById("air_temperature").innerText = data.air_temperature + " °C";
            document.getElementById("air_humidity").innerText = data.air_humidity + " %";
            document.getElementById("soil_pH").innerText = data.soil_pH;
            document.getElementById("soil_temperature").innerText = data.soil_temperature + " °C";
            document.getElementById("soil_conductivity").innerText = data.soil_conductivity + " mS/cm";
            document.getElementById("soil_humidity").innerText = data.soil_humidity + " %";
            document.getElementById("nitrogen").innerText = data.nitrogen;
            document.getElementById("phosphorus").innerText = data.phosphorus;
            document.getElementById("potassium").innerText = data.potassium;
            document.getElementById("updated_at").innerText = data.created_at;
        } else {
            console.log(result.message);
        }
    } catch (error) {
        console.error("Error fetching data:", error);
    }
}

// ambil data tiap 5 detik
setInterval(fetchLoRaData, 5000);
fetchLoRaData();
</script>
@endsection
