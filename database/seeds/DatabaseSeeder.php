<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);

        // Equipments
        $this->call(EquipmentTypesTableSeeder::class);
        $this->call(EquipmentManufacturersTableSeeder::class);
        $this->call(EquipmentModelsTableSeeder::class);
        $this->call(EquipmentsTableSeeder::class);
    }
}
