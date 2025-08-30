document.addEventListener("livewire:navigated", initChart);
document.addEventListener("livewire:update", initChart);

function initChart() {
    let chartDom = document.getElementById("airQualityChart");
    if (!chartDom) return;

    let myChart = echarts.init(chartDom);

    let temperature = chartDom.getAttribute("data-temperature") || 0;
    let humidity = chartDom.getAttribute("data-humidity") || 0;
    let airQuality = chartDom.getAttribute("data-airquality") || 0;

    let option = {
        xAxis: {
            type: "category",
            data: ["Suhu", "Kelembaban", "Kualitas Udara"],
        },
        yAxis: { type: "value" },
        series: [{ type: "bar", data: [temperature, humidity, airQuality] }],
    };

    myChart.setOption(option);
}

// inisialisasi pertama
document.addEventListener("DOMContentLoaded", initChart);
