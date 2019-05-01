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

            // Equipments - Config
            ['name' => Permissions::EQUIPMENTS_CONFIG_VIEW, 'section_key' => 'equipments_config'],
            ['name' => Permissions::EQUIPMENTS_CONFIG_EDIT, 'section_key' => 'equipments_config'],
            ['name' => Permissions::EQUIPMENTS_CONFIG_CREATE, 'section_key' => 'equipments_config'],
            ['name' => Permissions::EQUIPMENTS_CONFIG_DELETE, 'section_key' => 'equipments_config'],

            // Equipment Files
            ['name' => Permissions::EQUIPMENTS_FILES_VIEW, 'section_key' => 'equipment_files'],
            ['name' => Permissions::EQUIPMENTS_FILES_DOWNLOAD, 'section_key' => 'equipment_files'],
            ['name' => Permissions::EQUIPMENTS_FILES_EDIT, 'section_key' => 'equipment_files'],
            ['name' => Permissions::EQUIPMENTS_FILES_CREATE, 'section_key' => 'equipment_files'],
            ['name' => Permissions::EQUIPMENTS_FILES_DELETE, 'section_key' => 'equipment_files'],

            // Requests
            ['name' => Permissions::REQUESTS_VIEW, 'section_key' => 'requests'],
            ['name' => Permissions::REQUESTS_EDIT, 'section_key' => 'requests'],
            ['name' => Permissions::REQUESTS_CREATE, 'section_key' => 'requests'],
            ['name' => Permissions::REQUESTS_DELETE, 'section_key' => 'requests'],

            // Requests - Config
            ['name' => Permissions::REQUESTS_CONFIG_VIEW, 'section_key' => 'requests_config'],
            ['name' => Permissions::REQUESTS_CONFIG_EDIT, 'section_key' => 'requests_config'],
            ['name' => Permissions::REQUESTS_CONFIG_CREATE, 'section_key' => 'requests_config'],
            ['name' => Permissions::REQUESTS_CONFIG_DELETE, 'section_key' => 'requests_config'],

            // Global
            ['name' => Permissions::GLOBAL_SETTINGS, 'section_key' => 'global'],
            ['name' => Permissions::GLOBAL_MANIFEST, 'section_key' => 'global']
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
            'name' => __('roles_and_permissions.roles.admins'),
            'color' => '#f56c6c',
        ])
            ->givePermissionTo(collect($permissions)->pluck('name'));;

        Role::create([
            'name' => __('roles_and_permissions.roles.workers'),
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

                // Equipments - Config
                Permissions::EQUIPMENTS_CONFIG_VIEW,
                Permissions::EQUIPMENTS_CONFIG_EDIT,
                Permissions::EQUIPMENTS_CONFIG_CREATE,
                Permissions::EQUIPMENTS_CONFIG_DELETE,

                // Equipment Files
                Permissions::EQUIPMENTS_FILES_VIEW,
                Permissions::EQUIPMENTS_FILES_DOWNLOAD,
                Permissions::EQUIPMENTS_FILES_EDIT,
                Permissions::EQUIPMENTS_FILES_CREATE,
                Permissions::EQUIPMENTS_FILES_DELETE,

                // Requests
                Permissions::REQUESTS_VIEW,
                Permissions::REQUESTS_EDIT,
                Permissions::REQUESTS_CREATE,
                Permissions::REQUESTS_DELETE,

                // Requests - Config
                Permissions::REQUESTS_CONFIG_VIEW,
                Permissions::REQUESTS_CONFIG_EDIT,
                Permissions::REQUESTS_CONFIG_CREATE,
                Permissions::REQUESTS_CONFIG_DELETE,
            ]);

        Role::create([
            'name' => __('roles_and_permissions.roles.users'),
            'color' => '#67c23a',
            'default' => true,
        ])
            ->givePermissionTo([
                // Profile
                Permissions::PROFILE_EDIT,

                // Users
                Permissions::USERS_VIEW,

                // Equipments
                Permissions::EQUIPMENTS_VIEW,

                // Requests
                Permissions::REQUESTS_VIEW,
            ]);
    }
}
