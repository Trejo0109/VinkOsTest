<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
    protected $table = 'visitantes';

    protected $guarded = [
        'id',
        'created_at',
        'updated_at'
    ];
}
