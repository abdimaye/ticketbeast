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

use Carbon\Carbon;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Concert::class, function(Faker\Generator $faker) {
	return [
	    'title' => 'Example band',
	    'subtitle' => 'with fake openers',
	    'date' => Carbon::parse('+2 weeks'),
	    'ticket_price' => 2000,
	    'venue' => 'Example venue',
	    'venue_address' => '123 Example Lane',
	    'city' => 'Fakeville',
	    'state' => 'ON',
	    'zip' => '17916',
	    'additional_information' => 'Sample additional info.'
	];
});