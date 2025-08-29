📌 yottaaksara-iot-dashboard

IoT Monitoring Dashboard untuk menampilkan data sensor secara real-time (suhu, kelembaban, kualitas udara, dan parameter lingkungan lainnya) yang dikirim dari perangkat berbasis ESP32 melalui MQTT ke sistem web berbasis Laravel + Livewire.

✨ Fitur Utama

📡 Integrasi MQTT: ESP32 mengirim data sensor (dummy/real) ke broker MQTT dan dashboard menampilkannya.

📊 Monitoring Real-time: Data lingkungan (air quality, suhu, kelembaban, dll.) divisualisasikan langsung di web.

💡 Kontrol Perangkat: Dashboard dapat mengirim perintah balik ke ESP32 (misalnya ON/OFF lampu/relay).

📁 Export Data: Unduh log data sensor (Excel/CSV).

🎨 UI Responsif: Tampilan modern berbasis Laravel Blade + Livewire.

🛠️ Teknologi yang Digunakan

Backend: Laravel 12, Livewire

Frontend: Blade, Bootstrap, Chart.js/EasyPieChart

IoT Device: ESP32 dengan protokol MQTT

Broker MQTT: EMQX (broker.emqx.io / self-hosted)

Database: MySQL

🚀 Cara Kerja Singkat

ESP32 membaca data sensor (atau dummy data).

ESP32 publish data ke broker MQTT.

Laravel + Livewire subscribe data tersebut dan menampilkannya di dashboard.

User bisa mengirim perintah kontrol (misalnya nyalakan/matikan lampu) dari dashboard ke ESP32.

👉 Repo ini bisa jadi fondasi IoT Platform untuk smart building, pertanian cerdas, atau sistem monitoring energi.
