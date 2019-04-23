<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RequestStatus extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'color',
        'description',
        'default',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'default' => 'boolean',
    ];

    /**
     * @return int
     */
    public static function clearDefaultValues() {
        return self::where('default', true)->update(['default' => false]);
    }
}
