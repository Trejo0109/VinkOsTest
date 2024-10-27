<?php

namespace App\Services;

use App\Models\LogError;
use Carbon\Carbon;

class ErrorService {
    public static function layout($file){
        $error = LogError::create([
            'file' => $file,
            'type' => 'layout',
            'errors' => [$file => 'El layout del archivo no corresponde al solicitado.']
        ]);
        return $error;
    }

    public static function record($file, $errors, $key){
        $error = LogError::create([
            'file' => $file,
            'type' => 'record',
            'errors' => ["Line $key" => $errors],
        ]);
        return $error;
    }

}
