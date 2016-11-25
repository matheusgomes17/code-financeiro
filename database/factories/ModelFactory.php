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

$factory->define(codeFin\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});



$factory->state(\codeFin\User::class, 'admin', function (Faker\Generator $faker){
	return [
		'role' => \codeFin\User::ROLE_ADMIN
	];
});


$factory->define(codeFin\Models\Bank::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'logo' => md5(time()).'.jpg',        
    ];
});