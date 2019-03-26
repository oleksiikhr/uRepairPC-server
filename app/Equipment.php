<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'equipments';

    /** @var array */
    const ALLOW_COLUMNS_SEARCH = [
        'id',
        'serial_number',
        'inventory_number',
        'type_name',
        'manufacturer_name',
        'model_name',
        'updated_at',
        'created_at',
    ];

    /** @var array */
    const ALLOW_COLUMNS_SORT = [
        'id',
        'serial_number',
        'inventory_number',
        'type_name',
        'manufacturer_name',
        'model_name',
        'updated_at',
        'created_at',
    ];

    /* | ---------------------------------------------------------------
     * | Relationships
     * | ---------------------------------------------------------------
     */

    public function manufacturer()
    {
        return $this->belongsTo(EquipmentManufacturer::class);
    }

    public function type()
    {
        return $this->belongsTo(EquipmentType::class);
    }

    public function model()
    {
        return $this->belongsTo(EquipmentModel::class);
    }
}
