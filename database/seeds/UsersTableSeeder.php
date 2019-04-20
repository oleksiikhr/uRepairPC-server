<?php

use App\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'first_name' => 'Oliver',
            'last_name' => 'Noah',
            'email' => 'admin@example.com',
            'password' => '$2y$10$wUVz4ckveO.3O4Qvbuik/.fleI13a/VxQmeSEbPwaqZ8GbdLedmNC', // admin123
        ]);

        $user->assignRole(__('roles_and_permissions.roles.admins'));

        if (config('app.env') === 'local') {
            factory(App\User::class, 70)
                ->create()
                ->each(function ($user) {
                    $user->assignRole(__('roles_and_permissions.roles.users'));
                });
        }
    }
}
