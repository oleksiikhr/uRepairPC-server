<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pc extends Model
{
    protected $table = 'pc';

    protected $guarded = [];

    protected $dates = [
        'updated_at',
        'created_at',
        'last_seen'
    ];
}
