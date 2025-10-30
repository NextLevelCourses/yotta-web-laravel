<div wire:poll.3s="fetchData">
    <div class="card shadow-lg rounded-3 border-0">
        <div class="card-header bg-primary text-white rounded-top-3">
            <h4 class="card-title mb-0 fw-bold text-center text-md-start">
                Weather Station Monitoring
            </h4>
        </div>

        <div class="card-body p-4">

            @php
                $circumference = 2 * pi() * 50;
                $sensors = [
                    [
                        'label' => '🌡️ Suhu Udara (°C)',
                        'value' => $temperature,
                        'max' => 60,
                        'color' => '#ff4d4d',
                        'unit' => '°C',
                    ],
                    [
                        'label' => '💧 Kelembaban (%)',
                        'value' => $humidity,
                        'max' => 100,
                        'color' => '#3399ff',
                        'unit' => '%',
                    ],
                    [
                        'label' => '🌦️ Curah Hujan (mm)',
                        'value' => $rainfall,
                        'max' => 500,
                        'color' => '#00cc99',
                        'unit' => 'mm',
                    ],
                    [
                        'label' => '☀️ Radiasi Matahari (W/m²)',
                        'value' => $solar_radiation,
                        'max' => 1500,
                        'color' => '#ffcc00',
                        'unit' => 'W/m²',
                    ],
                    [
                        'label' => '💨 CO₂ (ppm)',
                        'value' => $co2,
                        'max' => 2000,
                        'color' => '#ff6600',
                        'unit' => 'ppm',
                    ],
                    [
                        'label' => '🧪 NH₃ (ppm)',
                        'value' => $nh3,
                        'max' => 50,
                        'color' => '#9933ff',
                        'unit' => 'ppm',
                    ],
                    [
                        'label' => '🧪 NO₂ (ppm)',
                        'value' => $no2,
                        'max' => 50,
                        'color' => '#ff33cc',
                        'unit' => 'ppm',
                    ],
                    [
                        'label' => '🧪 O₃ (ppm)',
                        'value' => $o3,
                        'max' => 50,
                        'color' => '#33ccff',
                        'unit' => 'ppm',
                    ],
                    [
                        'label' => '🌫️ PM10 (µg/m³)',
                        'value' => $pm10,
                        'max' => 500,
                        'color' => '#999999',
                        'unit' => 'µg/m³',
                    ],
                    [
                        'label' => '🌫️ PM2.5 (µg/m³)',
                        'value' => $pm25,
                        'max' => 500,
                        'color' => '#666666',
                        'unit' => 'µg/m³',
                    ],
                    [
                        'label' => '🧪 SO₂ (ppm)',
                        'value' => $so2,
                        'max' => 50,
                        'color' => '#cc0000',
                        'unit' => 'ppm',
                    ],
                    [
                        'label' => '🧪 TVOC (ppb)',
                        'value' => $tvoc,
                        'max' => 1000,
                        'color' => '#00b3b3',
                        'unit' => 'ppb',
                    ],
                ];
            @endphp

            {{-- Grid Sensor --}}
            <div class="row g-3 g-md-4 text-center">
                @foreach ($sensors as $s)
                    <div class="col-6 col-md-3 col-lg-2">
                        <div class="bg-light rounded-3 p-3 shadow-sm h-100">
                            @php
                                $val = is_numeric($s['value']) ? min($s['value'], $s['max']) : 0;
                                $dash = ($val / $s['max']) * $circumference;
                            @endphp
                            <svg width="120" height="120" viewBox="0 0 120 120" class="mx-auto"
                                style="transform: rotate(-125deg);">
                                <circle cx="60" cy="60" r="50" fill="transparent" stroke="#e5e7eb"
                                    stroke-width="10" />
                                <circle cx="60" cy="60" r="50" fill="transparent"
                                    stroke="{{ $s['color'] }}" stroke-width="10"
                                    stroke-dasharray="{{ $dash }} {{ $circumference }}" stroke-linecap="round">
                                    <animate attributeName="stroke-dasharray" from="0 {{ $circumference }}"
                                        to="{{ $dash }} {{ $circumference }}" dur="1s" fill="freeze" />
                                </circle>
                                <text x="60" y="60" fill="{{ $s['color'] }}" font-size="16" font-weight="bold"
                                    text-anchor="middle" alignment-baseline="middle" transform="rotate(125, 60, 60)">
                                    {{ is_numeric($s['value']) ? round($s['value'], 1) . $s['unit'] : $s['value'] }}
                                </text>
                            </svg>
                            <div class="mt-2 small fw-semibold">{{ $s['label'] }}</div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Informasi Tambahan --}}
            <div class="text-center mt-4">
                <div class="fw-semibold text-muted">
                    <span class="me-3">📟 Device ID: <strong>{{ $device_id }}</strong></span>
                    <span class="me-3">📅 Tanggal: <strong>{{ $tanggal }}</strong></span>
                    <span>🕓 Jam: <strong>{{ $jam }}</strong></span>
                </div>
            </div>

            {{-- Loading Indicator --}}
            <div class="text-center mt-4" wire:loading>
                <div class="spinner-border spinner-border-sm text-primary"></div>
                <span class="text-muted ms-2">Memperbarui data dari sensor cuaca...</span>
            </div>

        </div>
    </div>
</div>
