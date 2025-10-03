function initKnobs() {
    $(".dial").knob({
        readOnly: true,
        thickness: 0.3,
        angleArc: 250,
        angleOffset: -125,
    });
}

document.addEventListener("DOMContentLoaded", () => {
    initKnobs();

    document.addEventListener("livewire:load", () => {
        Livewire.hook("message.processed", () => {
            // update semua knob
            $(".dial").each(function () {
                $(this).val($(this).attr("value")).trigger("change");
            });
        });
    });
});
