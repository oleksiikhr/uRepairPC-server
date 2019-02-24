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
