<div wire:poll.3s="fetchLora">
    <div class="card shadow-lg rounded-3 border-0">
        <div class="card-header bg-success text-white rounded-top-3">
            <h4 class="card-title mb-0 fw-bold text-center text-md-start">
                📡 LoRaWAN Sensor Data : {{ $device_id }}
            </h4>
        </div>

        <div class="card-body p-4">

            {{-- ROW 1 --}}
            <div class="row g-4 text-center">
                <div class="col-md-3 col-6">
                    <div class="p-3 bg-light rounded shadow-sm">
                        <h6 class="text-muted">🌡 Air Temperature</h6>
                        <h4 class="fw-bold text-dark">{{ $air_temperature }} °C</h4>
                    </div>
                </div>

                <div class="col-md-3 col-6">
                    <div class="p-3 bg-light rounded shadow-sm">
                        <h6 class="text-muted">💧 Air Humidity</h6>
                        <h4 class="fw-bold text-dark">{{ $air_humidity }} %</h4>
                    </div>
                </div>

                <div class="col-md-3 col-6">
                    <div class="p-3 bg-light rounded shadow-sm">
                        <h6 class="text-muted">🌱 Soil pH</h6>
                        <h4 class="fw-bold text-dark">{{ $soil_pH }}</h4>
                    </div>
                </div>

                <div class="col-md-3 col-6">
                    <div class="p-3 bg-light rounded shadow-sm">
                        <h6 class="text-muted">🌡 Soil Temperature</h6>
                        <h4 class="fw-bold text-dark">{{ $soil_temperature }} °C</h4>
                    </div>
                </div>
            </div>

            {{-- ROW 2 --}}
            <div class="row g-4 text-center mt-3">
                <div class="col-md-3 col-6">
                    <div class="p-3 bg-light rounded shadow-sm">
                        <h6 class="text-muted">🌞 PAR (Photosynthetic)</h6>
                        <h4 class="fw-bold text-dark"> {{ $par_value }} µmol/m²·s</h4>
                    </div>
                </div>

                <div class="col-md-3 col-6">
                    <div class="p-3 bg-light rounded shadow-sm">
                        <h6 class="text-muted">⚡ Soil Conductivity</h6>
                        <h4 class="fw-bold text-dark">{{ $soil_conductivity }} mS/cm</h4>
                    </div>
                </div>

                <div class="col-md-3 col-6">
                    <div class="p-3 bg-light rounded shadow-sm">
                        <h6 class="text-muted">💦 Soil Humidity</h6>
                        <h4 class="fw-bold text-dark">{{ $soil_humidity }} %</h4>
                    </div>
                </div>

                <div class="col-md-3 col-6">
                    <div class="p-3 bg-light rounded shadow-sm">
                        <h6 class="text-muted">🧪 Nutrients (N–P–K)</h6>
                        <h5 class="fw-bold text-dark">
                            N: {{ $nitrogen }} |
                            P: {{ $phosphorus }} |
                            K: {{ $potassium }}
                        </h5>
                    </div>
                </div>
            </div>

            {{-- Chart --}}
            <div class="mt-5">
                <h5 class="fw-bold text-center mb-3 text-primary">📈 Soil Parameter Chart</h5>
                <div wire:ignore>
                    <canvas id="soilChart" height="120"></canvas>
                </div>
            </div>

            {{-- Last Updated --}}
            <div class="mt-4 text-center">
                <small class="text-muted">
                    ⏱ Last Updated: {{ $created_at }}
                </small>
            </div>

            {{-- Loading Spinner --}}
            <div class="text-center mt-4" wire:loading>
                <div class="spinner-border spinner-border-sm text-primary"></div>
                <span class="text-muted ms-2">Memperbarui data dari sensor...</span>
            </div>
        </div>
    </div>

    {{-- Script Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('livewire:load', function() {
            const ctx = document.getElementById('soilChart').getContext('2d');

            const soilChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: [],
                    datasets: [{
                            label: 'Soil Temp (°C)',
                            data: [],
                            borderColor: '#ff6384',
                            backgroundColor: 'rgba(255,99,132,0.1)',
                            tension: 0.3,
                            fill: true,
                        },
                        {
                            label: 'Soil Humidity (%)',
                            data: [],
                            borderColor: '#36a2eb',
                            backgroundColor: 'rgba(54,162,235,0.1)',
                            tension: 0.3,
                            fill: true,
                        },
                        {
                            label: 'Soil pH',
                            data: [],
                            borderColor: '#4bc0c0',
                            backgroundColor: 'rgba(75,192,192,0.1)',
                            tension: 0.3,
                            fill: true,
                        },
                    ]
                },
                options: {
                    responsive: true,
                    interaction: {
                        mode: 'index',
                        intersect: false
                    },
                    plugins: {
                        legend: {
                            position: 'bottom'
                        },
                        title: {
                            display: true,
                            text: 'Live Soil Data (3s refresh)'
                        },
                    },
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'Waktu'
                            }
                        },
                        y: {
                            title: {
                                display: true,
                                text: 'Nilai'
                            },
                            beginAtZero: true
                        }
                    }
                }
            });

            // Menerima event dari Livewire
            Livewire.on('updateChart', (data) => {
                soilChart.data.labels = data.labels;
                soilChart.data.datasets[0].data = data.soil_temperature;
                soilChart.data.datasets[1].data = data.soil_humidity;
                soilChart.data.datasets[2].data = data.soil_pH;
                soilChart.update();
            });
        });
    </script>
</div>
