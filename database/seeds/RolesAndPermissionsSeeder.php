<?php

use App\Role;
use App\Enums\Perm;
use App\RolePermission;
use Illuminate\Database\Seeder;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /*
         * ADMIN
         */
        $adminRole = Role::create([
            'name' => __('perm.roles.admins'),
            'color' => '#f56c6c',
        ]);

        $adminPermissions = Perm::getAll();

        foreach ($adminPermissions as $permission) {
            RolePermission::create([
                'role_id' => $adminRole->id,
                'name' => $permission,
            ]);
        }

        /*
         * USER
         */
        $userRole = Role::create([
            'name' => __('perm.roles.users'),
            'color' => '#67c23a',
            'default' => true,
        ]);

        $userPermissions = [
            Perm::PROFILE_EDIT, // profile
            Perm::USERS_VIEW_SECTION, // users
            Perm::USERS_VIEW_ALL,
            Perm::USERS_HIDE_EMAIL,
            Perm::USERS_HIDE_PHONE,
            Perm::EQUIPMENTS_VIEW_ALL, // equipments
            Perm::EQUIPMENTS_CONFIG_VIEW,
            Perm::REQUESTS_VIEW_SECTION, // requests
            Perm::REQUESTS_VIEW_OWN,
            Perm::REQUESTS_EDIT_OWN,
            Perm::REQUESTS_CREATE,
            Perm::REQUESTS_CONFIG_VIEW,
        ];

        foreach ($userPermissions as $permission) {
            RolePermission::create([
                'role_id' => $userRole->id,
                'name' => $permission,
            ]);
        }
    }
}
