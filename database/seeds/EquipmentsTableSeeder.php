<?php

use Illuminate\Database\Seeder;

class EquipmentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $arr = [
            ['serial' => '11111', 'inventory' => '22222', 'manufacturer_id' => 1, 'type_id' => 1, 'model_id' => 1]
        ];

        foreach ($arr as $item) {
            DB::table('equipments')->insert([
                'serial_number' => $item['serial'],
                'inventory_number' => $item['inventory'],
                'manufacturer_id' => $item['manufacturer_id'],
                'type_id' => $item['type_id'],
                'model_id' => $item['model_id'],
                'updated_at' => \Carbon\Carbon::now(),
                'created_at' => \Carbon\Carbon::now(),
            ]);
        }
    }
}
