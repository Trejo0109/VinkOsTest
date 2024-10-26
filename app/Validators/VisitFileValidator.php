<?php

namespace App\Validators;

use Illuminate\Support\Facades\Validator;

class VisitFileValidator
{
    protected static $requiredHeaders = [
        'email', 
        'jyv', 
        'Badmail', 
        'Baja', 
        'Fecha envio', 
        'Fecha open', 
        'Opens', 
        'Opens virales', 
        'Fecha click', 
        'Clicks', 
        'Clicks virales', 
        'Links', 
        'IPs', 
        'Navegadores', 
        'Plataformas'
    ];

    public static function layout($headers){
        foreach (self::$requiredHeaders as $header) {
            if (!in_array($header, $headers)) {
                return false; 
            }
        }
        return true; 
    }

    public static function visitor($data){
        return $validator = Validator::make($data, [
            0 => 'required|email',                     
            4 => 'date_format:d/m/Y H:i', 
        ]);
    }
}
