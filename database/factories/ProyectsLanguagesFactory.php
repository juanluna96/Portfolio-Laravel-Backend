<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Language;
use App\Proyect;
use App\proyects_languages;
use Faker\Generator as Faker;

$factory->define(proyects_languages::class, function (Faker $faker) {
    return [
        'title' => $faker->title(),
        'description' => $faker->paragraph(20),
        'proyect_id' => Proyect::all()->random()->id,
        'language_id' => Language::all()->random()->id
    ];
});
