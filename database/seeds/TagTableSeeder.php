<?php

use Illuminate\Database\Seeder;
use App\Models\Tag;

class TagTableSeeder extends Seeder
{

    public static $gameNames = [
        'Apex',
        'DbD',
        'Fortnite',
        'Among Us',
        'VALORANT',
        'LOL',
        'R6S',
        'PUBG'
    ];

    public static $options = [
        'playStyles' => [
            'エンジョイ',
        ],
        'hardwares' => [
            'PC',
            'PS4',
            'Switch',
        ],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tags = array_merge(static::$gameNames, ...array_values(static::$options));

        if (config('app.env') === 'production') {
            foreach ($tags as $tag) {
                Tag::create(['name' => $tag]);
            }
        } else {
            factory(Tag::class, 10)->create();
        }

        
    }
}
