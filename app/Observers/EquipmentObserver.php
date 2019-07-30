<?php

namespace App\Observers;

use App\Equipment;
use App\Events\Equipments\ECreate;
use App\Events\Equipments\EUpdate;
use App\Events\Equipments\EDelete;

class EquipmentObserver
{
    /**
     * Handle the equipment "created" event.
     *
     * @param  \App\Equipment  $equipment
     * @return void
     */
    public function created(Equipment $equipment)
    {
        event(new ECreate($equipment));
    }

    /**
     * Handle the equipment "updated" event.
     *
     * @param  \App\Equipment  $equipment
     * @return void
     */
    public function updated(Equipment $equipment)
    {
        event(new EUpdate($equipment->id, $equipment));
    }

    /**
     * Handle the equipment "deleted" event.
     *
     * @param  \App\Equipment  $equipment
     * @return void
     */
    public function deleted(Equipment $equipment)
    {
        event(new EDelete($equipment));
    }
}
