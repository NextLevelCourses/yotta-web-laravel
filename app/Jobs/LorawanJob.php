<?php

namespace App\Jobs;

use App\Http\Controllers\Controller;

use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class LorawanJob extends Controller implements ShouldQueue
{
    use Queueable;

    private function HandleGetDataLora()
    {
        try {
            //exec hold to handle 429
            sleep(config('lorawan.hold'));
            $data = $this->HandleLoraIncludePartOfObjectInsideArray($this->HandleLoraGetApi(
                config('lorawan.url'),
                config('lorawan.endpoint'),
                config('lorawan.token'),
                config('lorawan.accept'),
            ));

            //cek data null or another error
            if (!empty($data) && $data != 0) {
                Log::info('LorawanJob: Success fetch data');
                Log::info($data);
            } else {
                Log::info('LorawanJob - Failed fetch data:' . $data);
            }
        } catch (\Exception $e) {
            Log::error('LorawanJob: Internal error - ' . $e->getMessage());
        }
    }


    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->HandleGetDataLora();
    }
}
