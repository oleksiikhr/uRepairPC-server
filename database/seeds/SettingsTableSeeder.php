<?php

use App\Settings;
use Illuminate\Database\Seeder;

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
            ['name' => 'meta_title', 'value' => config('app.name'), 'type' => 'string'],
            ['name' => 'app_name', 'value' => null, 'type' => 'string'],
            ['name' => 'logo_auth', 'value' => null, 'type' => 'file'],
            ['name' => 'logo_header', 'value' => null, 'type' => 'file'],
            ['name' => 'favicon', 'value' => null, 'type' => 'file'],
            ['name' => 'name_and_logo', 'value' => false, 'type' => 'bool'],
        ];

        foreach ($frontend as $record) {
            Settings::create([
                'name' => 'frontend_' . $record['name'],
                'value' => $record['value'],
                'type' => $record['type'],
            ]);
        }
    }
}
