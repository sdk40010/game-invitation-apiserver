<?php

use App\User;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
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
                'name' => 'user1',
                'email' => 'user1@example.com',
                'icon_url' => '/img/user1icon.jpeg',
            ],
            [
                'name' => 'user2',
                'email' => 'user2@example.com',
                'icon_url' => '/img/user2icon.jpeg',
            ],
        ];

        foreach ($dataSet as $data) {
            User::create($data);
        }
    }
}
