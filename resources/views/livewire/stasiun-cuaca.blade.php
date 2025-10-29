{{-- Kartu utama --}}
    <div>
        <div class="card shadow-lg rounded-3 border-0">
            <div class="card-header text-white py-4 px-4" style="background: linear-gradient(90deg, #0f766e, #10b981);">
                <h4 class="card-title mb-0 fw-bold text-center text-md-start">
                    🌦️ Stasiun Cuaca — Environmental Monitoring Dashboard
                </h4>
            </div>

            <div class="card-body p-4">
                {{-- ROW 1 --}}
                <div class="row g-4 text-center">
                    <div class="col-md-3 col-6">
                        <div class="p-4 bg-light rounded shadow-sm h-100">
                            <h6 class="text-muted mb-1">🌡 Temperature</h6>
                            <h4 id="temperature" class="fw-bold text-dark mb-0"> °C</h4>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="p-4 bg-light rounded shadow-sm h-100">
                            <h6 class="text-muted mb-1">💧 Humidity</h6>
                            <h4 id="humidity" class="fw-bold text-dark mb-0"> %</h4>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="p-4 bg-light rounded shadow-sm h-100">
                            <h6 class="text-muted mb-1">☀️ Solar Radiation</h6>
                            <h4 id="solar_radiation" class="fw-bold text-dark mb-0"> W/m²</h4>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="p-4 bg-light rounded shadow-sm h-100">
                            <h6 class="text-muted mb-1">🌧 Rainfall</h6>
                            <h4 id="rainfall" class="fw-bold text-dark mb-0"> mm</h4>
                        </div>
                    </div>
                </div>

                {{-- ROW 2 --}}
                <div class="row g-4 text-center mt-3">
                    <div class="col-md-3 col-6">
                        <div class="p-4 bg-light rounded shadow-sm h-100">
                            <h6 class="text-muted mb-1">💨 PM2.5</h6>
                            <h4 id="pm25" class="fw-bold text-dark mb-0"> μg/m³</h4>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="p-4 bg-light rounded shadow-sm h-100">
                            <h6 class="text-muted mb-1">💨 PM10</h6>
                            <h4 id="pm10" class="fw-bold text-dark mb-0"> μg/m³</h4>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="p-4 bg-light rounded shadow-sm h-100">
                            <h6 class="text-muted mb-1">💨 CO₂</h6>
                            <h4 id="co2" class="fw-bold text-dark mb-0"> ppm</h4>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="p-4 bg-light rounded shadow-sm h-100">
                            <h6 class="text-muted mb-1">💨 O₃</h6>
                            <h4 id="o3" class="fw-bold text-dark mb-0"> ppb</h4>
                        </div>
                    </div>
                </div>

                {{-- ROW 3 --}}
                <div class="row g-4 text-center mt-3">
                    <div class="col-md-3 col-6">
                        <div class="p-4 bg-light rounded shadow-sm h-100">
                            <h6 class="text-muted mb-1">💨 SO₂</h6>
                            <h4 id="so2" class="fw-bold text-dark mb-0"> ppm</h4>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="p-4 bg-light rounded shadow-sm h-100">
                            <h6 class="text-muted mb-1">💨 NO₂</h6>
                            <h4 id="no2" class="fw-bold text-dark mb-0"> ppm</h4>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="p-4 bg-light rounded shadow-sm h-100">
                            <h6 class="text-muted mb-1">💨 NH₃</h6>
                            <h4 id="nh3" class="fw-bold text-dark mb-0"> ppm</h4>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="p-4 bg-light rounded shadow-sm h-100">
                            <h6 class="text-muted mb-1">🌿 TVOC</h6>
                            <h4 id="tvoc" class="fw-bold text-dark mb-0"> ppb</h4>
                        </div>
                    </div>
                </div>

                {{-- Informasi Tambahan GPS --}}
                <hr class="my-4">
                <div class="row text-center text-muted small gy-2">
                    <div class="col-md-3 col-6"><strong>📡 Device_ID:</strong> <span id="Device_ID"></span></div>
                    <div class="col-md-3 col-6"><strong>🕒 Local Time:</strong> <span id="gps_local_time"></span></div>
                    <div class="col-md-3 col-6"><strong>📍 Latitude:</strong> <span id="gps_latitude"></span></div>
                    <div class="col-md-3 col-6"><strong>📍 Longitude:</strong> <span id="gps_longitude"></span></div>
                    <div class="col-md-3 col-6"><strong>🏔 Altitude:</strong> <span id="gps_altitude"></span> m</div>
                    <div class="col-md-3 col-6"><strong>🛰 Satellites:</strong> <span id="gps_satellites"></span></div>
                    <div class="col-md-3 col-6"><strong>🎯 HDOP:</strong> <span id="gps_hdop"></span></div>
                    <div class="col-md-3 col-6"><strong>🏎 Speed:</strong> <span id="gps_speed_kmh"></span> km/h</div>
                </div>

                {{-- LOADING --}}
                <div class="text-center mt-4" wire:loading>
                    <div class="spinner-border spinner-border-sm text-success"></div>
                    <span class="text-muted ms-2">Memperbarui data sensor cuaca...</span>
                </div>
            </div>
        </div>
    </div>
</div>
