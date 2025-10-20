<?php

namespace App\Http\Controllers\Monitoring;

use App\Exports\LoraTestExport;
use App\Models\LoRa;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class LoraController extends Controller
{
    private function HandleIncludePartOfObjectInsideArray($raw): array|string|int
    {
        $jsonObjects = preg_split('/}\s*{/', $raw);
        $jsonObjects = array_map(function ($json, $i) use ($jsonObjects) {
            // Re-add curly braces we removed
            if ($i > 0) $json = '{' . $json;
            if ($i < count($jsonObjects) - 1) $json .= '}';
            return json_decode($json, true);
        }, $jsonObjects, array_keys($jsonObjects));

        //ambil data last
        $data = '';
        foreach ($jsonObjects as $value) {
            if (!empty($value)) {
                if (
                    Carbon::parse($value['result']['uplink_message']['received_at'])->timezone(config('app.timezone'))->format('Y-m-d H') >= Carbon::now()->timezone(config('app.timezone'))->format('Y-m-d H')
                ) {
                    $data = $value['result']['uplink_message']['decoded_payload'];
                }
            } else {
                return 0;
            }
        }

        $latest = array(
            'air_humidity' => $data['air_humidity'] ?? 0,
            'air_temperature' => $data['air_temperature'] ?? 0,
            'nitrogen' => $data['nitrogen'] ?? 0,
            'par_value' => $data['par_value'] ?? 0,
            'phosphorus' => $data['phosphorus'] ?? 0,
            'potassium' => $data['potassium'] ?? 0,
            'soil_conductivity' => $data['soil_conductivity'] ?? 0,
            'soil_humidity' => $data['soil_humidity'] ?? 0,
            'soil_pH' => $data['soil_pH'] ?? 0,
            'soil_temperature' => $data['soil_temperature'] ?? 0,
            'measured_at' => Carbon::now()->timezone(config('app.timezone'))->format('Y-m-d H:i:s'),
        );

        //store data
        Lora::create($latest);
        return $latest;
    }

    public function HandleGetDataLora()
    {
        $data = $this->HandleIncludePartOfObjectInsideArray($this->HandleGetDataLoraLatest(
            config('lorawan.url'),
            config('lorawan.endpoint'),
            config('lorawan.token'),
            config('lorawan.accept'),
        ));

        if (!empty($data) && $data != 0) {
            return $this->ResponseOk($data, 'Success fetch data');
        } else {
            return $this->ResponseError('Failed fetch data', 422);
        }
    }
    /**
     * Tampilkan halaman monitoring (Blade)
     */

    public function index()
    {
        return view('monitoring.lora');
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
