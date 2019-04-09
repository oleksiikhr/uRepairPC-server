<?php

namespace App;

use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    /** @var array */
    const ALLOW_COLUMNS_SEARCH = [
        'id',
        'name',
        'display_name',
        'guard_name',
        'default',
        'updated_at',
        'created_at',
    ];

    /** @var array */
    const ALLOW_COLUMNS_SORT = [
        'id',
        'name',
        'display_name',
        'guard_name',
        'default',
        'updated_at',
        'created_at',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'display_name',
        'color',
        'default',
    ];
}
