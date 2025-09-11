<div class="card">
    <div class="card-header">
        <h4 class="card-title">Air Quality Monitoring</h4>
        <p class="card-title-desc">Data kualitas udara terbaru dari sensor</p>
    </div>

    <div class="card-body">
        {{-- Baris untuk menampilkan dial/knob --}}
        <div class="row text-center">
            <div class="col-md-4">
                <input type="text" class="dial" id="suhuKnob" data-width="120" data-height="120" data-fgColor="#f5b849" data-readOnly="true" value="0" data-min="0" data-max="50">
                <h5 class="mt-2">Suhu (°C)</h5>
            </div>
            <div class="col-md-4">
                <input type="text" class="dial" id="kelembabanKnob" data-width="120" data-height="120" data-fgColor="#556ee6" data-readOnly="true" value="0" data-min="0" data-max="100">
                <h5 class="mt-2">Kelembaban (%)</h5>
            </div>
            <div class="col-md-4">
                <input type="text" class="dial" id="airQualityKnob" data-width="120" data-height="120" data-fgColor="#34c38f" data-readOnly="true" value="0" data-min="0" data-max="500">
                <h5 class="mt-2">Air Quality</h5>
            </div>
        </div>

        <hr>

        <div id="sensorChart" style="width: 100%; height: 300px;"></div>
        {{-- Info tambahan dan kontrol --}}
        <ul class="list-group list-group-flush mt-3">
            <li class="list-group-item">🕒 <span id="lastUpdate">Update Terakhir: -</span></li>
            <li class="list-group-item">
                🌀 Fan Status:
                <strong id="fanStatus" class="text-danger">OFF</strong>
            </li>
        </ul>

        {{-- Hanya tampilkan div ini jika user adalah admin --}}
        @if(auth()->user() && auth()->user()->role == 'admin')
        <div class="mt-3 d-flex gap-2">
            <button id="btnOn" class="btn btn-success btn-sm">Nyalakan Fan</button>
            <button id="btnOff" class="btn btn-danger btn-sm">Matikan Fan</button>
            
        </div>
        @endif

        <div id="status" class="mt-3 text-muted">Belum terhubung ke broker...</div>
        
    </div>
</div>

<!-- php artisan mqtt:subscribe -->
