<?php

use Illuminate\Database\Seeder;

class EquipmentManufacturesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $names = ['Acer', 'Apple', 'Asus', 'Dell', 'HP', 'Lenovo', 'Samsung'];

        foreach ($names as $name) {
            DB::table('equipment_manufactures')->insert([
                'name' => $name,
            ]);
        }
    }
}
