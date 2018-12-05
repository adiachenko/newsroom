<?php

use App\Article;
use App\Comment;
use App\User;
use Faker\Generator as Faker;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Comment::class, function (Faker $faker) {
    return [
        'author_id' => factory(User::class)->state(User::WRITER),
        'article_id' => factory(Article::class),
        'body' => $faker->text,
    ];
});
