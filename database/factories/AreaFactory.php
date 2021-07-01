<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Area;
use Faker\Generator as Faker;

$factory->define(Area::class, function (Faker $faker) {
    return [
        'title' => $faker->word(),
        'name' => '{"es":"' . $faker->word() . '","en":"' . $faker->word() . '"}',
        'logo' => 'FaLaravel'
    ];
});
