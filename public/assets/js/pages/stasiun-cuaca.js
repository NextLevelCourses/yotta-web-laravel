document.addEventListener("livewire:init", () => {
    let gauges = {};
    let charts = {};

    const gaugeOptions = (title, unit, min, max, zones) => ({
        responsive: true,
        maintainAspectRatio: false,
        legend: { display: false },
        title: {
            display: true,
            text: title,
        },
        layout: { padding: { bottom: 30 } },
        needle: {
            radiusPercentage: 2,
            widthPercentage: 3.2,
            lengthPercentage: 80,
            color: "rgba(0, 0, 0, 1)",
        },
        valueLabel: {
            display: true,
            formatter: (value) => `${value} ${unit}`,
            color: "rgba(0, 0, 0, 1)",
            fontSize: 24,
        },
        animation: { duration: 1000, easing: "easeOutQuart" },
        scales: {
            xAxes: [{ display: false }],
            yAxes: [
                {
                    display: false,
                    ticks: { min, max },
                    gridLines: { drawBorder: false, display: false },
                },
            ],
        },
        plugins: {
            gauge: { data: zones },
        },
    });

    const initGauge = (id, title, unit, min, max, zones) => {
        const el = document.getElementById(id);
        if (!el) return null;
        const ctx = el.getContext("2d");
        const data = {
            datasets: [
                { data: [0], backgroundColor: zones.map((z) => z.color) },
            ],
        };
        const config = {
            type: "gauge",
            data,
            options: gaugeOptions(title, unit, min, max, zones),
        };
        return new Chart(ctx, config);
    };

    const initChart = (id, title, unit, color, max) => {
        const el = document.getElementById(id);
        if (!el) return null;
        const ctx = el.getContext("2d");
        const config = {
            type: "line",
            data: {
                labels: [],
                datasets: [
                    {
                        label: title,
                        backgroundColor: color,
                        borderColor: color,
                        data: [],
                        fill: false,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                title: { display: true, text: title },
                scales: {
                    xAxes: [
                        {
                            display: true,
                            scaleLabel: { display: true, labelString: "Waktu" },
                        },
                    ],
                    yAxes: [
                        {
                            display: true,
                            scaleLabel: {
                                display: true,
                                labelString: `Nilai (${unit})`,
                            },
                            ticks: { suggestedMin: 0, suggestedMax: max },
                        },
                    ],
                },
            },
        };
        return new Chart(ctx, config);
    };

    // === Inisialisasi semua gauge ===
    gauges["temperatureGauge"] = initGauge(
        "temperatureGauge",
        "Suhu",
        "°C",
        0,
        50,
        [
            { value: 15, color: "#3399ff" },
            { value: 30, color: "#33cc33" },
            { value: 50, color: "#ff4d4d" },
        ]
    );
    gauges["humidityGauge"] = initGauge(
        "humidityGauge",
        "Kelembaban",
        "%",
        0,
        100,
        [
            { value: 30, color: "#ff4d4d" },
            { value: 70, color: "#33cc33" },
            { value: 100, color: "#ff4d4d" },
        ]
    );
    gauges["pressureGauge"] = initGauge(
        "pressureGauge",
        "Tekanan Udara",
        "hPa",
        800,
        1100,
        [
            { value: 950, color: "#ff4d4d" },
            { value: 1020, color: "#33cc33" },
            { value: 1100, color: "#ff4d4d" },
        ]
    );
    gauges["rainfallGauge"] = initGauge(
        "rainfallGauge",
        "Curah Hujan",
        "mm",
        0,
        100,
        [
            { value: 20, color: "#33cc33" },
            { value: 60, color: "#ffcc00" },
            { value: 100, color: "#ff4d4d" },
        ]
    );
    gauges["windSpeedGauge"] = initGauge(
        "windSpeedGauge",
        "Kecepatan Angin",
        "m/s",
        0,
        40,
        [
            { value: 10, color: "#33cc33" },
            { value: 25, color: "#ffcc00" },
            { value: 40, color: "#ff4d4d" },
        ]
    );
    gauges["windDirectionGauge"] = initGauge(
        "windDirectionGauge",
        "Arah Angin",
        "°",
        0,
        360,
        [
            { value: 90, color: "#33cc33" },
            { value: 270, color: "#3399ff" },
            { value: 360, color: "#9933ff" },
        ]
    );

    // === Inisialisasi semua chart garis ===
    charts["temperatureChart"] = initChart(
        "temperatureChart",
        "Suhu",
        "°C",
        "#f46a6a",
        50
    );
    charts["humidityChart"] = initChart(
        "humidityChart",
        "Kelembaban",
        "%",
        "#50a5f1",
        100
    );
    charts["pressureChart"] = initChart(
        "pressureChart",
        "Tekanan Udara",
        "hPa",
        "#34c38f",
        1100
    );
    charts["rainfallChart"] = initChart(
        "rainfallChart",
        "Curah Hujan",
        "mm",
        "#ffcc00",
        100
    );
    charts["windSpeedChart"] = initChart(
        "windSpeedChart",
        "Kecepatan Angin",
        "m/s",
        "#9933ff",
        40
    );
    charts["windDirectionChart"] = initChart(
        "windDirectionChart",
        "Arah Angin",
        "°",
        "#009999",
        360
    );

    // === Update data secara Livewire ===
    Livewire.on("updateCuaca", (data) => {
        Object.keys(data[0]).forEach((key) => {
            const value = data[0][key];
            if (value !== null && !isNaN(parseFloat(value))) {
                const gaugeKey = `${key}Gauge`;
                if (gauges[gaugeKey]) {
                    gauges[gaugeKey].data.datasets[0].data = [
                        parseFloat(value),
                    ];
                    gauges[gaugeKey].update();
                }

                const chartKey = `${key}Chart`;
                if (charts[chartKey]) {
                    const now = new Date();
                    charts[chartKey].data.labels.push(now.toLocaleTimeString());
                    charts[chartKey].data.datasets[0].data.push(
                        parseFloat(value)
                    );

                    // batasi jumlah data
                    const maxDataPoints = 20;
                    if (charts[chartKey].data.labels.length > maxDataPoints) {
                        charts[chartKey].data.labels.shift();
                        charts[chartKey].data.datasets[0].data.shift();
                    }
                    charts[chartKey].update();
                }
            }
        });
    });

    // === Reset grafik saat Livewire re-render ===
    Livewire.on("livewire:update", () => {
        Object.keys(gauges).forEach((key) => gauges[key]?.destroy());
        Object.keys(charts).forEach((key) => charts[key]?.destroy());
        gauges = {};
        charts = {};
    });
});
