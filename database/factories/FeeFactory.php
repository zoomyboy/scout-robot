<?php

$factory->define(\App\Fee::class, function(Faker\Generator $faker) {
    return [
        'title' => $faker->words(3, true),
        'nami_id' => $faker->numberBetween(100, 200)
    ];
});
