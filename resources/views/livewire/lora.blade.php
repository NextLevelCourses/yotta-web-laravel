<div wire:poll.3s="fetchLora">
    <div class="card shadow-lg rounded-3 border-0">
        <div class="card-header bg-success text-white rounded-top-3">
            <h4 class="card-title mb-0 fw-bold text-center text-md-start">
                📡 LoRaWAN Sensor Data
            </h4>
        </div>
        <div class="card-body p-4">

            {{-- Row pertama --}}
            <div class="row g-4 text-center">
                <div class="col-md-3">
                    <div class="p-3 bg-light rounded shadow-sm">
                        <h6 class="text-muted">🌡 Air Temperature</h6>
                        <h4 class="fw-bold text-dark">
                            {{ $air_temperature }} °C
                        </h4>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="p-3 bg-light rounded shadow-sm">
                        <h6 class="text-muted">💧 Air Humidity</h6>
                        <h4 class="fw-bold text-dark">
                            {{ $air_humidity }} %
                        </h4>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="p-3 bg-light rounded shadow-sm">
                        <h6 class="text-muted">🌱 Soil pH</h6>
                        <h4 class="fw-bold text-dark">
                            {{ $soil_pH }}
                        </h4>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="p-3 bg-light rounded shadow-sm">
                        <h6 class="text-muted">🌡 Soil Temp</h6>
                        <h4 class="fw-bold text-dark">
                            {{ $soil_temperature }} °C
                        </h4>
                    </div>
                </div>
            </div>

            {{-- Row kedua --}}
            <div class="row g-4 text-center mt-3">
                <div class="col-md-3">
                    <div class="p-3 bg-light rounded shadow-sm">
                        <h6 class="text-muted">⚡ Soil Conductivity</h6>
                        <h4 class="fw-bold text-dark">
                            {{ $soil_conductivity }} mS/cm
                        </h4>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="p-3 bg-light rounded shadow-sm">
                        <h6 class="text-muted">💦 Soil Humidity</h6>
                        <h4 class="fw-bold text-dark">
                            {{ $soil_humidity }} %
                        </h4>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="p-3 bg-light rounded shadow-sm">
                        <h6 class="text-muted">🟢 Nitrogen</h6>
                        <h4 class="fw-bold text-dark">
                            {{ $nitrogen }}
                        </h4>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="p-3 bg-light rounded shadow-sm">
                        <h6 class="text-muted">🟡 Phosphorus</h6>
                        <h4 class="fw-bold text-dark">
                            {{ $phosphorus }}
                        </h4>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="p-3 bg-light rounded shadow-sm">
                        <h6 class="text-muted">🔴 Potassium</h6>
                        <h4 class="fw-bold text-dark">
                            {{ $potassium }}
                        </h4>
                    </div>
                </div>
            </div>

            {{-- Last Updated --}}
            <div class="mt-4 text-center">
                <small class="text-muted">
                    ⏱ Last Updated: {{ $created_at }}
                </small>
            </div>

            <div class="text-center mt-4" wire:loading>
                <div class="spinner-border spinner-border-sm text-primary"></div>
                <span class="text-muted ms-2">Memperbarui data dari sensor...</span>
            </div>
        </div>
    </div>
</div>
