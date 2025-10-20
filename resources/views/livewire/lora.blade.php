<div wire:poll.3s="fetchLora">
    <div class="card shadow-lg rounded-3 border-0">
        <div class="card-header bg-success text-white rounded-top-3">
            <h4 class="card-title mb-0 fw-bold text-center text-md-start">
                🌐 LoRaWAN Sensor Monitoring
            </h4>
        </div>

        <div class="card-body p-4">
            {{-- ROW 1 --}}
            <div class="row g-4 text-center">
                <div class="col-md-3 col-6">
                    <div class="p-4 bg-light rounded shadow-sm h-100">
                        <h6 class="text-muted mb-1">🌡 Air Temperature</h6>
                        <h4 class="fw-bold text-dark mb-0">{{ $air_temperature }} °C</h4>
                    </div>
                </div>

                <div class="col-md-3 col-6">
                    <div class="p-4 bg-light rounded shadow-sm h-100">
                        <h6 class="text-muted mb-1">💧 Air Humidity</h6>
                        <h4 class="fw-bold text-dark mb-0">{{ $air_humidity }} %</h4>
                    </div>
                </div>

                <div class="col-md-3 col-6">
                    <div class="p-4 bg-light rounded shadow-sm h-100">
                        <h6 class="text-muted mb-1">🌱 Soil pH</h6>
                        <h4 class="fw-bold text-dark mb-0">{{ $soil_pH }}</h4>
                    </div>
                </div>

                <div class="col-md-3 col-6">
                    <div class="p-4 bg-light rounded shadow-sm h-100">
                        <h6 class="text-muted mb-1">🌡 Soil Temperature</h6>
                        <h4 class="fw-bold text-dark mb-0">{{ $soil_temperature }} °C</h4>
                    </div>
                </div>
            </div>

            {{-- ROW 2 --}}
            <div class="row g-4 text-center mt-3">
                <div class="col-md-3 col-6">
                    <div class="p-4 bg-light rounded shadow-sm h-100">
                        <h6 class="text-muted mb-1">🌞 PAR (Photosynthetic)</h6>
                        <h4 class="fw-bold text-dark mb-0">{{ $par_value }} µmol/m²·s</h4>
                    </div>
                </div>

                <div class="col-md-3 col-6">
                    <div class="p-4 bg-light rounded shadow-sm h-100">
                        <h6 class="text-muted mb-1">⚡ Soil Conductivity</h6>
                        <h4 class="fw-bold text-dark mb-0">{{ $soil_conductivity }} mS/cm</h4>
                    </div>
                </div>

                <div class="col-md-3 col-6">
                    <div class="p-4 bg-light rounded shadow-sm h-100">
                        <h6 class="text-muted mb-1">💦 Soil Humidity</h6>
                        <h4 class="fw-bold text-dark mb-0">{{ $soil_humidity }} %</h4>
                    </div>
                </div>

                <div class="col-md-3 col-6">
                    <div class="p-4 bg-light rounded shadow-sm h-100">
                        <h6 class="text-muted mb-1">🧪 Nutrients (N–P–K)</h6>
                        <h5 class="fw-bold text-dark mb-0">
                            N: {{ $nitrogen }} |
                            P: {{ $phosphorus }} |
                            K: {{ $potassium }}
                        </h5>
                    </div>
                </div>
            </div>

            {{-- DEVICE INFO --}}
            <div class="mt-4 text-center">
                <small class="text-muted">
                    ⏱ Last Updated: {{ $created_at }}
                </small>
            </div>

            {{-- LOADING --}}
            <div class="text-center mt-4" wire:loading>
                <div class="spinner-border spinner-border-sm text-success"></div>
                <span class="text-muted ms-2">Memperbarui data dari sensor...</span>
            </div>
        </div>
    </div>
</div>
