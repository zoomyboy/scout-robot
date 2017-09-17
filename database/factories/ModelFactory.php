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
$factory->define(\App\User::class, function (Faker\Generator $faker) {
    static $password;

	if (\App\Usergroup::get()->count() == 0) {
		throw new \Exception('Error in factory: usergroup for user_factory not found - please call a usergroup_seeder before');
	}

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
		'remember_token' => str_random(10),
		'usergroup_id' => \App\Usergroup::get()->random()->id
    ];
});

$factory->define(\App\Usergroup::class, function(Faker\Generator $faker) {
	return [
		'title' => $faker->word
	];
});
