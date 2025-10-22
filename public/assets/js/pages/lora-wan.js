document.addEventListener("livewire:init", () => {
    if (typeof echarts === "undefined") {
        console.error(
            "❌ ECharts belum dimuat. Pastikan sudah di-include di layout."
        );
        return;
    }

    console.log("✅ Livewire & LoRaWAN Chart siap!");
    let loraWanChart;

    Livewire.on("chartDataLoraWan", (payload) => {
        console.log("📡 Data grafik LoRaWAN diterima:", payload);

        const chartDom = document.getElementById("lorawan-chart");
        if (!chartDom) {
            console.error("Elemen #lorawan-chart tidak ditemukan.");
            return;
        }

        // Inisialisasi ECharts hanya sekali
        if (!loraWanChart) {
            loraWanChart = echarts.init(chartDom, null, { renderer: "canvas" });
        }

        const option = {
            backgroundColor: "transparent",
            animationDuration: 800,
            tooltip: {
                trigger: "axis",
                backgroundColor: "rgba(50,50,50,0.85)",
                borderWidth: 0,
                textStyle: { color: "#fff" },
            },
            legend: {
                data: [
                    "Suhu Udara",
                    "Kelembaban Udara",
                    "pH Tanah",
                    "Suhu Tanah",
                    "PAR",
                    "Konduktivitas",
                    "Kelembaban Tanah",
                    "Nitrogen",
                    "Fosfor",
                    "Kalium",
                ],
                type: "scroll",
                bottom: 5,
                textStyle: { color: "#555", fontWeight: 500 },
            },
            grid: {
                top: "10%",
                left: "3%",
                right: "3%",
                bottom: "15%",
                containLabel: true,
            },
            xAxis: {
                type: "category",
                boundaryGap: false,
                data: payload.data.labels || [],
                axisLabel: {
                    color: "#666",
                    rotate: 30,
                },
                axisLine: {
                    lineStyle: { color: "#ccc" },
                },
            },
            yAxis: {
                type: "value",
                axisLabel: {
                    color: "#666",
                },
                splitLine: {
                    lineStyle: { color: "#eee", type: "dashed" },
                },
            },
            series: [
                {
                    name: "Suhu Udara",
                    type: "line",
                    smooth: true,
                    showSymbol: false,
                    lineStyle: { color: "#ff6b6b", width: 2 },
                    areaStyle: { color: "rgba(255,107,107,0.2)" },
                    data: payload.data.air_temperature,
                },
                {
                    name: "Kelembaban Udara",
                    type: "line",
                    smooth: true,
                    showSymbol: false,
                    lineStyle: { color: "#4ecdc4", width: 2 },
                    areaStyle: { color: "rgba(78,205,196,0.2)" },
                    data: payload.data.air_humidity,
                },
                {
                    name: "pH Tanah",
                    type: "line",
                    smooth: true,
                    showSymbol: false,
                    lineStyle: { color: "#f9c74f", width: 2 },
                    areaStyle: { color: "rgba(249,199,79,0.2)" },
                    data: payload.data.soil_pH,
                },
                {
                    name: "Suhu Tanah",
                    type: "line",
                    smooth: true,
                    showSymbol: false,
                    lineStyle: { color: "#d62828", width: 2 },
                    areaStyle: { color: "rgba(214,40,40,0.2)" },
                    data: payload.data.soil_temperature,
                },
                {
                    name: "PAR",
                    type: "line",
                    smooth: true,
                    showSymbol: false,
                    lineStyle: { color: "#f77f00", width: 2 },
                    areaStyle: { color: "rgba(247,127,0,0.2)" },
                    data: payload.data.par_value,
                },
                {
                    name: "Konduktivitas",
                    type: "line",
                    smooth: true,
                    showSymbol: false,
                    lineStyle: { color: "#8338ec", width: 2 },
                    areaStyle: { color: "rgba(131,56,236,0.2)" },
                    data: payload.data.soil_conductivity,
                },
                {
                    name: "Kelembaban Tanah",
                    type: "line",
                    smooth: true,
                    showSymbol: false,
                    lineStyle: { color: "#3a86ff", width: 2 },
                    areaStyle: { color: "rgba(58,134,255,0.2)" },
                    data: payload.data.soil_humidity,
                },
                {
                    name: "Nitrogen",
                    type: "line",
                    smooth: true,
                    showSymbol: false,
                    lineStyle: { color: "#2a9d8f", width: 2 },
                    areaStyle: { color: "rgba(42,157,143,0.2)" },
                    data: payload.data.nitrogen,
                },
                {
                    name: "Fosfor",
                    type: "line",
                    smooth: true,
                    showSymbol: false,
                    lineStyle: { color: "#e76f51", width: 2 },
                    areaStyle: { color: "rgba(231,111,81,0.2)" },
                    data: payload.data.phosphorus,
                },
                {
                    name: "Kalium",
                    type: "line",
                    smooth: true,
                    showSymbol: false,
                    lineStyle: { color: "#577590", width: 2 },
                    areaStyle: { color: "rgba(87,117,144,0.2)" },
                    data: payload.data.potassium,
                },
            ],
        };

        loraWanChart.setOption(option, true);

        // Pastikan grafik tetap responsif
        window.addEventListener("resize", () => {
            loraWanChart.resize();
        });
    });
});
