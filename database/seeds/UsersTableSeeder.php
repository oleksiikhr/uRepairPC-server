<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'first_name' => 'Admin',
            'last_name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => '$2y$10$wUVz4ckveO.3O4Qvbuik/.fleI13a/VxQmeSEbPwaqZ8GbdLedmNC', // admin123
            'role' => 'admin',
            'updated_at' => \Carbon\Carbon::now(),
            'created_at' => \Carbon\Carbon::now(),
        ]);

        if (config('app.env') === 'local') {
            factory(App\User::class, 70)->create();
        }
    }
}
