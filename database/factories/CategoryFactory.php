<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Area;
use App\Category;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

$factory->define(Category::class, function (Faker $faker) {
    return [
        'name' => $faker->word(),
        'color_text' => $faker->hexColor,
        'color_bg' => $faker->hexColor,
        'logo' => 'FaLaravel',
        'image' => $faker->image('public/storage/categories', 56, 49, null, false),
        'imageBig' => $faker->image('public/storage/categories', 341, 296, null, false),
        'area_id' => Area::all()->random()->id,
    ];
});
