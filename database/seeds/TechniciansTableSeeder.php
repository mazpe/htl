<?php

use App\Models\Technician;
use Illuminate\Database\Seeder;

class TechniciansTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Add 10 vehicles to the database
        factory(Technician::class, 10)->create();
    }
}
