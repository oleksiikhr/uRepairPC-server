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
            ['name' => 'users.view', 'action_name' => 'Перегядати', 'section_name' => 'Користувачі'],
            ['name' => 'users.edit', 'action_name' => 'Редагувати', 'section_name' => 'Користувачі'],
            ['name' => 'users.create', 'action_name' => 'Створювати', 'section_name' => 'Користувачі'],
            ['name' => 'users.delete', 'action_name' => 'Видаляти', 'section_name' => 'Користувачі'],

            // Profile
            ['name' => 'profile.edit', 'action_name' => 'Редагувати', 'section_name' => 'Профіль'],

            // Groups
            ['name' => 'groups.view', 'action_name' => 'Перегядати', 'section_name' => 'Групи'],
            ['name' => 'groups.manage', 'action_name' => 'Керувати', 'section_name' => 'Групи'],

            // Equipments
            ['name' => 'equipments.view', 'action_name' => 'Перегядати', 'section_name' => 'Обладнання'],
            ['name' => 'equipments.edit', 'action_name' => 'Редагувати', 'section_name' => 'Обладнання'],
            ['name' => 'equipments.create', 'action_name' => 'Створювати', 'section_name' => 'Обладнання'],
            ['name' => 'equipments.delete', 'action_name' => 'Видаляти', 'section_name' => 'Обладнання'],

            // Equipment Files
            ['name' => 'equipments.files.view', 'action_name' => 'Перегядати', 'section_name' => 'Обладнання - файли'],
            ['name' => 'equipments.files.download', 'action_name' => 'Завантажувати', 'section_name' => 'Обладнання - файли'],
            ['name' => 'equipments.files.edit', 'action_name' => 'Редагувати', 'section_name' => 'Обладнання - файли'],
            ['name' => 'equipments.files.create', 'action_name' => 'Створювати', 'section_name' => 'Обладнання - файли'],
            ['name' => 'equipments.files.delete', 'action_name' => 'Видаляти', 'section_name' => 'Обладнання - файли'],

            // Other
            ['name' => 'other.global_settings', 'action_name' => 'Глобальні налаштування', 'section_name' => 'Інше'],
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }

        /* | -----------------------------------------------------------------------------------
         * | Roles
         * | -----------------------------------------------------------------------------------
         */

        // Not create a Gate!
        Role::create(['name' => 'admin', 'display_name' => 'Адміністратор', 'color' => '#f56c6c'])
            ->givePermissionTo(collect($permissions)->pluck('name'));;

        Role::create(['name' => 'worker', 'display_name' => 'Робочий', 'color' => '#409eff'])
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

        Role::create(['name' => 'user', 'display_name' => 'Користувач', 'color' => '#67c23a'])
            ->givePermissionTo('profile.edit');

        /* | -----------------------------------------------------------------------------------
         * | Other
         * | -----------------------------------------------------------------------------------
         */

        User::find(1)->assignRole('admin');
    }
}
