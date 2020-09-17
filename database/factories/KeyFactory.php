<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Models\Key;
use App\Models\Vehicle;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

/**
 * Define the factory to create Keys
 */

// Get a random vehicle
$vehicle = Vehicle::inRandomOrder()->first();

$factory->define(Key::class, function (Faker $faker)  {
    return [
        'item_name'   => $faker->company,
        'description' => $faker->sentence,
        'price'       =>
            $faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 100)
    ];
});
