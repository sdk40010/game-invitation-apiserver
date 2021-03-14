<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class FriendshipTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $self = User::where('name', 'sdk 40010')->first();
        [$half1, $half2] = User::where('id', '!=', $self->id)->take(4)->get()->split(2);

        // 自分->相手
        $half1->each(function ($user) use ($self) {
            $self->friends()->attach($user);
        });

        // 相手->自分
        $half2->each(function ($user) use ($self) {
            $user->friends()->attach($self);
        });

    }
}
