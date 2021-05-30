<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Company;
use Faker\Generator as Faker;

$factory->define(Company::class, function (Faker $faker) {
    return [
        'name' => $faker->company,
        'position_es' => $faker->paragraph(30),
        'position_en' => $faker->paragraph(30),
        'image' => $faker->image()
    ];
});
