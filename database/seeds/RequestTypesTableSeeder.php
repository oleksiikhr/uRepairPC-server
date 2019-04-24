<?php

use App\RequestType;
use Illuminate\Database\Seeder;

class RequestTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = [
            [
                'name' => 'Інцидент',
                'description' => 'Заявки, пов\'язані з поломкою або несправністю',
                'default' => true,
            ],
            [
                'name' => 'Обслуговування',
                'description' => 'Заявки, пов\'язані з плановим обслуговуванням',
                'default' => false,
            ],
        ];

        foreach ($items as $item) {
            RequestType::create($item);
        }
    }
}
