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

// define a particular state so we don't have to explicitly set this value
// in our tests
$factory->state(App\Concert::class, 'published' ,function(Faker\Generator $faker) {
	return [
		'published_at' => Carbon::parse('-1 week'),
	];
});

$factory->state(App\Concert::class, 'unpublished' ,function(Faker\Generator $faker) {
	return [
		'published_at' => null,
	];
});

$factory->define(App\Ticket::class, function(Faker\Generator $faker) {
	return [
	    'concert_id' => function() { // lazy load
	    	return factory(App\Concert::class)->create()->id;
	    },
	];
});