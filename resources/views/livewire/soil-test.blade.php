<div wire:poll.3s="fetchData">
    <div class="card shadow-lg rounded-3 border-0">
        <div class="card-header bg-primary text-white rounded-top-3">
            <h4 class="card-title mb-0 fw-bold">🌍 Soil Quality Monitoring</h4>
        </div>
        <div class="card-body p-4">

            @php
                $circumference = 2 * pi() * 50; // keliling lingkaran untuk radius 50
                $sensors = [
                    [
                        'label' => '🌡️ Suhu (°C)',
                        'value' => $temperature,
                        'max' => 50,
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
                        'label' => '⚡ EC (µS/cm)',
                        'value' => $ec,
                        'max' => 2000,
                        'color' => '#33cc33',
                        'unit' => 'µS/cm',
                    ],
                    ['label' => '🧪 pH', 'value' => $ph, 'max' => 14, 'color' => '#ffcc00', 'unit' => ''],
                ];
            @endphp

            {{-- Sensor Utama --}}
            <div class="row g-4 text-center">
                @foreach ($sensors as $s)
                    <div class="col-md-3 col-6">
                        <div class="bg-light rounded-3 p-3 shadow-sm">
                            @php
                                $val = is_numeric($s['value']) ? min($s['value'], $s['max']) : 0;
                                $dash = ($val / $s['max']) * $circumference;
                            @endphp
                            <svg width="120" height="120" viewBox="0 0 120 120" style="transform: rotate(-125deg);">
                                <circle cx="60" cy="60" r="50" fill="transparent" stroke="#e5e7eb"
                                    stroke-width="10" />
                                <circle cx="60" cy="60" r="50" fill="transparent"
                                    stroke="{{ $s['color'] }}" stroke-width="10"
                                    stroke-dasharray="{{ $dash }} {{ $circumference }}" stroke-linecap="round">
                                    <animate attributeName="stroke-dasharray" from="0 {{ $circumference }}"
                                        to="{{ $dash }} {{ $circumference }}" dur="1s" fill="freeze" />
                                </circle>
                                <text x="60" y="60" fill="{{ $s['color'] }}" font-size="18" font-weight="bold"
                                    text-anchor="middle" alignment-baseline="middle" transform="rotate(125, 60, 60)">
                                    {{ is_numeric($s['value']) ? $s['value'] . $s['unit'] : $s['value'] }}
                                </text>
                            </svg>
                            <div class="mt-2 fw-semibold">{{ $s['label'] }}</div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Nutrisi NPK --}}
            <h5 class="mt-5 mb-3 fw-bold text-primary">🌱 Nutrient Levels (NPK)</h5>
            <div class="row g-4 text-center">
                @php
                    $npks = [
                        [
                            'label' => '🌱 Nitrogen (mg/kg)',
                            'value' => $nitrogen,
                            'max' => 500,
                            'color' => '#9933ff',
                            'unit' => 'mg/kg',
                        ],
                        [
                            'label' => '🌾 Fosfor (mg/kg)',
                            'value' => $fosfor,
                            'max' => 500,
                            'color' => '#ff9933',
                            'unit' => 'mg/kg',
                        ],
                        [
                            'label' => '🥔 Kalium (mg/kg)',
                            'value' => $kalium,
                            'max' => 500,
                            'color' => '#009999',
                            'unit' => 'mg/kg',
                        ],
                    ];
                @endphp

                @foreach ($npks as $n)
                    <div class="col-md-4 col-12">
                        <div class="bg-light rounded-3 p-3 shadow-sm">
                            @php
                                $val = is_numeric($n['value']) ? min($n['value'], $n['max']) : 0;
                                $dash = ($val / $n['max']) * $circumference;
                            @endphp
                            <svg width="120" height="120" viewBox="0 0 120 120"
                                style="transform: rotate(-125deg);">
                                <circle cx="60" cy="60" r="50" fill="transparent" stroke="#e5e7eb"
                                    stroke-width="10" />
                                <circle cx="60" cy="60" r="50" fill="transparent"
                                    stroke="{{ $n['color'] }}" stroke-width="10"
                                    stroke-dasharray="{{ $dash }} {{ $circumference }}"
                                    stroke-linecap="round">
                                    <animate attributeName="stroke-dasharray" from="0 {{ $circumference }}"
                                        to="{{ $dash }} {{ $circumference }}" dur="1s" fill="freeze" />
                                </circle>
                                <text x="60" y="60" fill="{{ $n['color'] }}" font-size="18" font-weight="bold"
                                    text-anchor="middle" alignment-baseline="middle" transform="rotate(125, 60, 60)">
                                    {{ is_numeric($n['value']) ? $n['value'] . $n['unit'] : $n['value'] }}
                                </text>
                            </svg>
                            <div class="mt-2 fw-semibold">{{ $n['label'] }}</div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Loading --}}
            <div class="text-center mt-4" wire:loading>
                <div class="spinner-border spinner-border-sm text-primary"></div>
                <span class="text-muted ms-2">Memperbarui data dari sensor...</span>
            </div>
        </div>
    </div>
</div>
