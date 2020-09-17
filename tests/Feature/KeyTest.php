<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Key;
use App\Models\Vehicle;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class KeyTest extends TestCase
{

    /**
     * Test validation response for required fields
     */
    public function testCantCreateWithoutValidatingKeyFields()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user, 'api');

        $this->json('POST', 'api/keys', ['Accept' => 'application/json'])
            ->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'Validation Error.',
                'data'    => [
                    'vehicle_id'  => ['The vehicle id field is required.'],
                    'item_name'  => ['The item name field is required.'],
                    'description' => ['The description field is required.'],
                    'price'   => ['The price field is required.']
                ]
            ])
        ;
    }

    //TODO Test creating a key with an invalid price

    /**
     * Test a key was created successfully
     */
    public function testCanCreateKey()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user, 'api');

        // Create a vehicles using the vehicle factory
        $vehicle = factory(Vehicle::class)->create();

        $key = [
            'vehicle_id'  => $vehicle->id,
            'item_name'   => 'Key Name',
            'description' => 'Test Key',
            'price'       => '1.83'
        ];

        $this->json('POST', 'api/keys', $key, ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Key created successfully.',
                'data'    => array(
                    'vehicle_id'  => $key['vehicle_id'],
                    'item_name'  => $key['item_name'],
                    'description' => $key['description'],
                    'price'   => $key['price']
                )
            ])
        ;
    }

    /**
     * Test keys get listed
     */
    public function testCanListKeys()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user, 'api');

        // Create a vehicle for our keys
        $vehicle = factory(Vehicle::class)->create();

        // Create two keys using the key factory passing the vehicle we created
        $key1 = factory(Key::class)->create(['vehicle_id' => $vehicle->id]);
        $key2 = factory(Key::class)->create(['vehicle_id' => $vehicle->id]);

        // Retreieve and verify the keys
        $this->json('GET', 'api/keys', ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Keys retrieved successfully.',
                'data'    => [
                    [
                        'vehicle_id'  => $vehicle->id,
                        'item_name'   => $key1->item_name,
                        'description' => $key1->description,
                        'price'       => $key1->price
                    ],
                    [
                        'vehicle_id'  => $vehicle->id,
                        'item_name'   => $key2->item_name,
                        'description' => $key2->description,
                        'price'       => $key2->price
                    ]
                ]
            ])
        ;
    }

    /**
     * Test reading a particular key
     */
    public function testCanShowParticularKey()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user, 'api');

        // Create a vehicle for our keys
        $vehicle = factory(Vehicle::class)->create();

        // Create a key using the key factory passing the vehicle we created
        $key1 = factory(Key::class)->create(['vehicle_id' => $vehicle->id]);

        // Retreieve and verify the key
        // TODO: validate the actual key with ->assertJsonFrament
        $this->json('GET', 'api/keys/'. $key1->id, ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Key retrieved successfully.',
            ])
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'id',
                    'item_name',
                    'description',
                    'price',
                    'active',
                    'created_at',
                    'updated_at',
                    'deleted_at'
                ]
            ])
        ;
    }

    /**
     * Test updating a particular key
     */
    public function testCanUpdateKey()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user, 'api');

        // Create a vehicle for our keys
        $vehicle = factory(Vehicle::class)->create();

        // Create a key using the key factory passing the vehicle we created
        $key1 = factory(Key::class)->create(['vehicle_id' => $vehicle->id]);

        // Retreieve and verify the keys
        $this->json('GET', 'api/keys', ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Keys retrieved successfully.',
                'data'    => [
                    [
                        'vehicle_id'  => $vehicle->id,
                        'item_name'   => $key1->item_name,
                        'description' => $key1->description,
                        'price'       => $key1->price
                    ]
                ]
            ])
        ;

        // Update the key
        $key = [
            'vehicle_id'  => $vehicle->id,
            'item_name'   => 'New Key',
            'description' => 'Updated Key',
            'price'       => '2.50'
        ];

        $this->json('PUT', 'api/keys/' . $key1->id, $key, ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Key updated successfully.',
                'data'    => array(
                    'item_name'   => $key['item_name'],
                    'description' => $key['description'],
                    'price'       => $key['price']
                )
            ])
        ;
    }

    /**
     * Test can delete a key
     */
    public function testCanDeleteKey()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user, 'api');

        // Create a vehicle for our keys
        $vehicle = factory(Vehicle::class)->create();

        // Create a key using the key factory passing the vehicle we created
        $key = factory(Key::class)->create(['vehicle_id' => $vehicle->id]);

        // Retreieve and verify the key
        // TODO: validate the actual key with ->assertJsonFrament
        $this->json('GET', 'api/keys/'. $key->id, ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Key retrieved successfully.',
            ])
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'id',
                    'item_name',
                    'description',
                    'price',
                    'active',
                    'created_at',
                    'updated_at',
                    'deleted_at'
                ]
            ])
        ;

        $this->json('DELETE', 'api/keys/' . $key->id, ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Key deleted successfully.'
            ])
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'id',
                    'item_name',
                    'description',
                    'price',
                    'active',
                    'created_at',
                    'updated_at',
                    'deleted_at'
                ]
            ])
        ;

        // Try to retreive the key again, which should not be found.
        $this->json('GET', 'api/keys/'. $key->id, ['Accept' => 'application/json'])
            ->assertStatus(404)
        ;
    }
}
