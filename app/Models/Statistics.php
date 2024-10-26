<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Statistics extends Model
{
    protected $table = 'estadisticas';

    protected $guarded = [
        'id',
        'created_at',
        'updated_at'
    ];
}
