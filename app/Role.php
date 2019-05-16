<?php

namespace App;

use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    /** @var array */
    const ALLOW_COLUMNS_SEARCH = [
        'id',
        'name',
        'guard_name',
        'default',
        'updated_at',
        'created_at',
    ];

    /** @var array */
    const ALLOW_COLUMNS_SORT = [
        'id',
        'name',
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
        'color',
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
     * Convert the model instance to an array.
     *
     * @return array
     */
    public function toArray()
    {
        if (! empty($this->permissions)) {
            if (count($this->permissions) > 0) {
                $this->setAttribute('permissions_grouped', $this->permissions->groupBy('section_name'));
            } else {
                $this->setAttribute('permissions_grouped', (object) []);
            }
        }

        return parent::toArray();
    }
}
