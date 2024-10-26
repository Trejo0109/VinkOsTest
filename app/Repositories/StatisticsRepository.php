<?php

namespace App\Repositories;

use App\Models\Statistics;
use App\Services\StatisticsService;
use Carbon\Carbon;

class StatisticsRepository
{
    public static function insert(array $data)
    {
        $data = StatisticsService::format($data);

        Statistics::insert($data);
    }
}
