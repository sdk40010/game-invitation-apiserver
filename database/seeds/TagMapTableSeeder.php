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
        $tags = Tag::all();

        $invitations->each(function ($invitation) use($tags) {
            $n = rand(0, 10);
            $random = $tags->random($n); // ランダムかつ重複がないようにタグを取得する

            // 1つの募集につき最大で10個のタグを付ける
            for ($i = 0; $i < $n; $i++) {
                $tag = $random->get($i);
                $tag->count += 1; //　使用回数の更新
                $tag->save();
                $invitation->tags()->attach($tag->id);
            }
        });
    }
}
