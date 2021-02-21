<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Invitation;
use Faker\Generator as Faker;
use Illuminate\Support\Carbon;

$factory->define(Invitation::class, function (Faker $faker) {
    $startTime = $faker->dateTimeBetween(
        Carbon::now()->addMonth(2),
        Carbon::now()->addMonth(4)
    );
    $endTime = $faker->dateTimeBetween(
        $startTime,
        Carbon::parse($startTime)->addHours(4)
    );

    return [
        'id' => $faker->uuid,
        'user_id' => $faker->randomElement(range(1, 2)),
        'title' => $faker->word,
        'description' => $faker->text,
        'start_time' => $startTime,
        'end_time' => $endTime,
        'capacity' => $faker->randomElement(range(1, 10))
    ];
});

