<?php

use App\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
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
            // Users
            ['name' => 'users.view', 'section_name' => 'Користувачі'],
            ['name' => 'users.edit', 'section_name' => 'Користувачі'],
            ['name' => 'users.create', 'section_name' => 'Користувачі'],
            ['name' => 'users.delete', 'section_name' => 'Користувачі'],

            // Profile
            ['name' => 'profile.edit', 'section_name' => 'Профіль'],

            // Groups
            ['name' => 'groups.view', 'section_name' => 'Групи'],
            ['name' => 'groups.manage', 'section_name' => 'Групи'],

            // Equipments
            ['name' => 'equipments.view', 'section_name' => 'Обладнання'],
            ['name' => 'equipments.edit', 'section_name' => 'Обладнання'],
            ['name' => 'equipments.create', 'section_name' => 'Обладнання'],
            ['name' => 'equipments.delete', 'section_name' => 'Обладнання'],

            // Equipment Files
            ['name' => 'equipments.files.view', 'section_name' => 'Обладнання - файли'],
            ['name' => 'equipments.files.download', 'section_name' => 'Обладнання - файли'],
            ['name' => 'equipments.files.edit', 'section_name' => 'Обладнання - файли'],
            ['name' => 'equipments.files.create', 'section_name' => 'Обладнання - файли'],
            ['name' => 'equipments.files.delete', 'section_name' => 'Обладнання - файли'],

            // Other
            ['name' => 'other.global_settings', 'section_name' => 'Інше'],
        ];

        foreach ($permissions as $permission) {
            $arrName = explode('.', $permission['name']);

            Permission::create([
                'name' => $permission['name'],
                'display_name' => __('roles_and_permissions.actions.' . end($arrName)),
                'section_name' => $permission['section_name'],
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
            'color' => '#f56c6c'
        ])
            ->givePermissionTo(collect($permissions)->pluck('name'));;

        Role::create([
            'name' => 'worker',
            'display_name' => __('roles_and_permissions.roles.workers'),
            'color' => '#409eff'
        ])
            ->givePermissionTo([
                // Equipments
                'equipments.view',
                'equipments.edit',
                'equipments.create',
                'equipments.delete',

                // Equipment Files
                'equipments.files.view',
                'equipments.files.download',
                'equipments.files.edit',
                'equipments.files.create',
                'equipments.files.delete',
            ]);

        Role::create([
            'name' => 'user',
            'display_name' => __('roles_and_permissions.roles.users'),
            'color' => '#67c23a'
        ])
            ->givePermissionTo('profile.edit');

        /* | -----------------------------------------------------------------------------------
         * | Other
         * | -----------------------------------------------------------------------------------
         */

        User::find(1)->assignRole('admin');
    }
}
