<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Models\Order;
use App\Models\Technician;
use App\Models\Vehicle;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

/**
 * Define the factory to create Orders
 */

$factory->define(Order::class, function (Faker $faker) {
    $vehicle = Vehicle::inRandomOrder()->first();
    $key = $vehicle->keys()->inRandomOrder()->first();
    $technician = Technician::inRandomOrder()->first();
    // If for some reason we cant find a vehicle, key or technician exit
    if (!$vehicle || !$key || !$technician) {
        exit();
    }

    return [
        'vehicle_id'   => $vehicle->id,
        'key_id'    => $key->id,
        'technician_id' => $technician->id,
        'status'    => 1,
        'note'  => $faker->sentence
    ];

});
