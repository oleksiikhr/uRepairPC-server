<?php

use Illuminate\Database\Seeder;

class EquipmentTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $names = ['Ноутбуки', 'Планшети', 'Монітори'];

        foreach ($names as $name) {
            DB::table('equipment_types')->insert([
                'name' => $name,
            ]);
        }
    }
}
