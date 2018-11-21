<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $guarded = [];

    protected $dates = [
        'updated_at',
        'created_at',
        'last_seen'
    ];
}
