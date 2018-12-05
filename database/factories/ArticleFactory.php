<?php

use App\Article;
use App\User;
use Faker\Generator as Faker;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Article::class, function (Faker $faker) {
    return [
        'user_id' => factory(User::class)->state(User::WRITER),
        'title' => $faker->sentence,
        'body' => $faker->text,
    ];
});
