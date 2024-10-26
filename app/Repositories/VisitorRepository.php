<?php

namespace App\Repositories;

use App\Models\Visitor;
use App\Services\VisitorService;
use Carbon\Carbon;

class VisitorRepository
{
    public static function getOrInsert($data)
    {
        foreach ($data as $visit) {
            $record = Visitor::where('email', $visit['email'])->first();
            if ($record) {
                $record = VisitorService::logVisit($record, $visit);
            } else {
                $visit = VisitorService::format($visit);
                Visitor::create($visit);
            }
        }
    }
}
