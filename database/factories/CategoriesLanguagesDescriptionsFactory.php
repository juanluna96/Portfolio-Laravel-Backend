<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\categories_languages_descriptions;
use App\Category;
use App\Language;
use Faker\Generator as Faker;

$factory->define(categories_languages_descriptions::class, function (Faker $faker) {
    return [
        'description' => $faker->paragraph(20),
        'category_id' => Category::all()->random()->id,
        'language_id' => Language::all()->random()->id,
    ];
});
