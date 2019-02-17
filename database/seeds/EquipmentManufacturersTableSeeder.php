<?php

use Illuminate\Database\Seeder;

class EquipmentManufacturersTableSeeder extends Seeder
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
            DB::table('equipment_manufacturers')->insert([
                'name' => $name,
            ]);
        }
    }
}
