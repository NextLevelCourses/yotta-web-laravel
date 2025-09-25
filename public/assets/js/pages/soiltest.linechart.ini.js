document.addEventListener("DOMContentLoaded", function () {
    if (!window.sensorData) return;

    var chartDom = document.getElementById("sensorChart");
    var myChart = echarts.init(chartDom);

    var option = {
        tooltip: { trigger: "axis" },
        legend: { data: ["Suhu (°C)", "Kelembaban (%)", "CO₂ (ppm)"] },
        grid: { left: "3%", right: "4%", bottom: "3%", containLabel: true },
        xAxis: {
            type: "category",
            boundaryGap: false,
            data: window.sensorData.labels,
        },
        yAxis: { type: "value" },
        series: [
            {
                name: "Suhu (°C)",
                type: "line",
                smooth: true,
                data: window.sensorData.temperature,
                color: "#f46a6a",
            },
            {
                name: "Kelembaban (%)",
                type: "line",
                smooth: true,
                data: window.sensorData.humidity,
                color: "#34c38f",
            },
            {
                name: "CO₂ (ppm)",
                type: "line",
                smooth: true,
                data: window.sensorData.co2,
                color: "#50a5f1",
            },
        ],
    };

    myChart.setOption(option);

    // 🔄 Responsif
    window.addEventListener("resize", function () {
        myChart.resize();
    });
});
