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

    protected static $dateFormat = 'date_format:d/m/Y H:i';

    public static function layout($headers)
    {
        foreach (self::$requiredHeaders as $header) {
            if (!in_array($header, $headers)) {
                return false;
            }
        }
        return true;
    }

    public static function visitor($data)
    {
        return Validator::make($data, [
            'email' => 'required|email',
            'fecha_envio' => self::$dateFormat,
            'fecha_open' => self::$dateFormat,
            'fecha_click' => self::$dateFormat,
        ]);
    }
}
