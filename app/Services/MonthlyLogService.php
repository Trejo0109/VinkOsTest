<?php

namespace App\Services;

use App\Models\MonthlyLog;
use Carbon\Carbon;

class MonthlyLogService {

    public static function file(){
        $date = Carbon::now()->format('Y-m');
        
        $log = MonthlyLog::where('date',$date)->first();

        if(empty($log)){
            MonthlyLog::create([
                'date' => $date,
                'processed_files' => 1,
                'processed_records' => 0,
            ]);
        }else{
            $log->processed_files += 1;
            $log->save(); 
        }
    }

    public static function record(){
        $date = Carbon::now()->format('Y-m');
        
        $log = MonthlyLog::where('date',$date)->first();

        if(empty($log)){
            MonthlyLog::create([
                'date' => $date,
                'processed_files' => 0,
                'processed_records' => 1,
            ]);
        }else{
            $log->processed_records += 1;
            $log->save(); 
        }
    }

}
