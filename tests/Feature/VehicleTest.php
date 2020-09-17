<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Vehicle;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class VehicleTest extends TestCase
{

    /**
     * Test validation response for required fields
     */
    public function testCantCreateWithoutValidatingVehicleFields()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user, 'api');

        $this->json('POST', 'api/vehicles', ['Accept' => 'application/json'])
            ->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'Validation Error.',
                'data'    => [
                    'year'  => ['The year field is required.'],
                    'make'  => ['The make field is required.'],
                    'model' => ['The model field is required.'],
                    'vin'   => ['The vin field is required.']
                ]
            ])
        ;
    }

    /**
     * Test a vehicle was created successfully
     */
    public function testCanCreateVehicle()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user, 'api');

        $vehicle = [
            'year'  => '2004',
            'make'  => 'Lotus',
            'model' => 'Esprit',
            'vin'   => 'SCCDC0826XHA15727'
        ];

        $this->json('POST', 'api/vehicles', $vehicle, ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Vehicle created successfully.',
                'data'    => array(
                    'year'  => '2004',
                    'make'  => 'Lotus',
                    'model' => 'Esprit',
                    'vin'   => 'SCCDC0826XHA15727'
                )
            ])
        ;
    }

    /**
     * Test vehicles get listed
     */
    public function testCanListVehicles()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user, 'api');

        // Create two vehicles using the vehicle factory
        $vehicle1 = factory(Vehicle::class)->create();
        $vehicle2 = factory(Vehicle::class)->create();

        // Retreieve and verify the vehicles
        $this->json('GET', 'api/vehicles', ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Vehicles retrieved successfully.',
                'data'    => [
                    [
                        'id'    => $vehicle1->id,
                        'year'  => $vehicle1->year,
                        'make'  => $vehicle1->make,
                        'model' => $vehicle1->model,
                        'vin'   => $vehicle1->vin
                    ],
                    [
                        'id'    => $vehicle2->id,
                        'year'  => $vehicle2->year,
                        'make'  => $vehicle2->make,
                        'model' => $vehicle2->model,
                        'vin'   => $vehicle2->vin
                    ]
                ]
            ])
        ;
    }

    /**
     * Test reading a particular vehicle
     */
    public function testCanShowParticularVehicle()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user, 'api');

        // Create a vehicle using the vehicle factory
        $vehicle = factory(Vehicle::class)->create();

        // Retreieve and verify the vehicle
        // TODO: validate the actual vehicle with ->assertJsonFrament
        $this->json('GET', 'api/vehicles/'. $vehicle->id, ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Vehicle retrieved successfully.',
            ])
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'id',
                    'year',
                    'make',
                    'model',
                    'vin',
                    'active',
                    'created_at',
                    'updated_at',
                    'deleted_at'
                ]
            ])
        ;
    }

    /**
     * Test updating a particular vehicle
     */
    public function testCanUpdateVehicle()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user, 'api');

        // Create a vehicles using the vehicle factory
        $vehicle1 = factory(Vehicle::class)->create();

        // Retreieve and verify the vehicles
        $this->json('GET', 'api/vehicles', ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Vehicles retrieved successfully.',
                'data'    => [
                    [
                        'id'    => $vehicle1->id,
                        'year'  => $vehicle1->year,
                        'make'  => $vehicle1->make,
                        'model' => $vehicle1->model,
                        'vin'   => $vehicle1->vin
                    ]
                ]
            ])
        ;

        // Update the vehicle
        $vehicle = [
            'year'  => '2004',
            'make'  => 'Lotus',
            'model' => 'Esprit',
            'vin'   => 'SCCDC0826XHA15727'
        ];

        $this->json('PUT', 'api/vehicles/' . $vehicle1->id, $vehicle, ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Vehicle updated successfully.',
                'data'    => array(
                    'year'  => '2004',
                    'make'  => 'Lotus',
                    'model' => 'Esprit',
                    'vin'   => 'SCCDC0826XHA15727'
                )
            ])
        ;
    }

    /**
     * Test can delete a vehicle
     */
    public function testCanDeleteVehicle()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user, 'api');

        // Create a vehicles using the vehicle factory
        $vehicle = factory(Vehicle::class)->create();

        // Retreieve and verify the vehicle
        // TODO: validate the actual vehicle with ->assertJsonFrament
        $this->json('GET', 'api/vehicles/'. $vehicle->id, ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Vehicle retrieved successfully.',
            ])
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'id',
                    'year',
                    'make',
                    'model',
                    'vin',
                    'active',
                    'created_at',
                    'updated_at',
                    'deleted_at'
                ]
            ])
        ;

        $this->json('DELETE', 'api/vehicles/' . $vehicle->id, ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Vehicle deleted successfully.'
            ])
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'id',
                    'year',
                    'make',
                    'model',
                    'vin',
                    'active',
                    'created_at',
                    'updated_at',
                    'deleted_at'
                ]
            ])
        ;

        // Try to retreive the vehicle again, which should not be found.
        $this->json('GET', 'api/vehicles/'. $vehicle->id, ['Accept' => 'application/json'])
            ->assertStatus(404)
        ;
    }
}
