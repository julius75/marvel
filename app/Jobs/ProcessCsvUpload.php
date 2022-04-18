<?php

namespace App\Jobs;

use App\Models\Sale;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Redis;

class ProcessCsvUpload implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $file;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $file)
    {
        $this->file = $file;
    }


    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Redis::throttle('upload-csv')->allow(1)->every(20)->then(function () {
            dump('processing this file:---',$this->file);
            $data = array_map('str_getcsv',file($this->file));

            foreach($data as $row){
                Sale::updateOrCreate([
                    'InvoiceNo' => $row[0]
                ],[
                    'StockCode' => $row[1],
                    'Description' => $row[2],
                    'Quantity' => $row[3],
                    'InvoiceDate' => $row[4],
                    'UnitPrice' => $row[5],
                    'CustomerID' => $row[6],
                    'Country' => $row[7],

                ]);
            }
            dump('done processing this:---',$this->file);
            unlink($this->file);
        }, function () {

            return $this->release(10);
        });
}
}
