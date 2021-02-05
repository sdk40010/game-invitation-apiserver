<?php

use App\Models\Invitation;
use Illuminate\Database\Seeder;

class InvitationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dataSet = [
            [
                'uuid' => 'd345b38e-5763-ef02-e8cb-2d235561257f',
                'user_id' => 1,
                'title' => '募集1',
                'description' => '',
                'date' => '2020-01-01',
                'start_time'=> '20:00',
                'end_time' => '22:00',
                'capacity' => 3,
                'img_url' => '',
            ]
        ];

        foreach ($dataSet as $data) {
            Invitation::create($data);
        }
    }
}
