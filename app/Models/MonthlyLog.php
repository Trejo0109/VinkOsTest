<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MonthlyLog extends Model
{
    protected $guarded = [
        'id',
        'created_at',
        'updated_at'
    ];
}
