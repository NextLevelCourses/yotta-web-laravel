<?php

namespace App\Livewire;

use Livewire\Component;
use Kreait\Firebase\Contract\Firestore;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Polling; // Baris ini yang perlu ditambahkan

class SoilManagLivewire extends Component
{
    public $temperature = 'N/A';
    public $humidity = 'N/A';
    public $ph = 'N/A';
    public $ec = 'N/A';
    public $nitrogen = 'N/A';
    public $fosfor = 'N/A';
    public $kalium = 'N/A';
    public $lastUpdate = 'N/A';

    public $status = 'Mempersiapkan koneksi...';
    public $statusClass = 'text-muted';
    public $errorMessage = '';

    public function mount(Firestore $firestore)
    {
        $this->fetchData($firestore);
    }

    public function fetchData(Firestore $firestore)
    {
        try {
            $database = $firestore->database();

            $snapshot = $database->collection('artifacts')
                ->document('default-app-id')
                ->collection('public')
                ->document('data')
                ->collection('soilmanag')
                ->document('update')
                ->snapshot();

            if ($snapshot->exists()) {
                $data = $snapshot->data();

                $this->temperature = $data['Suhu Tanah'] ?? 'N/A';
                $this->humidity    = $data['Kelembaban Tanah'] ?? 'N/A';
                $this->ph          = $data['pH Tanah'] ?? 'N/A';
                $this->ec          = $data['Konduktivitas'] ?? 'N/A';
                $this->nitrogen    = $data['Nitrogen'] ?? 'N/A';
                $this->fosfor      = $data['Fosfor'] ?? 'N/A';
                $this->kalium      = $data['Kalium'] ?? 'N/A';
                $this->lastUpdate  = now()->format('d-m-Y H:i:s');

                $this->status = 'Data berhasil diperbarui!';
                $this->statusClass = 'text-success';
                $this->errorMessage = '';
            } else {
                $this->status = 'Dokumen tidak ditemukan.';
                $this->statusClass = 'text-warning';
                $this->errorMessage = 'Dokumen update tidak ada di Firestore.';
            }
        } catch (\Throwable $e) {
            $this->status = 'Koneksi gagal.';
            $this->statusClass = 'text-danger';
            $this->errorMessage = 'Error: ' . $e->getMessage();
            Log::error('Firestore Fetch Error: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.soil-manag');
    }
}