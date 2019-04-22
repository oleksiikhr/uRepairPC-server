<?php

use App\RequestStatus;
use Illuminate\Database\Seeder;

class RequestStatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = [
            ['name' => 'Відкрита', 'color' => '#ff8522', 'default' => true],
            ['name' => 'Відкладена', 'color' => '#ffc926', 'default' => false],
            ['name' => 'Вирішена', 'color' => '#5cb85c', 'default' => false],
            ['name' => 'Закрита', 'color' => '#8e9eb3', 'default' => false],
        ];

        foreach ($items as $item) {
            RequestStatus::create([
                'name' => $item['name'],
                'color' => $item['color'],
                'default' => $item['default']
            ]);
        }
    }
}
