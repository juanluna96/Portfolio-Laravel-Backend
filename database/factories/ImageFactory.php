<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Image;
use App\Proyect;
use Faker\Generator as Faker;
use Illuminate\Support\Carbon;

$factory->define(Image::class, function (Faker $faker) {
    $date = Carbon::now()->timestamp;
    return [
        'name' => $date . '.jpg',
        'size' => $faker->numerify('###############'),
        'url_image' => $faker->image('public/storage/proyects', 640, 480, null, false),
        'proyect_id' => Proyect::all()->random()->id,
    ];
});
