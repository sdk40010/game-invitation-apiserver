<?php

use Illuminate\Database\Seeder;
use App\Models\Comment;
use App\Models\Reply;
use App\Models\User;

class ReplyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $comments = Comment::all();
        $users = User::all();

        // $comments->each(function ($comment) use ($users) {
        //     for ($i = 0; $i < rand(0, 10); $i++) {
        //         $comment->replies()->save(
        //             factory(Reply::class)
        //                 ->make()
        //                 ->user()
        //                 ->associate($users->random())
        //         );
        //     }
        // });

        $comments->each(function ($comment) use ($users) {
            for ($i = 0; $i < 5; $i++) {
                $comment->replies()->save(
                    factory(Reply::class)
                        ->make()
                        ->user()
                        ->associate($users->random())
                );
            }
        });
    }
}
