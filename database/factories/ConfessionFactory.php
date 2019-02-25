<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(\App\Confession::class, function (Faker\Generator $faker) {
    return [
        'title' => $faker->word,
        'nami_title' => $faker->word,
        'nami_id' => rand(100, 200)
    ];
});
