<?php

namespace App\Observers;

use App\Events\Roles\EDelete;
use App\Role;

class RoleObserver
{
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
