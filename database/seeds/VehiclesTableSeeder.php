<?php

use App\Models\Vehicle;
use Illuminate\Database\Seeder;

class VehiclesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Add 10 vehicles to the database
        factory(Vehicle::class, 10)->create();

        // Add 2 inactive vehicles to the database
        factory(Vehicle::class, 2)->states('inactive')->create();
    }
}
