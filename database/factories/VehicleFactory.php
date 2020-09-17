<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Models\Vehicle;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

/**
 * Define the factory to create Vehicles
 */
$factory->define(Vehicle::class, function (Faker $faker) {
    // Add Faker Provider for Cars
    $faker->addProvider(new \Faker\Provider\Fakecar($faker));
    $v = $faker->vehicleArray();

    return [
        'year'  => $faker->biasedNumberBetween(1998, 2017, 'sqrt'),
        'make'  => $v['brand'],
        'model' => $v['model'],
        'vin'   => $faker->unique()->vin
    ];
});

/**
 * Set the active or inactive states
 */
$factory->state(Vehicle::class, 'active', [
    'active' => '1',
]);
$factory->state(Vehicle::class, 'inactive', [
    'active' => '0',
]);
