<?php

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
        'subscription_id' => \App\Subscription::get()->random()->id,
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

