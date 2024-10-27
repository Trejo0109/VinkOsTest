<?php

namespace App\Services;

use App\Models\Visitor;
use Carbon\Carbon;

class VisitorService
{
    public static function format(array $visit)
    {
        $fechaEnvio =  Carbon::createFromFormat('d/m/Y H:i', $visit['fecha_envio']);
        return [
            'email' => $visit['email'],
            'fecha_primera_visita' => $fechaEnvio,
            'fecha_ultima_visita' => $fechaEnvio,
            'visitas_totales' => 1,
            'visitas_mes_actual' => $fechaEnvio->isCurrentMonth() ? 1 : 0,
            'visitas_anio_actual' => $fechaEnvio->isCurrentYear() ? 1 : 0,
        ];
    }

    public static function logVisit($record, $newVisit)
    {
        $fechaEnvio =  Carbon::createFromFormat('d/m/Y H:i', $newVisit['fecha_envio']);
        $record->fecha_primera_visita = min($record->fecha_primera_visita, $fechaEnvio);
        $record->fecha_ultima_visita = max($record->fecha_ultima_visita, $fechaEnvio);
        $record->visitas_totales +=  1;
        $record->visitas_mes_actual +=  $fechaEnvio->isCurrentMonth() ? 1 : 0;
        $record->visitas_anio_actual +=  $fechaEnvio->isCurrentYear() ? 1 : 0;
        return $record;
    }
}
