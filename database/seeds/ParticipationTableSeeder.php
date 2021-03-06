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
        
        $invitations->each(function ($invitation) use($users) {
            // 募集の作成者を最初の参加者にする
            $invitation->participants()->attach($invitation->user_id);

            $n = rand(0, $invitation->capacity - 1);
            
            for ($i = 0; $i < $n; $i++) {
                $user = $users->get($i);
                if ($user->id === $invitation->user_id) {
                    continue;
                }
                $invitation->participants()->attach($user);
            }
        });
    }
}
