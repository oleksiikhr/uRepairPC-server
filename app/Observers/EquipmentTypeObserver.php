<?php

namespace App\Observers;

use App\EquipmentType;
use App\Events\EquipmentTypes\ECreate;
use App\Events\EquipmentTypes\EDelete;
use App\Events\EquipmentTypes\EUpdate;

class EquipmentTypeObserver
{
    /**
     * Handle the equipment type "created" event.
     *
     * @param  \App\EquipmentType  $equipmentType
     * @return void
     */
    public function created(EquipmentType $equipmentType)
    {
        event(new ECreate($equipmentType));
    }

    /**
     * Handle the equipment type "updated" event.
     *
     * @param  \App\EquipmentType  $equipmentType
     * @return void
     */
    public function updated(EquipmentType $equipmentType)
    {
        event(new EUpdate($equipmentType->id, $equipmentType));
    }

    /**
     * Handle the equipment type "deleted" event.
     *
     * @param  \App\EquipmentType  $equipmentType
     * @return void
     */
    public function deleted(EquipmentType $equipmentType)
    {
        event(new EDelete($equipmentType));
    }

    /**
     * Handle the equipment type "restored" event.
     *
     * @param  \App\EquipmentType  $equipmentType
     * @return void
     */
    public function restored(EquipmentType $equipmentType)
    {
        //
    }

    /**
     * Handle the equipment type "force deleted" event.
     *
     * @param  \App\EquipmentType  $equipmentType
     * @return void
     */
    public function forceDeleted(EquipmentType $equipmentType)
    {
        //
    }
}
