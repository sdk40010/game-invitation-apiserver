<?php

use Illuminate\Database\Seeder;
use App\Models\Invitation;
use App\Models\Tag;

class TagMapTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $invitations = Invitation::all();
        $count = Tag::count();

        $invitations->each(function ($invitation) use ($count) {
            // 1つの募集につき最大で10個のタグを付ける
            for ($i = 0; $i < rand(0, 10); $i++) {
                $invitation->tags()->attach(rand(1, $count));
            }
        });
    }
}
