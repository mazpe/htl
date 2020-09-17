<?php

use App\Models\Order;
use App\Models\Technician;
use App\Models\Vehicle;
use App\Models\Key;
use Illuminate\Database\Seeder;

class VehiclesKeysTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // VEHICLES // KEYS
        // Create 10 vehicles and create a random amount (from 3 to 5) keys per
        // vehicle
        factory(Vehicle::class, 10)->create()->each(function ($vehicle) {
            $vehicle->keys()->createMany(
                factory(Key::Class, rand(3,5))->make()->toArray()
            );
        });
    }
}
