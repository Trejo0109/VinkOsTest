<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogError extends Model
{
    protected $table = 'errores';

    protected $guarded = [
        'id',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'errors' => 'array', 
    ];
}
