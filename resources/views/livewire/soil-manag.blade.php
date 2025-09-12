<div wire:poll.5s="fetchData">
    <div class="card shadow-lg rounded-xl">
        <div class="card-header bg-gray-50 rounded-t-xl">
            <h4 class="card-title mb-0 font-bold text-lg text-gray-800">Soil Quality Monitoring</h4>
            <small class="{{ $statusClass }}">{{ $status }}</small>
        </div>

        <div class="card-body p-6">
            <div class="row text-center">
                {{-- Suhu --}}
                <div class="col-sm-4" wire:ignore>
                    <h5>🌡️ Suhu Tanah</h5>
                    <input type="text" class="dial"
                           id="suhuKnob"
                           data-width="120" data-height="120"
                           data-fgcolor="#f5b849"
                           data-min="0" data-max="50"
                           value="{{ $temperature }}">
                </div>

                {{-- Kelembaban --}}
                <div class="col-sm-4" wire:ignore>
                    <h5>💧 Kelembaban</h5>
                    <input type="text" class="dial"
                           id="humKnob"
                           data-width="120" data-height="120"
                           data-fgcolor="#4fc3f7"
                           data-min="0" data-max="100"
                           value="{{ $humidity }}">
                </div>

                {{-- pH --}}
                <div class="col-sm-4" wire:ignore>
                    <h5>⚗️ pH Tanah</h5>
                    <input type="text" class="dial"
                           id="phKnob"
                           data-width="120" data-height="120"
                           data-fgcolor="#81c784"
                           data-min="0" data-max="14"
                           value="{{ $ph }}">
                </div>
            </div>

            <div class="row text-center mt-4">
                {{-- Konduktivitas --}}
                <div class="col-sm-4" wire:ignore>
                    <h5>⚡ Konduktivitas</h5>
                    <input type="text" class="dial"
                           id="ecKnob"
                           data-width="120" data-height="120"
                           data-fgcolor="#ba68c8"
                           data-min="0" data-max="100"
                           value="{{ $ec }}">
                </div>

                {{-- Nitrogen --}}
                <div class="col-sm-4" wire:ignore>
                    <h5>🌱 Nitrogen</h5>
                    <input type="text" class="dial"
                           id="nKnob"
                           data-width="120" data-height="120"
                           data-fgcolor="#ff8a65"
                           data-min="0" data-max="100"
                           value="{{ $nitrogen }}">
                </div>

                {{-- Fosfor --}}
                <div class="col-sm-4" wire:ignore>
                    <h5>🧪 Fosfor</h5>
                    <input type="text" class="dial"
                           id="pKnob"
                           data-width="120" data-height="120"
                           data-fgcolor="#f06292"
                           data-min="0" data-max="100"
                           value="{{ $fosfor }}">
                </div>
            </div>

            <div class="row text-center mt-4">
                {{-- Kalium --}}
                <div class="col-sm-4 offset-sm-4" wire:ignore>
                    <h5>🧬 Kalium</h5>
                    <input type="text" class="dial"
                           id="kKnob"
                           data-width="120" data-height="120"
                           data-fgcolor="#ffca28"
                           data-min="0" data-max="100"
                           value="{{ $kalium }}">
                </div>
            </div>

            <div class="mt-3 text-center">
                <small class="text-muted">⏱ Last Update: {{ $lastUpdate }}</small>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function initKnobs() {
    $(".dial").knob({
        readOnly: true,
        thickness: 0.3,
        angleArc: 250,
        angleOffset: -125
    });
}

document.addEventListener("DOMContentLoaded", () => {
    initKnobs();

    document.addEventListener("livewire:load", () => {
        Livewire.hook("message.processed", () => {
            // update knob setiap kali Livewire selesai render
            $(".dial").each(function () {
                const newVal = $(this).attr("value");
                $(this).val(newVal).trigger("change");
            });
        });
    });
});
</script>
@endpush
