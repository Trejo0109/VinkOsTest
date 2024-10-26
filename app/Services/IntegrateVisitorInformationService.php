<?php

namespace App\Services;

use App\Validators\VisitFileValidator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class IntegrateVisitorInformationService
{

    public static function integrateInformation($files){

        self::sortFiles($files);

        foreach ($files as  $file) {
            $content = Storage::get($file);

            $lines = explode(PHP_EOL, $content);
            $headers = str_getcsv(array_shift($lines));

            if(!VisitFileValidator::layout($headers)){
                Log::error("{$file}: El formato del layout no es el requerido.");
                continue;
            }
            $data = [];
            foreach ($lines as $key =>$line) {
                if (trim($line) === '') continue;
                $record = str_getcsv($line);

                $validator = VisitFileValidator::visitor($record);
                if($validator->fails()){
                    Log::error($validator->errors()->all());
                }
                $data[] = $record;
            }

            //Log::alert($data);
        }
    }

    public static function sortFiles(array &$files){
        usort($files, function ($a, $b) {
            $numA = (int)filter_var(basename($a), FILTER_SANITIZE_NUMBER_INT);
            $numB = (int)filter_var(basename($b), FILTER_SANITIZE_NUMBER_INT);
        
            return $numA <=> $numB; 
        });
    }
}