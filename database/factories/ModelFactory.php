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

$factory->define(\App\Payment::class, function(Faker\Generator $faker) {
	if (!\App\Subscription::get()->count()) {
		abort(404, 'Du solltest einen SubscriptionSeder für Payments ausführen');
	}

	return [
 		'subscription_id' => \App\Subscription::get()->random()->id,
		'nr' => $faker->numberBetween(date('Y')-5, date('Y')),
		'status_id' => \App\Status::get()->random()->id
	];
});

$factory->define(\App\Subscription::class, function(Faker\Generator $faker) {
	return [
		'amount' => $faker->numberBetween(1000, 5000),
		'title' => $faker->words(3, true),
		'fee_id' => \App\Fee::get()->random()->id
	];
});
