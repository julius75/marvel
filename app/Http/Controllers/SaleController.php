<?php

namespace App\Http\Controllers;

use App\Jobs\SaleCsvProcess;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;

class SaleController extends Controller
{
    public function index()
    {
        return view('welcome');
    }
    public function upload_csv_records(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt'
        ]);
        $file = file($request->file->getRealPath());
        $data = array_slice($file,1);
        $parts = (array_chunk($data,8000));

        foreach($parts as $index=>$part){
            $fileName = resource_path('pending-files/'.date('y-m-d-H-i-s').$index.'.csv');
            file_put_contents($fileName,$part);
        }
        (new Sale())->importToDb();

        session()->flash('status','import stared');

       return redirect("/");
//        if( $request->has('file') ) {
//
//            $csv    = file($request->file);
//            $chunks = array_chunk($csv,1000);
//            $header = [];
//            $batch  = Bus::batch([])->dispatch();
//
//            foreach ($chunks as $key => $chunk) {
//                $data = array_map('str_getcsv', $chunk);
//                if($key == 0){
//                    $header = $data[0];
//                    unset($data[0]);
//                }
//                $batch->add(new SaleCsvProcess($data, $header));
//            }
//            return $batch;
//        }
//        return "please upload csv file";
    }
}
