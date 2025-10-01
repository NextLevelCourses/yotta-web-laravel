<div wire:poll.3s="fetchlora">
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
                            {{ $data['air_temperature'] ?? '--' }} °C
                        </h4>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="p-3 bg-light rounded shadow-sm">
                        <h6 class="text-muted">💧 Air Humidity</h6>
                        <h4 class="fw-bold text-dark">
                            {{ $data['air_humidity'] ?? '--' }} %
                        </h4>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="p-3 bg-light rounded shadow-sm">
                        <h6 class="text-muted">🌱 Soil pH</h6>
                        <h4 class="fw-bold text-dark">
                            {{ $data['soil_pH'] ?? '--' }}
                        </h4>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="p-3 bg-light rounded shadow-sm">
                        <h6 class="text-muted">🌡 Soil Temp</h6>
                        <h4 class="fw-bold text-dark">
                            {{ $data['soil_temperature'] ?? '--' }} °C
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
                            {{ $data['soil_conductivity'] ?? '--' }} mS/cm
                        </h4>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="p-3 bg-light rounded shadow-sm">
                        <h6 class="text-muted">💦 Soil Humidity</h6>
                        <h4 class="fw-bold text-dark">
                            {{ $data['soil_humidity'] ?? '--' }} %
                        </h4>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="p-3 bg-light rounded shadow-sm">
                        <h6 class="text-muted">🟢 Nitrogen</h6>
                        <h4 class="fw-bold text-dark">
                            {{ $data['nitrogen'] ?? '--' }}
                        </h4>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="p-3 bg-light rounded shadow-sm">
                        <h6 class="text-muted">🟡 Phosphorus</h6>
                        <h4 class="fw-bold text-dark">
                            {{ $data['phosphorus'] ?? '--' }}
                        </h4>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="p-3 bg-light rounded shadow-sm">
                        <h6 class="text-muted">🔴 Potassium</h6>
                        <h4 class="fw-bold text-dark">
                            {{ $data['potassium'] ?? '--' }}
                        </h4>
                    </div>
                </div>
            </div>

            {{-- Last Updated --}}
            <div class="mt-4 text-center">
                <small class="text-muted">
                    ⏱ Last Updated: {{ $data['created_at'] ?? '--' }}
                </small>
            </div>
        </div>
    </div>
</div>
