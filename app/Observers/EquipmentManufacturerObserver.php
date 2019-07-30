<?php

namespace App\Observers;

use App\EquipmentManufacturer;
use App\Events\EquipmentManufacturers\ECreate;
use App\Events\EquipmentManufacturers\EDelete;
use App\Events\EquipmentManufacturers\EUpdate;

class EquipmentManufacturerObserver
{
    /**
     * Handle the equipment manufacturer "created" event.
     *
     * @param  \App\EquipmentManufacturer  $equipmentManufacturer
     * @return void
     */
    public function created(EquipmentManufacturer $equipmentManufacturer)
    {
        event(new ECreate($equipmentManufacturer));
    }

    /**
     * Handle the equipment manufacturer "updated" event.
     *
     * @param  \App\EquipmentManufacturer  $equipmentManufacturer
     * @return void
     */
    public function updated(EquipmentManufacturer $equipmentManufacturer)
    {
        event(new EUpdate($equipmentManufacturer->id, $equipmentManufacturer));
    }

    /**
     * Handle the equipment manufacturer "deleted" event.
     *
     * @param  \App\EquipmentManufacturer  $equipmentManufacturer
     * @return void
     */
    public function deleted(EquipmentManufacturer $equipmentManufacturer)
    {
        event(new EDelete($equipmentManufacturer));
    }
}
