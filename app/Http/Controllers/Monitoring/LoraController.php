<?php

namespace App\Http\Controllers\Monitoring;

use App\Exports\LoraTestExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class LoraController extends Controller
{
    public function HandleGetDataLora()
    {
        try {
            $data = $this->HandleLoraIncludePartOfObjectInsideArray($this->HandleLoraGetApi(
                config('lorawan.url'),
                config('lorawan.endpoint'),
                config('lorawan.token'),
                config('lorawan.accept'),
            ));

            if (!empty($data) && $data != 0) {
                Log::info('Lorawan: Success fetch data');
                return $this->ResponseOk($data, 'Success fetch data');
            } else {
                Log::info('Lorawan - Failed fetch data:' . $data);
                return $this->ResponseError('Failed fetch data', 422);
            }
        } catch (\Exception $e) {
            Log::error('Lorawan Internal Error - ' . $e->getMessage());
            return $this->ResponseError('Error sistem internal', 500);
        }
    }
    /**
     * Tampilkan halaman monitoring (Blade)
     */

    public function index()
    {
        return Auth::check() ? view('monitoring.lora') : redirect()->route('login')->with('error', 'Anda perlu login,silahkan login!');
    }

    public function export(string $date): BinaryFileResponse|string
    {
        try {
            $month = substr($date, 5, 2); // hasilnya mm (month) only without yyyy or dd
            $year = substr($date, 0, 4); // hasilnya yyyy (year) only without mm or dd
            $now = date('Y-m-d');
            return Excel::download(new LoraTestExport($month, $year), "loratest{$now}.xlsx");
        } catch (\Exception $e) {
            Log::error('Export error: ' . $e->getMessage());
            return 'Error exporting data';
        }
    }
}
