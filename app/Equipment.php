<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
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
