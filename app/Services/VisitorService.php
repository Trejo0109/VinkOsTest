<?php

namespace App\Services;

use App\Models\Visitor;
use Carbon\Carbon;

class VisitorService
{
    public static function format(array $visit)
    {
        $fechaOpen =  Carbon::createFromFormat('d/m/Y H:i', $visit['fecha_open']);
        return [
            'email' => $visit['email'],
            'fecha_primera_visita' => $fechaOpen,
            'fecha_ultima_visita' => $fechaOpen,
            'visitas_totales' => $visit['opens'],
            'visitas_mes_actual' => $fechaOpen->isCurrentMonth() ? $visit['opens'] : 0,
            'visitas_anio_actual' => $fechaOpen->isCurrentYear() ? $visit['opens'] : 0,
        ];
    }

    public static function logVisit($record, $newVisit)
    {
        $fechaOpen =  Carbon::createFromFormat('d/m/Y H:i', $newVisit['fecha_open']);
        $record->fecha_primera_visita = min($record->fecha_primera_visita, $fechaOpen);
        $record->fecha_ultima_visita = max($record->fecha_ultima_visita, $fechaOpen);
        $record->visitas_totales +=  $newVisit['opens'];
        $record->visitas_mes_actual +=  $fechaOpen->isCurrentMonth() ? $newVisit['opens'] : 0;
        $record->visitas_anio_actual +=  $fechaOpen->isCurrentYear() ? $newVisit['opens'] : 0;
        return $record->save();
    }
}
