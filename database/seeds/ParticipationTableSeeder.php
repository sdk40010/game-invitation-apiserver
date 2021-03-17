<?php

use Illuminate\Database\Seeder;
use App\Models\Invitation;
use App\Models\User;

use Illuminate\Support\Facades\Log;

class ParticipationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $invitations = Invitation::all();
        $users = User::all()->shuffle();

        // １つの募集につき最大で10人の参加者を作成する
        // $invitations->each(function ($invitation) use($users) {
        //     $n = rand(0, 10);
        //     $random = $users->random($n);
        //     for ($i = 0; $i < $n; $i++) {
        //         $invitation->participants()->attach($random[$i]);
        //     }
        // });
        
        $invitations->each(function ($invitation) use($users) {
            // 募集の作成者を最初の参加者として保存する
            $invitation->participants()->attach($invitation->user_id);

            $n = $invitation->capacity > 2 
                ? $invitation->capacity - 2
                : 0;

            
            for ($i = 0; $i < $n; $i++) {
                $user = $users->get($i);
                if ($user->id === $invitation->user_id
                ) {
                    continue;
                }
                $invitation->participants()->attach($user);
            }
        });
    }
}
