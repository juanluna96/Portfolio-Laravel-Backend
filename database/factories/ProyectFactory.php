<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use App\Proyect;
use App\Category;
use Faker\Generator as Faker;

$factory->define(Proyect::class, function (Faker $faker) {
    return [
        'title' => $faker->title(),
        'url' => $faker->title(),
        'user_id' => User::all()->random()->id,
        'category_id' => Category::all()->random()->id,
    ];
});
