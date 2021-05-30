<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Image;
use App\Proyect;
use Faker\Generator as Faker;

$factory->define(Image::class, function (Faker $faker) {
    return [
        'url_image' => $faker->image(),
        'proyect_id' => Proyect::all()->random()->id,
    ];
});
