<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $frontend = [
            ['name' => 'name', 'value' => config('app.name')],
            ['name' => 'logo_home', 'value' => null],
            ['name' => 'logo_header', 'value' => null],
        ];

        foreach ($frontend as $record) {
            DB::table('settings')->insert([
                'name' => 'frontend_' . $record['name'],
                'value' => $record['value'],
                'updated_at' => \Carbon\Carbon::now(),
                'created_at' => \Carbon\Carbon::now(),
            ]);
        }
    }
}
