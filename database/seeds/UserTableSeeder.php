<?php

use App\Models\User;
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
        // 実際のユーザー
        // $dataSet = [
        //     [
        //         'firebase_uid' => 'Yz52RbGrCQehcunzoen2qwGax8x1',
        //         'name' => 'sdk 40010',
        //         'email' => 'sdk40010@gmail.com',
        //         'icon_url' => 'https://lh5.googleusercontent.com/-R0q8LvVP8OM/AAAAAAAAAAI/AAAAAAAAAAA/AMZuuclsexLfibj4wYaZyAUXUATPcdLf4w/s96-c/photo.jpg',
        //     ],
        //     [
        //         'firebase_uid' => 'fGfjszHRZtcp1EtQal2jIiB0Pex1',
        //         'name' => 'tomo1 kurono',
        //         'email' => 'kuronotomo1@gmail.com',
        //         'icon_url' => 'https://lh6.googleusercontent.com/-LCpuIZHk7yg/AAAAAAAAAAI/AAAAAAAAAAA/AMZuucnqUEXY8wg7_6mgIPv1u90wnCIL5A/s96-c/photo.jpg',
        //     ],
        // ];

        // foreach ($dataSet as $data) {
        //     User::create($data);
        // }

        // 架空のユーザー
        factory(User::class, 10)->create();
    }
}
