<div wire:poll.3s="fetchData">
    <div class="card shadow-lg rounded-3 border-0">
        <div class="card-header bg-primary text-white rounded-top-3">
            <h4 class="card-title mb-0 fw-bold text-center text-md-start">
                🌱 Soil Quality Monitoring
            </h4>
        </div>

        <div class="card-body p-4">
            {{-- ROW 1 --}}
            <div class="row g-4 text-center">
                <div class="col-md-3 col-6">
                    <div class="p-4 bg-light rounded shadow-sm h-100">
                        <h6 class="text-muted mb-1">🌡 Soil Temperature</h6>
                        <h4 class="fw-bold text-dark mb-0">{{ $temperature }} °C</h4>
                    </div>
                </div>

                <div class="col-md-3 col-6">
                    <div class="p-4 bg-light rounded shadow-sm h-100">
                        <h6 class="text-muted mb-1">💧 Soil Moisture</h6>
                        <h4 class="fw-bold text-dark mb-0">{{ $humidity }} %</h4>
                    </div>
                </div>

                <div class="col-md-3 col-6">
                    <div class="p-4 bg-light rounded shadow-sm h-100">
                        <h6 class="text-muted mb-1">⚡ Electrical Conductivity (EC)</h6>
                        <h4 class="fw-bold text-dark mb-0">{{ $ec }} µS/cm</h4>
                    </div>
                </div>

                <div class="col-md-3 col-6">
                    <div class="p-4 bg-light rounded shadow-sm h-100">
                        <h6 class="text-muted mb-1">🧪 Soil pH</h6>
                        <h4 class="fw-bold text-dark mb-0">{{ $ph }}</h4>
                    </div>
                </div>
            </div>

            {{-- ROW 2 --}}
            <div class="row g-4 text-center mt-3">
                <div class="col-md-3 col-6">
                    <div class="p-4 bg-light rounded shadow-sm h-100">
                        <h6 class="text-muted mb-1">🌾 Nitrogen (N)</h6>
                        <h4 class="fw-bold text-dark mb-0">{{ $nitrogen }} mg/kg</h4>
                    </div>
                </div>

                <div class="col-md-3 col-6">
                    <div class="p-4 bg-light rounded shadow-sm h-100">
                        <h6 class="text-muted mb-1">🌾 Phosphorus (P)</h6>
                        <h4 class="fw-bold text-dark mb-0">{{ $fosfor }} mg/kg</h4>
                    </div>
                </div>

                <div class="col-md-3 col-6">
                    <div class="p-4 bg-light rounded shadow-sm h-100">
                        <h6 class="text-muted mb-1">🌾 Potassium (K)</h6>
                        <h4 class="fw-bold text-dark mb-0">{{ $kalium }} mg/kg</h4>
                    </div>
                </div>

                <div class="col-md-3 col-6">
                    <div class="p-4 bg-light rounded shadow-sm h-100">
                        <h6 class="text-muted mb-1">🧪 N–P–K Balance</h6>
                        <h5 class="fw-bold text-dark mb-0">
                            N: {{ $nitrogen }} | P: {{ $fosfor }} | K: {{ $kalium }}
                        </h5>
                    </div>
                </div>
            </div>

            {{-- TIMESTAMP --}}
            <div class="mt-4 text-center">
                <small class="text-muted">
                    ⏱ Last Updated: {{ $updated_at ?? now()->format('H:i:s') }}
                </small>
            </div>

            {{-- LOADING INDICATOR --}}
            <div class="text-center mt-4" wire:loading>
                <div class="spinner-border spinner-border-sm text-primary"></div>
                <span class="text-muted ms-2">Memperbarui data dari sensor...</span>
            </div>
        </div>
    </div>
</div>
