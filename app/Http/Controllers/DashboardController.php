<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Menampilkan dashboard utama.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Mendefinisikan data modul di dalam controller
        $modules = [
            ['href' => route('air-quality'), 'icon' => 'fas fa-wind', 'color' => 'primary', 'title' => 'Kualitas Udara', 'text' => 'Lihat data polusi dan kualitas udara secara real-time dari sensor.'],
            ['href' => route('soil-test'), 'icon' => 'fas fa-seedling', 'color' => 'success', 'title' => 'Monitoring Tanah', 'text' => 'Pantau kelembaban, suhu, dan tingkat nutrisi tanah.'],
            ['href' => '#', 'icon' => 'fas fa-solar-panel', 'color' => 'warning', 'title' => 'Panel Surya', 'text' => 'Lacak produksi energi dan efisiensi panel secara keseluruhan.'],
            ['href' => '#', 'icon' => 'fas fa-tint', 'color' => 'info', 'title' => 'Kualitas Air', 'text' => 'Analisis tingkat kemurnian, pH, dan suhu air.'],
            ['href' => '#', 'icon' => 'fas fa-bolt', 'color' => 'danger', 'title' => 'Konsumsi Energi', 'text' => 'Monitor penggunaan daya di berbagai perangkat dan lokasi.'],
            ['href' => '#', 'icon' => 'fas fa-map-marker-alt', 'color' => 'secondary', 'title' => 'Pelacakan Aset', 'text' => 'Lacak lokasi dan status aset berharga secara real-time.'],
            ['href' => '#', 'icon' => 'fas fa-lightbulb', 'color' => 'warning', 'title' => 'Pencahayaan Cerdas', 'text' => 'Kontrol dan otomatisasi sistem pencahayaan untuk efisiensi.'],
            ['href' => route('soil-manag'), 'icon' => 'fas fa-leaf', 'color' => 'success', 'title' => 'Soil Management', 'text' => 'Kelola nutrisi, pH, dan kondisi lingkungan untuk tanaman.'],
        ];

        // Mengirimkan data 'modules' ke view
        return view('iot-dashboard-minimalist', compact('modules'));
    }
}
