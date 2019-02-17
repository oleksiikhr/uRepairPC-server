<?php

use Illuminate\Database\Seeder;

class EquipmentModelsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $arr = [
            ['name' => 'Модель 1', 'type_id' => 1, 'manufacturer_id' => 1],
        ];

        foreach ($arr as $item) {
            DB::table('equipment_models')->insert([
                'name' => $item['name'],
                'type_id' => $item['type_id'],
                'manufacturer_id' => $item['manufacturer_id'],
            ]);
        }
    }
}
