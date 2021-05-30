<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use App\Proyect;
use App\Category;
use App\Company;
use Faker\Generator as Faker;

$factory->define(Proyect::class, function (Faker $faker) {
    return [
        'title' => $faker->title(),
        'url' => $faker->title(),
        'user_id' => User::all()->random()->id,
        'company_id' => Company::all()->random()->id,
    ];
});
