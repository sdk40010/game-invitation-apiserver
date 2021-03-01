<?php

use Illuminate\Database\Seeder;
use App\Models\Comment;
use App\Models\Invitation;
use App\Models\User;

class CommentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $invitations = Invitation::all();
        $users = User::all();

        $invitations->each(function ($invitation) use ($users) {
            for ($i = 0; $i < rand(0, 10); $i++) {
                $invitation->comments()->save(
                    factory(Comment::class)
                        ->make()
                        ->user()
                        ->associate($users->random())
                );
            }
        });
        
    }
}
