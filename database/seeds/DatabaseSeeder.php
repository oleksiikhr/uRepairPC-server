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
        $this->call(SettingsTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(RolesAndPermissionsSeeder::class);

        // Equipments
        if (config('app.env') === 'local') {
            $this->call(EquipmentTypesTableSeeder::class);
            $this->call(EquipmentManufacturersTableSeeder::class);
            $this->call(EquipmentModelsTableSeeder::class);
            $this->call(EquipmentsTableSeeder::class);
        }
    }
}
