<?php

namespace App\Http\Controllers\Monitoring;

use App\Exports\LoraTestExport;
use App\Models\LoRa;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
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

        //ambil semua data
        $data = [];
        foreach ($jsonObjects as $value) {
            //validate data lora nya exists or null (note: pastikan data dalam bentuk array)
            if (
                !empty($value) &&
                is_array($value)
            ) {
                if (
                    Carbon::parse($value['result']['uplink_message']['received_at'])->timezone(config('app.timezone'))->format('Y-m-d H') >=
                    Carbon::now()->timezone(config('app.timezone'))->format('Y-m-d H')
                ) {
                    array_push($data, $value['result']['uplink_message']);
                }
            } else {
                return 0;
            }
        }

        //map data latest(data paling terbaru saja) supaya realtime alias datanya sesuai dengan apa yang ada di thingsboard
        $latest = array(
            'air_humidity' => max($data)['decoded_payload']['air_humidity'] ?? 0,
            'air_temperature' => max($data)['decoded_payload']['air_temperature'] ?? 0,
            'nitrogen' => max($data)['decoded_payload']['nitrogen'] ?? 0,
            'par_value' => max($data)['decoded_payload']['par_value'] ?? 0,
            'phosphorus' => max($data)['decoded_payload']['phosphorus'] ?? 0,
            'potassium' => max($data)['decoded_payload']['potassium'] ?? 0,
            'soil_conductivity' => max($data)['decoded_payload']['soil_conductivity'] ?? 0,
            'soil_humidity' => max($data)['decoded_payload']['soil_humidity'] ?? 0,
            'soil_pH' => max($data)['decoded_payload']['soil_pH'] ?? 0,
            'soil_temperature' => max($data)['decoded_payload']['soil_temperature'] ?? 0,
            'measured_at' => Carbon::parse(max($data)['received_at'])->timezone(config('app.timezone'))->format('Y-m-d H:i:s') ?? '-',
        );

        //store data latest
        Lora::create($latest);

        //return data
        return max($data);
    }

    public function HandleGetDataLora()
    {
        try {
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
        } catch (\Exception $e) {
            Log::error('Fetch Lora data error: ' . $e->getMessage());
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
