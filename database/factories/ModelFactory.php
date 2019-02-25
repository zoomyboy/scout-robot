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

$factory->define(\App\Member::class, function(Faker\Generator $faker) {
	if (\App\Gender::where('is_null', false)->get()->count() == 0) {abort(404, 'Error in Factory: No Genders');}
	if (\App\Country::get()->count() == 0) {abort(404, 'Error in Factory: No Countries');}
	if (\App\Region::where('is_null', false)->get()->count() == 0) {abort(404, 'Error in Factory: No Regions');}
	if (\App\Confession::get()->count() == 0) {abort(404, 'Error in Factory: No Confessions');}
	if (\App\Way::get()->count() == 0) {abort(404, 'Error in Factory: No Ways');}
	if (\App\Nationality::get()->count() == 0) {abort(404, 'Error in Factory: No Nationalities');}

	return [
		'firstname' => $faker->firstname,
		'lastname' => $faker->lastname,
		'nickname' => $faker->name,
		'phone' => $faker->regexify('/\+49 [0-9]{3} [0-9]{5,7}/'),
		'fax' => $faker->regexify('/\+49 [0-9]{3} [0-9]{5,7}/'),
		'business_phone' => $faker->regexify('/\+49 [0-9]{3} [0-9]{5,7}/'),
		'mobile' => $faker->regexify('/\+49 [0-9]{3} [0-9]{5,7}/'),
		'gender_id' => \App\Gender::where('is_null', false)->get()->random()->id,
		'country_id' => \App\Country::get()->random()->id,
		'other_country' => $faker->country,
		'region_id' => \App\Region::where('is_null', false)->get()->random()->id,
		'confession_id' => \App\Confession::get()->random()->id,
		'nationality_id' => \App\Nationality::get()->random()->id,
		'way_id' => \App\Way::get()->random()->id,
		'birthday' => $faker->date,
		'further_address' => $faker->streetAddress,
		'joined_at' => $faker->date,
		'keepdata' => $faker->boolean,
		'sendnewspaper' => $faker->boolean,
		'address' => $faker->streetAddress,
		'zip' => $faker->postcode,
		'city' => $faker->city,
		'email' => $faker->email,
		'email_parents' => $faker->email,
		'active' => true,
		'nami_id' => $faker->regexify('/[0-9]{8}/'),
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
