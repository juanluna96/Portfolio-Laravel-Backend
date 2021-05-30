<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\CategoriesProyects;
use App\Category;
use App\Proyect;
use Faker\Generator as Faker;

$factory->define(CategoriesProyects::class, function (Faker $faker) {
    return [
        'proyect_id' => Proyect::all()->random()->id,
        'category_id' => Category::all()->random()->id,
    ];
});
