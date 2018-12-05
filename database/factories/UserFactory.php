<?php

use App\User;
use Faker\Generator as Faker;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => bcrypt('secret'),
        'api_token' => str_random(60),
        'role' => User::READER,
    ];
});

$factory->state(User::class, User::WRITER, [
    'role' => User::WRITER,
]);

$factory->state(User::class, User::READER, [
    'role' => User::READER,
]);
