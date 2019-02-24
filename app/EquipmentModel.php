<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EquipmentModel extends Model
{
    /* | ---------------------------------------------------------------
     * | Relationships
     * | ---------------------------------------------------------------
     */

    public function equipments()
    {
        return $this->hasMany(Equipment::class);
    }

    public function type()
    {
        return $this->belongsTo(EquipmentModel::class);
    }

    public function manufacturer()
    {
        return $this->belongsTo(EquipmentManufacturer::class);
    }
}
