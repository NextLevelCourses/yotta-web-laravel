<div wire:poll.3s="fetchSolarDome">
    {{-- Header --}}
    <div class="card shadow-lg border-0 rounded-3 mb-4">
        <div class="card-header bg-primary text-white rounded-top-3">
            <h3 class="m-0 fw-bold text-white text-start">Solar Dome Controller</h3>
            <p class="mb-0 small text-light text-start">Cloud Control System</p>
        </div>

        <div class="card-body">

            {{-- Status Sistem --}}
            <h5 class="fw-bold text-primary mb-3">
                <i class="fas fa-microchip me-2"></i>Status Sistem
            </h5>

            <div class="row g-4 text-center">
                <div class="col-md-3 col-6">
                    <div class="p-3 bg-light rounded shadow-sm">
                        <h6 class="text-muted">SUHU</h6>
                        <h3 id="temperature" class="fw-bold text-dark">{{ $temperature }}</h3>
                        <span class="small text-secondary">°C</span>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="p-3 bg-light rounded shadow-sm">
                        <h6 class="text-muted">KELEMBAPAN</h6>
                        <h3 id="humidity" class="fw-bold text-dark">{{ $humidity }}</h3>
                        <span class="small text-secondary">%</span>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="p-3 bg-light rounded shadow-sm">
                        <h6 class="text-muted">MODE</h6>
                        @switch($controlMode)
                            @case('AUTO')
                                <h4 id="mode" class="fw-bold text-primary">
                                    <i class="fas fa-circle text-primary me-1"></i>{{ $controlMode }}
                                </h4>
                            @break

                            @case('MANUAL_ON')
                                <h4 id="mode" class="fw-bold text-success">
                                    <i class="fas fa-circle text-sucess me-1"></i>{{ $controlMode }}
                                </h4>
                            @break

                            @default
                                <h4 id="mode" class="fw-bold text-danger">
                                    <i class="fas fa-circle text-danger me-1"></i>{{ $controlMode }}
                                </h4>
                        @endswitch
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="p-3 bg-light rounded shadow-sm">
                        <h6 class="text-muted">KIPAS</h6>
                        @if ($relayState)
                            <h4 id="fan_status" class="fw-bold text-success">
                                <i class="fas fa-circle text-success me-1"></i>
                                {{ 'ON' }}
                            </h4>
                        @else
                            <h4 id="fan_status" class="fw-bold text-danger">
                                <i class="fas fa-circle text-danger me-1"></i>
                                {{ 'OFF' }}
                            </h4>
                        @endif
                    </div>
                </div>
            </div>

            <div class="text-end mt-3">
                <small class="text-muted">Terakhir update: <span id="last_update">{{ $measure_at }}</span></small>
            </div>

            {{-- LOADING --}}
            <div class="text-center mt-4" wire:loading>
                <div class="spinner-border spinner-border-sm text-success"></div>
                <span class="text-muted ms-2">Memperbarui data dari sensor...</span>
            </div>


        </div>
    </div>
</div>
