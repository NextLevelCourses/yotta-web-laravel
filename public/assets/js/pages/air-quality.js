document.addEventListener("DOMContentLoaded", (event) => {
    // Inisialisasi plugin JQuery Knob
    $(".dial").knob();

    // --------------------------------------------------
    // INISIALISASI ECHARTS
    // --------------------------------------------------
    const chartDom = document.getElementById("sensorChart");
    const myChart = echarts.init(chartDom);
    const chartOption = {
        title: {
            text: "Grafik Sensor Real-time",
        },
        tooltip: {
            trigger: "axis",
        },
        legend: {
            data: ["Suhu", "Kelembaban"],
        },
        xAxis: {
            type: "category",
            boundaryGap: false,
            data: [], // Data waktu akan diisi di sini
        },
        yAxis: [
            {
                type: "value",
                name: "Suhu (°C)",
                min: 0,
                max: 50,
                axisLabel: {
                    formatter: "{value} °C",
                },
            },
            {
                type: "value",
                name: "Kelembaban (%)",
                min: 0,
                max: 100,
                position: "right",
                axisLabel: {
                    formatter: "{value} %",
                },
            },
        ],
        series: [
            {
                name: "Suhu",
                type: "line",
                yAxisIndex: 0, // Menggunakan yAxis pertama (kiri)
                data: [], // Data suhu akan diisi di sini
                smooth: true,
            },
            {
                name: "Kelembaban",
                type: "line",
                yAxisIndex: 1, // Menggunakan yAxis kedua (kanan)
                data: [], // Data kelembaban akan diisi di sini
                smooth: true,
            },
        ],
    };
    myChart.setOption(chartOption);

    // --------------------------------------------------
    // KONFIGURASI MQTT BROKER
    // --------------------------------------------------
    const brokerHost = "broker.emqx.io";
    const brokerPort = 8083;
    const clientId = "webClient_" + parseInt(Math.random() * 1000);
    const topicSensor = "tes/topic/sensor";
    const topicFanControl = "tes/topic/fan_status";

    // --------------------------------------------------
    // ELEMEN HTML
    // --------------------------------------------------
    const suhuKnob = $("#suhuKnob");
    const kelembabanKnob = $("#kelembabanKnob");
    const airQualityKnob = $("#airQualityKnob");
    const lastUpdateEl = document.getElementById("lastUpdate");
    const fanStatusEl = document.getElementById("fanStatus");
    const statusEl = document.getElementById("status");
    const btnOn = document.getElementById("btnOn");
    const btnOff = document.getElementById("btnOff");

    // --------------------------------------------------
    // INISIALISASI MQTT CLIENT
    // --------------------------------------------------
    const client = new Paho.Client(brokerHost, brokerPort, clientId);
    client.onConnectionLost = onConnectionLost;
    client.onMessageArrived = onMessageArrived;
    const connectOptions = {
        onSuccess: onConnect,
        onFailure: onFailure,
        useSSL: false,
    };
    client.connect(connectOptions);

    // --------------------------------------------------
    // FUNGSI-FUNGSI CALLBACK MQTT
    // --------------------------------------------------
    function onConnect() {
        console.log("✅ Berhasil terhubung ke MQTT Broker!");
        updateStatus("Terhubung ke broker!", "text-success");
        client.subscribe(topicSensor);
    }

    function onFailure(responseObject) {
        console.error("Koneksi gagal: " + responseObject.errorMessage);
        updateStatus(
            `Koneksi gagal: ${responseObject.errorMessage}`,
            "text-danger"
        );
    }

    function onConnectionLost(responseObject) {
        if (responseObject.errorCode !== 0) {
            console.warn("Koneksi terputus: " + responseObject.errorMessage);
            updateStatus("Koneksi terputus.", "text-warning");
        }
    }

    function onMessageArrived(message) {
        console.log(
            `Pesan diterima pada topik [${message.destinationName}]: ${message.payloadString}`
        );

        if (message.destinationName === topicSensor) {
            try {
                const data = JSON.parse(message.payloadString);
                const now = new Date();
                const timeString = now.toLocaleTimeString("id-ID");

                // --- UPDATE UI KNOB ---
                if (data.temperature !== undefined) {
                    suhuKnob.val(data.temperature.toFixed(1)).trigger("change");
                }
                if (data.humidity !== undefined) {
                    kelembabanKnob
                        .val(data.humidity.toFixed(1))
                        .trigger("change");
                }
                if (data.air_quality !== undefined) {
                    airQualityKnob.val(data.air_quality).trigger("change");
                }

                lastUpdateEl.innerText = "Update Terakhir: " + timeString;

                // --- UPDATE DATA GRAFIK ECHARTS ---
                const xAxisData = chartOption.xAxis.data;
                const suhuData = chartOption.series[0].data;
                const kelembabanData = chartOption.series[1].data;

                // 1. Tambahkan data baru
                xAxisData.push(timeString);
                suhuData.push(data.temperature.toFixed(1));
                kelembabanData.push(data.humidity.toFixed(1));

                // 2. Batasi jumlah data agar grafik tidak terlalu padat (misal: 20 data terakhir)
                const maxDataPoints = 20;
                if (xAxisData.length > maxDataPoints) {
                    xAxisData.shift(); // Hapus data waktu paling lama
                    suhuData.shift(); // Hapus data suhu paling lama
                    kelembabanData.shift(); // Hapus data kelembaban paling lama
                }

                // 3. Terapkan pembaruan ke grafik
                myChart.setOption(chartOption);
            } catch (e) {
                console.error("Gagal mem-parsing JSON:", e);
            }
        }
    }

    // ... sisa fungsi (controlFan, updateFanStatusUI, dll) tetap sama ...
    function controlFan(status) {
        if (!client.isConnected()) {
            alert("Tidak terhubung ke broker. Tidak bisa mengirim perintah.");
            return;
        }
        const message = new Paho.Message(String(status));
        message.destinationName = topicFanControl;
        client.send(message);
        console.log(
            `Perintah kipas (${status}) dikirim ke topik [${topicFanControl}]`
        );
        updateFanStatusUI(status);
    }

    function updateFanStatusUI(status) {
        if (status === 1) {
            fanStatusEl.innerText = "ON";
            fanStatusEl.className = "text-success";
        } else {
            fanStatusEl.innerText = "OFF";
            fanStatusEl.className = "text-danger";
        }
    }

    function updateStatus(text, className) {
        statusEl.innerText = text;
        statusEl.className = "mt-3 " + className;
    }

    if (btnOn && btnOff) {
        btnOn.addEventListener("click", () => controlFan(1));
        btnOff.addEventListener("click", () => controlFan(0));
    }
});
