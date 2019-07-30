<?php

namespace App\Observers;

use App\EquipmentModel;
use App\Events\EquipmentModels\ECreate;
use App\Events\EquipmentModels\EDelete;
use App\Events\EquipmentModels\EUpdate;

class EquipmentModelObserver
{
    /**
     * Handle the equipment model "created" event.
     *
     * @param  \App\EquipmentModel  $equipmentModel
     * @return void
     */
    public function created(EquipmentModel $equipmentModel)
    {
        event(new ECreate($equipmentModel));
    }

    /**
     * Handle the equipment model "updated" event.
     *
     * @param  \App\EquipmentModel  $equipmentModel
     * @return void
     */
    public function updated(EquipmentModel $equipmentModel)
    {
        event(new EUpdate($equipmentModel->id, $equipmentModel));
    }

    /**
     * Handle the equipment model "deleted" event.
     *
     * @param  \App\EquipmentModel  $equipmentModel
     * @return void
     */
    public function deleted(EquipmentModel $equipmentModel)
    {
        event(new EDelete($equipmentModel));
    }
}
