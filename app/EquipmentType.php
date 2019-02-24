<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EquipmentType extends Model
{
    /* | ---------------------------------------------------------------
     * | Relationships
     * | ---------------------------------------------------------------
     */

    public function models() {
        return $this->hasMany(EquipmentModel::class);
    }

    public function equipments() {
        return $this->hasMany(Equipment::class);
    }
}
