document.addEventListener("DOMContentLoaded", () => {
    // --------------------------------------------------
    // ELEMEN HTML
    // --------------------------------------------------
    const suhuKnob = $("#suhuKnob");
    const kelembabanKnob = $("#kelembabanKnob");
    const airQualityKnob = $("#airQualityKnob");
    const soilMoistureKnob = $("#soilMoistureKnob");
    const lastUpdateEl = document.getElementById("lastUpdate");
    const fanStatusEl = document.getElementById("fanStatus");
    const btnOn = document.getElementById("btnOn");
    const btnOff = document.getElementById("btnOff");
    const btnDownload = document.getElementById("btnDownload");

    // --------------------------------------------------
    // INISIALISASI ECHARTS
    // --------------------------------------------------
    const chartDom = document.getElementById("sensorChart");
    // Pastikan elemen chart ada sebelum inisialisasi
    if (!chartDom) return;

    const myChart = echarts.init(chartDom);
    const chartOption = {
        title: { text: "Grafik Sensor Real-time" },
        tooltip: { trigger: "axis" },
        legend: {
            data: ["Suhu", "Kelembaban", "Kualitas Udara", "Kelembaban Tanah"],
        },
        xAxis: { type: "category", boundaryGap: false, data: [] },
        yAxis: [
            {
                type: "value",
                name: "Suhu (°C)",
                min: 0,
                max: 50,
                position: "left",
            },
            {
                type: "value",
                name: "Kelembaban/Tanah (%)",
                min: 0,
                max: 100,
                position: "right",
            },
            {
                type: "value",
                name: "Kualitas Udara",
                position: "left",
                offset: 50,
            },
        ],
        series: [
            {
                name: "Suhu",
                type: "line",
                yAxisIndex: 0,
                data: [],
                smooth: true,
            },
            {
                name: "Kelembaban",
                type: "line",
                yAxisIndex: 1,
                data: [],
                smooth: true,
            },
            {
                name: "Kualitas Udara",
                type: "line",
                yAxisIndex: 2,
                data: [],
                smooth: true,
            },
            {
                name: "Kelembaban Tanah",
                type: "line",
                yAxisIndex: 1,
                data: [],
                smooth: true,
            },
        ],
    };
    myChart.setOption(chartOption);

    // --------------------------------------------------
    // FUNGSI UNTUK UPDATE UI
    // --------------------------------------------------
    function updateSensorReadings(data) {
        if (!data) return;

        const now = new Date();
        const timeString = now.toLocaleTimeString("id-ID");

        // Update Knobs
        suhuKnob.val(data.temperature || 0).trigger("change");
        kelembabanKnob.val(data.humidity || 0).trigger("change");
        airQualityKnob.val(data.air_quality || 0).trigger("change");
        soilMoistureKnob.val(data.soil_moisture || 0).trigger("change");

        // Update Teks
        lastUpdateEl.innerText = "Update Terakhir: " + timeString;
        updateFanStatusUI(data.fan_status);

        // Update Grafik
        const maxDataPoints = 30;
        const xAxisData = chartOption.xAxis.data;

        xAxisData.push(timeString);
        chartOption.series[0].data.push(data.temperature || null);
        chartOption.series[1].data.push(data.humidity || null);
        chartOption.series[2].data.push(data.air_quality || null);
        chartOption.series[3].data.push(data.soil_moisture || null);

        if (xAxisData.length > maxDataPoints) {
            xAxisData.shift();
            chartOption.series.forEach((series) => series.data.shift());
        }
        myChart.setOption(chartOption);
    }

    function updateFanStatusUI(status) {
        if (status) {
            fanStatusEl.innerText = "ON";
            fanStatusEl.className = "fw-bold text-success ms-2";
        } else {
            fanStatusEl.innerText = "OFF";
            fanStatusEl.className = "fw-bold text-danger ms-2";
        }
    }

    // --------------------------------------------------
    // INTERAKSI DENGAN LIVEWIRE
    // --------------------------------------------------
    // Mendengarkan event 'sensorDataUpdated' yang dikirim dari komponen Livewire
    Livewire.on("sensorDataUpdated", (sensorData) => {
        updateSensorReadings(sensorData);
    });

    // Mengirim event ke komponen Livewire untuk mengontrol kipas
    if (btnOn)
        btnOn.addEventListener("click", () =>
            Livewire.emit("controlFan", true)
        );
    if (btnOff)
        btnOff.addEventListener("click", () =>
            Livewire.emit("controlFan", false)
        );

    // --------------------------------------------------
    // FUNGSI DOWNLOAD DATA CSV
    // --------------------------------------------------
    function downloadDataAsCSV() {
        const headers = [
            "Waktu",
            "Suhu (C)",
            "Kelembaban (%)",
            "Kualitas Udara",
            "Kelembaban Tanah (%)",
        ];
        const xAxisData = chartOption.xAxis.data;

        const rows = xAxisData.map((time, index) =>
            [
                time,
                chartOption.series[0].data[index], // Suhu
                chartOption.series[1].data[index], // Kelembaban
                chartOption.series[2].data[index], // Kualitas Udara
                chartOption.series[3].data[index], // Kelembaban Tanah
            ].join(",")
        );

        const csvContent = [headers.join(","), ...rows].join("\n");

        const blob = new Blob([csvContent], {
            type: "text/csv;charset=utf-8;",
        });
        const link = document.createElement("a");
        const url = URL.createObjectURL(blob);
        const filename = `sensor_data_${new Date()
            .toISOString()
            .slice(0, 10)}.csv`;

        link.setAttribute("href", url);
        link.setAttribute("download", filename);
        link.style.visibility = "hidden";
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }

    if (btnDownload) btnDownload.addEventListener("click", downloadDataAsCSV);
});
