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
    private function HandleIncludePartOfObjectInsideArray($raw): array|string
    {
        $jsonObjects = preg_split('/}\s*{/', $raw);
        $jsonObjects = array_map(function ($json, $i) use ($jsonObjects) {
            // Re-add curly braces we removed
            if ($i > 0) $json = '{' . $json;
            if ($i < count($jsonObjects) - 1) $json .= '}';
            return json_decode($json, true);
        }, $jsonObjects, array_keys($jsonObjects));

        //ambil data last
        $latest = '';
        foreach ($jsonObjects as $key => $value) {
            if (
                Carbon::parse($value['result']['uplink_message']['received_at'])->timezone(config('app.timezone'))->format('Y-m-d H') >= Carbon::now()->timezone(config('app.timezone'))->format('Y-m-d H')
            ) {
                $latest = $value['result']['uplink_message']['decoded_payload'];
            }
        }

        //map data
        $latest = array(
            'air_humidity' => $latest['air_humidity'],
            'air_temperature' => $latest['air_temperature'],
            'nitrogen' => $latest['nitrogen'],
            'par_value' => $latest['par_value'],
            'phosphorus' => $latest['phosphorus'],
            'potassium' => $latest['potassium'],
            'soil_conductivity' => $latest['soil_conductivity'],
            'soil_humidity' => $latest['soil_humidity'],
            'soil_pH' => $latest['soil_pH'],
            'soil_temperature' => $latest['soil_temperature'],
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
        return $this->ResponseOk($data, 'Success Get LoRa Data');
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
