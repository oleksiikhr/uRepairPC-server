<?php

namespace App\Observers;

use App\Role;
use App\Events\Roles\ECreate;
use App\Events\Roles\EDelete;
use App\Events\Roles\EUpdate;

class RoleObserver
{
    /**
     * Handle the role "created" event.
     *
     * @param  \App\Role  $role
     * @return void
     */
    public function created(Role $role)
    {
        event(new ECreate($role));
    }

    /**
     * Handle the role "updated" event.
     *
     * @param  \App\Role  $role
     * @return void
     */
    public function updated(Role $role)
    {
        event(new EUpdate($role->id, $role));
    }

    /**
     * Handle the role "deleted" event.
     *
     * @param  \App\Role  $role
     * @return void
     */
    public function deleted(Role $role)
    {
        event(new EDelete($role));
    }
}
