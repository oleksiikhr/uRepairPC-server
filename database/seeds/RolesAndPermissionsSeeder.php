<?php

use App\Role;
use App\Permission;
use App\Enums\Permissions;
use Illuminate\Database\Seeder;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        /* | -----------------------------------------------------------------------------------
         * | Permissions
         * | -----------------------------------------------------------------------------------
         */

        $permissions = [
            // Profile
            ['name' => Permissions::PROFILE_EDIT, 'section_key' => 'profile'],

            // Users
            ['name' => Permissions::USERS_VIEW, 'section_key' => 'users'],
            ['name' => Permissions::USERS_EDIT, 'section_key' => 'users'],
            ['name' => Permissions::USERS_CREATE, 'section_key' => 'users'],
            ['name' => Permissions::USERS_DELETE, 'section_key' => 'users'],

            // Groups
            ['name' => Permissions::ROLES_VIEW, 'section_key' => 'roles'],
            ['name' => Permissions::ROLES_MANAGE, 'section_key' => 'roles'],

            // Equipments
            ['name' => Permissions::EQUIPMENTS_VIEW, 'section_key' => 'equipments'],
            ['name' => Permissions::EQUIPMENTS_EDIT, 'section_key' => 'equipments'],
            ['name' => Permissions::EQUIPMENTS_CREATE, 'section_key' => 'equipments'],
            ['name' => Permissions::EQUIPMENTS_DELETE, 'section_key' => 'equipments'],

            // Equipment Files
            ['name' => Permissions::EQUIPMENTS_FILES_VIEW, 'section_key' => 'equipment_files'],
            ['name' => Permissions::EQUIPMENTS_FILES_DOWNLOAD, 'section_key' => 'equipment_files'],
            ['name' => Permissions::EQUIPMENTS_FILES_EDIT, 'section_key' => 'equipment_files'],
            ['name' => Permissions::EQUIPMENTS_FILES_CREATE, 'section_key' => 'equipment_files'],
            ['name' => Permissions::EQUIPMENTS_FILES_DELETE, 'section_key' => 'equipment_files'],

            // Other
            ['name' => Permissions::OTHER_GLOBAL_SETTINGS, 'section_key' => 'other'],
        ];

        foreach ($permissions as $permission) {
            $arrName = explode('.', $permission['name']);

            Permission::create([
                'name' => $permission['name'],
                'display_name' => __('roles_and_permissions.actions.' . end($arrName)),
                'section_name' => __('roles_and_permissions.sections.' . $permission['section_key']),
            ]);
        }

        /* | -----------------------------------------------------------------------------------
         * | Roles
         * | -----------------------------------------------------------------------------------
         */

        // Not create a Gate!
        Role::create([
            'name' => 'admin',
            'display_name' => __('roles_and_permissions.roles.admins'),
            'color' => '#f56c6c',
        ])
            ->givePermissionTo(collect($permissions)->pluck('name'));;

        Role::create([
            'name' => 'worker',
            'display_name' => __('roles_and_permissions.roles.workers'),
            'color' => '#409eff',
        ])
            ->givePermissionTo([
                // Profile
                Permissions::PROFILE_EDIT,

                // Users
                Permissions::USERS_VIEW,

                // Equipments
                Permissions::EQUIPMENTS_VIEW,
                Permissions::EQUIPMENTS_EDIT,
                Permissions::EQUIPMENTS_CREATE,
                Permissions::EQUIPMENTS_DELETE,

                // Equipment Files
                Permissions::EQUIPMENTS_FILES_VIEW,
                Permissions::EQUIPMENTS_FILES_DOWNLOAD,
                Permissions::EQUIPMENTS_FILES_EDIT,
                Permissions::EQUIPMENTS_FILES_CREATE,
                Permissions::EQUIPMENTS_FILES_DELETE,
            ]);

        Role::create([
            'name' => 'user',
            'display_name' => __('roles_and_permissions.roles.users'),
            'color' => '#67c23a',
            'default' => true,
        ])
            ->givePermissionTo([
                // Profile
                Permissions::PROFILE_EDIT,

                // Users
                Permissions::USERS_VIEW,
            ]);
    }
}
