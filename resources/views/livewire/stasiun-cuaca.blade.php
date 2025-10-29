{{-- Kartu utama --}}
    <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
        <div class="card-header text-white py-4 px-4"
             style="background: linear-gradient(90deg, #0f766e, #10b981);">
            <h4 class="card-title mb-0 fw-bold text-center text-md-start">
                🌦️ Stasiun Cuaca — Environmental Monitoring Dashboard
            </h4>
        </div>

        <div class="card-body bg-light p-4">

            {{-- Parameter Grid --}}
            <div id="weather-data" class="row g-4 text-center">
                @php
                    $cards = [
                        ['Temperature', '°C', '🌡', 'success', 'temperature'],
                        ['Humidity', '%', '💧', 'primary', 'humidity'],
                        ['Solar Radiation', 'W/m²', '☀️', 'warning', 'solar_radiation'],
                        ['Rainfall', 'mm', '🌧', 'info', 'rainfall'],
                        ['PM2.5', 'μg/m³', '💨', 'secondary', 'pm25'],
                        ['PM10', 'μg/m³', '💨', 'dark', 'pm10'],
                        ['CO₂', 'ppm', '💨', 'success', 'co2'],
                        ['O₃', 'ppb', '💨', 'danger', 'o3'],
                        ['SO₂', 'ppm', '💨', 'warning', 'so2'],
                        ['NO₂', 'ppm', '💨', 'purple', 'no2'],
                        ['NH₃', 'ppm', '💨', 'pink', 'nh3'],
                        ['TVOC', 'ppb', '🌿', 'teal', 'tvoc']
                    ];
                @endphp

                @foreach ($cards as $item)
                    <div class="col-md-3 col-sm-6">
                        <div class="sensor-card p-4 bg-white rounded-4 shadow-sm h-100 border-start border-4 border-{{ $item[3] }}">
                            <h6 class="text-muted mb-1">{{ $item[2] }} {{ $item[0] }}</h6>
                            <h4 id="{{ $item[4] }}" class="fw-bold text-dark">-- {{ $item[1] }}</h4>
                        </div>
                    </div>
                @endforeach
            </div>

            <hr class="my-4">

            {{-- Informasi Tambahan GPS dan Device --}}
            <div class="row text-center text-muted small gy-2">
                <div class="col-md-3 col-6"><strong>📡 Device_ID:</strong> <span id="Device_ID">--</span></div>
                <div class="col-md-3 col-6"><strong>🕒 Local Time:</strong> <span id="gps_local_time">--</span></div>
                <div class="col-md-3 col-6"><strong>📍 Latitude:</strong> <span id="gps_latitude">--</span></div>
                <div class="col-md-3 col-6"><strong>📍 Longitude:</strong> <span id="gps_longitude">--</span></div>
                <div class="col-md-3 col-6"><strong>🏔 Altitude:</strong> <span id="gps_altitude">--</span> m</div>
                <div class="col-md-3 col-6"><strong>🛰 Satellites:</strong> <span id="gps_satellites">--</span></div>
                <div class="col-md-3 col-6"><strong>🎯 HDOP:</strong> <span id="gps_hdop">--</span></div>
                <div class="col-md-3 col-6"><strong>🏎 Speed:</strong> <span id="gps_speed_kmh">--</span> km/h</div>
            </div>

            {{-- Tombol Kontrol (Admin Only) --}}
            @if (Auth::check() && Auth::user()->role === 'admin')
                <hr class="my-4">
                <div class="text-center">
                    <button id="btnOn" class="btn btn-success btn-sm px-3 shadow-sm mx-1 rounded-3">
                        <i class="fas fa-power-off me-1"></i> Nyalakan Fan
                    </button>
                    <button id="btnOff" class="btn btn-danger btn-sm px-3 shadow-sm mx-1 rounded-3">
                        <i class="fas fa-ban me-1"></i> Matikan Fan
                    </button>
                </div>
            @endif

        </div>
    </div>