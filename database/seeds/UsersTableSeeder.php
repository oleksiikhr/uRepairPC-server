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
            'first_name' => 'Dante',
            'last_name' => 'Basso',
            'email' => 'admin@example.com',
            'password' => '$2y$10$wUVz4ckveO.3O4Qvbuik/.fleI13a/VxQmeSEbPwaqZ8GbdLedmNC', // admin123
        ]);

        $user->assignRole('admin');

        if (config('app.env') === 'local') {
            factory(App\User::class, 70)
                ->create()
                ->each(function ($user) {
                    $user->assignRole('user');
                });
        }
    }
}
