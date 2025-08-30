<div wire:poll.5s="fetchData">
    {{-- Card utama untuk membungkus semua elemen --}}
    <div class="card">
        <div class="card-header">
            <h4 class="card-title mb-0">Air Quality Monitoring</h4>
        </div><!-- end card header -->

        <div class="card-body">
            <div class="row text-center">

                <!-- Data Suhu -->
                <div class="col-sm-4">
                    <div class="card bg-light border">
                        <div class="card-body">
                            <h5 class="text-muted mb-3">🌡️ Suhu</h5>
                            <h1 class="mb-0 fw-bold display-5">{{ $temperature }}</h1>
                            <span class="fs-4 text-muted">°C</span>
                        </div>
                    </div>
                </div><!-- end col -->

                <!-- Data Kelembaban -->
                <div class="col-sm-4">
                    <div class="card bg-light border">
                        <div class="card-body">
                            <h5 class="text-muted mb-3">💧 Kelembaban</h5>
                            <h1 class="mb-0 fw-bold display-5">{{ $humidity }}</h1>
                            <span class="fs-4 text-muted">%</span>
                        </div>
                    </div>
                </div><!-- end col -->

                <!-- Data Kualitas Udara -->
                <div class="col-sm-4">
                    <div class="card bg-light border">
                        <div class="card-body">
                            <h5 class="text-muted mb-3">🌫️ Kualitas Udara</h5>
                            <h1 class="mb-0 fw-bold display-5">{{ $airQuality }}</h1>
                            <span class="fs-4 text-muted">Index</span>
                        </div>
                    </div>
                </div><!-- end col -->

            </div><!-- end row -->

            {{-- Indikator loading saat Livewire memperbarui data --}}
            <div class="text-center mt-3" wire:loading>
                <div class="spinner-border spinner-border-sm text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <span class="text-muted ms-1">Memperbarui data dari sensor...</span>
            </div>

        </div><!-- end card-body -->
    </div><!-- end card -->
</div>
