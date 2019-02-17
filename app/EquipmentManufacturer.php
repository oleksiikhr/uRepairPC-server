<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EquipmentManufacturer extends Model
{
    /* | ---------------------------------------------------------------
     * | Relationships
     * | ---------------------------------------------------------------
     */

    public function equipments()
    {
        return $this->hasMany(Equipment::class);
    }

    public function models()
    {
        return $this->hasMany(EquipmentModel::class);
    }
}
