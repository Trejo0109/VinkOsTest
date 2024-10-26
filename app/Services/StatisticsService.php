<?php

namespace App\Services;

use Carbon\Carbon;

class StatisticsService
{
    public static function format(array $data)
    {
        $now = Carbon::now()->toDateTimeString();
        foreach ($data as &$visit) {
            $visit['fecha_envio'] = Carbon::createFromFormat('d/m/Y H:i', $visit['fecha_click'])->format('Y-m-d H:i:s');
            $visit['fecha_open'] = Carbon::createFromFormat('d/m/Y H:i', $visit['fecha_click'])->format('Y-m-d H:i:s');
            $visit['fecha_click'] = Carbon::createFromFormat('d/m/Y H:i', $visit['fecha_click'])->format('Y-m-d H:i:s');
            $visit['created_at'] = $now;
            $visit['updated_at'] = $now;
        }
        return $data;
    }
}
