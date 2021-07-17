<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Invitation;
use App\Models\User;
use Faker\Generator as Faker;
use Illuminate\Support\Carbon;

$factory->define(Invitation::class, function (Faker $faker) {
    $count = User::count();

    $titles = collect(TagTableSeeder::$gameNames)
        ->map(function ($gameName) { return $gameName.'　募集'; });

    $title = config('app.env') === 'production'
        ? $faker->randomElement($titles)
        : $faker->word;

    $startTime = $faker->dateTimeBetween(
        Carbon::now()->addMonth(12),
        Carbon::now()->addMonth(14)
    );
    $endTime = $faker->dateTimeBetween(
        $startTime,
        Carbon::parse($startTime)->addHours(4)
    );

    return [
        'id' => $faker->uuid,
        'user_id' => $faker->randomElement(range(1, $count)),
        'title' => $title,
        'description' => $faker->text,
        'start_time' => $startTime,
        'end_time' => $endTime,
        'capacity' => $faker->randomElement(range(1, 10))
    ];
});

