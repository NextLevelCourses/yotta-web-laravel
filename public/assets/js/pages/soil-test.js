document.addEventListener("livewire:init", () => {
    let gauges = {};
    let charts = {};

    const gaugeOptions = (title, unit, min, max, zones) => ({
        responsive: true,
        maintainAspectRatio: false,
        legend: {
            display: false,
        },
        title: {
            display: true,
            text: title,
        },
        layout: {
            padding: {
                bottom: 30,
            },
        },
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
        animation: {
            duration: 1000,
            easing: "easeOutQuart",
        },
        scales: {
            xAxes: [
                {
                    display: false,
                },
            ],
            yAxes: [
                {
                    display: false,
                    ticks: {
                        min,
                        max,
                    },
                    gridLines: {
                        drawBorder: false,
                        display: false,
                    },
                },
            ],
        },
        plugins: {
            gauge: {
                data: zones,
            },
        },
    });

    const initGauge = (id, title, unit, min, max, zones) => {
        const el = document.getElementById(id);
        if (!el) return null;
        const ctx = el.getContext("2d");
        const data = {
            datasets: [
                {
                    data: [0],
                    backgroundColor: zones.map((zone) => zone.color),
                },
            ],
        };
        const config = {
            type: "gauge",
            data: data,
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
                title: {
                    display: true,
                    text: title,
                },
                scales: {
                    xAxes: [
                        {
                            display: true,
                            scaleLabel: {
                                display: true,
                                labelString: "Waktu",
                            },
                        },
                    ],
                    yAxes: [
                        {
                            display: true,
                            scaleLabel: {
                                display: true,
                                labelString: `Nilai (${unit})`,
                            },
                            ticks: {
                                suggestedMin: 0,
                                suggestedMax: max,
                            },
                        },
                    ],
                },
            },
        };
        return new Chart(ctx, config);
    };

    // Initialize all gauges
    gauges["suhuGauge"] = initGauge("suhuGauge", "Suhu", "°C", 0, 50, [
        { value: 15, color: "#3399ff" },
        { value: 30, color: "#33cc33" },
        { value: 50, color: "#ff4d4d" },
    ]);
    gauges["kelembabanGauge"] = initGauge(
        "kelembabanGauge",
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
    gauges["ecGauge"] = initGauge("ecGauge", "EC", "uS/cm", 0, 2000, [
        { value: 500, color: "#ff4d4d" },
        { value: 1500, color: "#33cc33" },
        { value: 2000, color: "#ff4d4d" },
    ]);
    gauges["phGauge"] = initGauge("phGauge", "pH", "", 0, 14, [
        { value: 5, color: "#ff4d4d" },
        { value: 9, color: "#33cc33" },
        { value: 14, color: "#ff4d4d" },
    ]);
    gauges["nitrogenGauge"] = initGauge(
        "nitrogenGauge",
        "Nitrogen",
        "mg/kg",
        0,
        500,
        [
            { value: 100, color: "#ff4d4d" },
            { value: 400, color: "#33cc33" },
            { value: 500, color: "#ff4d4d" },
        ]
    );
    gauges["fosforGauge"] = initGauge(
        "fosforGauge",
        "Fosfor",
        "mg/kg",
        0,
        500,
        [
            { value: 100, color: "#ff4d4d" },
            { value: 400, color: "#33cc33" },
            { value: 500, color: "#ff4d4d" },
        ]
    );
    gauges["kaliumGauge"] = initGauge(
        "kaliumGauge",
        "Kalium",
        "mg/kg",
        0,
        500,
        [
            { value: 100, color: "#ff4d4d" },
            { value: 400, color: "#33cc33" },
            { value: 500, color: "#ff4d4d" },
        ]
    );

    // Initialize all line charts
    charts["suhuChart"] = initChart("suhuChart", "Suhu", "°C", "#ff4d4d", 50);
    charts["kelembabanChart"] = initChart(
        "kelembabanChart",
        "Kelembaban",
        "%",
        "#3399ff",
        100
    );
    charts["ecChart"] = initChart("ecChart", "EC", "uS/cm", "#33cc33", 2000);
    charts["phChart"] = initChart("phChart", "pH", "", "#ffcc00", 14);
    charts["nitrogenChart"] = initChart(
        "nitrogenChart",
        "Nitrogen",
        "mg/kg",
        "#9933ff",
        500
    );
    charts["fosforChart"] = initChart(
        "fosforChart",
        "Fosfor",
        "mg/kg",
        "#ff9933",
        500
    );
    charts["kaliumChart"] = initChart(
        "kaliumChart",
        "Kalium",
        "mg/kg",
        "#009999",
        500
    );

    // Livewire listener to update charts
    Livewire.on("updateKnobs", (data) => {
        Object.keys(data[0]).forEach((key) => {
            const value = data[0][key];
            if (
                value !== null &&
                value !== undefined &&
                value !== "--" &&
                !isNaN(parseFloat(value))
            ) {
                // Update gauge charts
                const gaugeKey = `${key}Gauge`;
                if (gauges[gaugeKey]) {
                    gauges[gaugeKey].data.datasets[0].data = [
                        parseFloat(value),
                    ];
                    gauges[gaugeKey].update();
                }

                // Update line charts
                const chartKey = `${key}Chart`;
                if (charts[chartKey]) {
                    const now = new Date();
                    charts[chartKey].data.labels.push(now.toLocaleTimeString());
                    charts[chartKey].data.datasets[0].data.push(
                        parseFloat(value)
                    );

                    // Batasi jumlah data di grafik
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

    // Cleanup and re-init on livewire:update
    Livewire.on("livewire:update", () => {
        // Destroy existing gauges
        Object.keys(gauges).forEach((key) => {
            if (gauges[key]) {
                gauges[key].destroy();
            }
        });
        gauges = {};
        // Destroy existing charts
        Object.keys(charts).forEach((key) => {
            if (charts[key]) {
                charts[key].destroy();
            }
        });
        charts = {};

        // Re-initialize all gauges
        gauges["suhuGauge"] = initGauge("suhuGauge", "Suhu", "°C", 0, 50, [
            { value: 15, color: "#3399ff" },
            { value: 30, color: "#33cc33" },
            { value: 50, color: "#ff4d4d" },
        ]);
        gauges["kelembabanGauge"] = initGauge(
            "kelembabanGauge",
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
        gauges["ecGauge"] = initGauge("ecGauge", "EC", "uS/cm", 0, 2000, [
            { value: 500, color: "#ff4d4d" },
            { value: 1500, color: "#33cc33" },
            { value: 2000, color: "#ff4d4d" },
        ]);
        gauges["phGauge"] = initGauge("phGauge", "pH", "", 0, 14, [
            { value: 5, color: "#ff4d4d" },
            { value: 9, color: "#33cc33" },
            { value: 14, color: "#ff4d4d" },
        ]);
        gauges["nitrogenGauge"] = initGauge(
            "nitrogenGauge",
            "Nitrogen",
            "mg/kg",
            0,
            500,
            [
                { value: 100, color: "#ff4d4d" },
                { value: 400, color: "#33cc33" },
                { value: 500, color: "#ff4d4d" },
            ]
        );
        gauges["fosforGauge"] = initGauge(
            "fosforGauge",
            "Fosfor",
            "mg/kg",
            0,
            500,
            [
                { value: 100, color: "#ff4d4d" },
                { value: 400, color: "#33cc33" },
                { value: 500, color: "#ff4d4d" },
            ]
        );
        gauges["kaliumGauge"] = initGauge(
            "kaliumGauge",
            "Kalium",
            "mg/kg",
            0,
            500,
            [
                { value: 100, color: "#ff4d4d" },
                { value: 400, color: "#33cc33" },
                { value: 500, color: "#ff4d4d" },
            ]
        );

        // Re-initialize all line charts
        charts["suhuChart"] = initChart(
            "suhuChart",
            "Suhu",
            "°C",
            "#ff4d4d",
            50
        );
        charts["kelembabanChart"] = initChart(
            "kelembabanChart",
            "Kelembaban",
            "%",
            "#3399ff",
            100
        );
        charts["ecChart"] = initChart(
            "ecChart",
            "EC",
            "uS/cm",
            "#33cc33",
            2000
        );
        charts["phChart"] = initChart("phChart", "pH", "", "#ffcc00", 14);
        charts["nitrogenChart"] = initChart(
            "nitrogenChart",
            "Nitrogen",
            "mg/kg",
            "#9933ff",
            500
        );
        charts["fosforChart"] = initChart(
            "fosforChart",
            "Fosfor",
            "mg/kg",
            "#ff9933",
            500
        );
        charts["kaliumChart"] = initChart(
            "kaliumChart",
            "Kalium",
            "mg/kg",
            "#009999",
            500
        );
    });
});
