<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class FollowingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $self = User::where('name', 'sdk 40010')->first();
        // [$half1, $half2] = User::where('id', '!=', $self->id)->take(10)->get()->split(2);

        // // フォロー
        // $half1->each(function ($user) use ($self) {
        //     $self->followings()->attach($user);
        // });

        // // フォロワー
        // $half2->each(function ($user) use ($self) {
        //     $user->followings()->attach($self);
        // });

        $users = User::all();
        
        // フォロー
        $users->each(function ($user) {
            $others = User::where('id', '!=', $user->id)->get()->shuffle();
            $n = rand(0, $others->count());

            for ($i = 0; $i < $n; $i++ ) {
                $user->followings()->attach($others[$i]);
            }
        });
    }
}
