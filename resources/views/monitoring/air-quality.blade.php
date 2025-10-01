@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">

    <div class="d-flex align-items-center justify-content-between mb-4">
        <h5 class="m-0 text-muted">Monitoring / 
            <span class="fw-bold text-success">Air Quality</span>
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
                Air Quality Monitoring
        </div>
        <div class="card-body p-4">

            <div id="lora-data" class="row g-4 text-center">
                <div class="col-md-3">
                    <div class="p-3 bg-light rounded shadow-sm">
                        <h6 class="text-muted">🌡 Air Temperature</h6>
                        <h4 id="air_temperature" class="fw-bold text-dark">-- °C</h4>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="p-3 bg-light rounded shadow-sm">
                        <h6 class="text-muted">💧 Air Humidity</h6>
                        <h4 id="air_humidity" class="fw-bold text-dark">-- %</h4>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="p-3 bg-light rounded shadow-sm">
                        <h6 class="text-muted">🌱 Soil pH</h6>
                        <h4 id="soil_pH" class="fw-bold text-dark">--</h4>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="p-3 bg-light rounded shadow-sm">
                        <h6 class="text-muted">🌡 Soil Temp</h6>
                        <h4 id="soil_temperature" class="fw-bold text-dark">-- °C</h4>
                    </div>
                </div>
            </div>

            <div class="row g-4 text-center mt-3">
                <div class="col-md-3">
                    <div class="p-3 bg-light rounded shadow-sm">
                        <h6 class="text-muted">⚡ Soil Conductivity</h6>
                        <h4 id="soil_conductivity" class="fw-bold text-dark">-- mS/cm</h4>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="p-3 bg-light rounded shadow-sm">
                        <h6 class="text-muted">💦 Soil Humidity</h6>
                        <h4 id="soil_humidity" class="fw-bold text-dark">-- %</h4>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="p-3 bg-light rounded shadow-sm">
                        <h6 class="text-muted">🟢 Nitrogen</h6>
                        <h4 id="nitrogen" class="fw-bold text-dark">--</h4>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="p-3 bg-light rounded shadow-sm">
                        <h6 class="text-muted">🟡 Phosphorus</h6>
                        <h4 id="phosphorus" class="fw-bold text-dark">--</h4>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="p-3 bg-light rounded shadow-sm">
                        <h6 class="text-muted">🔴 Potassium</h6>
                        <h4 id="potassium" class="fw-bold text-dark">--</h4>
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
