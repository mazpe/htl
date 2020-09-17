<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Models\Technician;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

/**
 * Define the factory to create Technicians
 */

$factory->define(Technician::class, function (Faker $faker) {
    return [
        'first_name'   => $faker->firstName,
        'last_name'    => $faker->lastName,
        'truck_number' => $faker->numberBetween($min = 1000, $max = 9999)
    ];
});
