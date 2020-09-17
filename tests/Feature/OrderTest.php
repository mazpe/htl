<?php

namespace Tests\Feature;

use App\Models\Key;
use App\Models\Order;
use App\Models\Technician;
use App\Models\User;
use App\Models\Vehicle;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrderTest extends TestCase
{

    /**
     * Test validation response for required fields
     */
    public function testCantCreateWithoutValidatingOrderFields()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user, 'api');

        $this->json('POST', 'api/orders', ['Accept' => 'application/json'])
            ->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'Validation Error.',
                'data'    => [
                    'vehicle_id'    => ['The vehicle id field is required.'],
                    'key_id'        => ['The key id field is required.'],
                    'technician_id' => ['The technician id field is required.'],
                    'status'        => ['The status field is required.']
                ]
            ])
        ;
    }

    /**
     * Test an order was created successfully
     */
    public function testCanCreateOrder()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user, 'api');

        // Create a vehicle with a key and a techician
        $vehicle = factory(Vehicle::class)->create();
        $key = factory(Key::class)->create(['vehicle_id' => $vehicle->id]);
        $technician = factory(Technician::class)->create();

        $order = [
            'vehicle_id'    => $vehicle->id,
            'key_id'        => $key->id,
            'technician_id' => $technician->id,
            'status'        => Order::STATUSES['PENDING'],
            'note'          => 'This is an order'
        ];

        $this->json('POST', 'api/orders', $order, ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Order created successfully.',
                'data' => array(
                    'vehicle_id'    => $vehicle->id,
                    'key_id'        => $key->id,
                    'technician_id' => $technician->id,
                    'status'        => Order::STATUSES['PENDING'],
                    'note'          => $order['note']
                )
            ])
        ;
    }

    /**
     * Test orders get listed
     */
    public function testCanListOrders()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user, 'api');

        // Create a vehicle with a key and a techician to be used in Order 1
        $vehicle1 = factory(Vehicle::class)->create();
        $key1 = factory(Key::class)->create(['vehicle_id' => $vehicle1->id]);
        $technician1 = factory(Technician::class)->create();
        $order1 = factory(Order::class)->create([
            'vehicle_id'    => $vehicle1->id,
            'key_id'        => $key1->id,
            'technician_id' => $technician1->id,
            'status'        => Order::STATUSES['PENDING']
        ]);

        // Create a vehicle with a key and a techician to be used in Order 2
        $vehicle2 = factory(Vehicle::class)->create();
        $key2 = factory(Key::class)->create(['vehicle_id' => $vehicle2->id]);
        $technician2 = factory(Technician::class)->create();
        $order2 = factory(Order::class)->create([
            'vehicle_id'    => $vehicle2->id,
            'key_id'        => $key2->id,
            'technician_id' => $technician2->id,
            'status'        => Order::STATUSES['PENDING']
        ]);

        // Retreieve and verify the orders
        $this->json('GET', 'api/orders', ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Orders retrieved successfully.',
                'data' => [
                    [
                        'vehicle_id'    => $order1->vehicle_id,
                        'key_id'        => $order1->key_id,
                        'technician_id' => $order1->technician_id
                    ],
                    [
                        'vehicle_id'    => $order2->vehicle_id,
                        'key_id'        => $order2->key_id,
                        'technician_id' => $order2->technician_id
                    ]
                ]
            ])
        ;
    }

    /**
     * Test reading a particular order
     */
    public function testCanShowParticularOrder()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user, 'api');

        // Create a vehicle with a key and a techician to be used in Order 1
        $vehicle1 = factory(Vehicle::class)->create();
        $key1 = factory(Key::class)->create(['vehicle_id' => $vehicle1->id]);
        $technician1 = factory(Technician::class)->create();
        $order1 = factory(Order::class)->create([
            'vehicle_id'    => $vehicle1->id,
            'key_id'        => $key1->id,
            'technician_id' => $technician1->id,
            'status'        => Order::STATUSES['PENDING']
        ]);

        // Retreieve and verify the order
        // TODO: validate the actual order with ->assertJsonFrament
        $this->json('GET', 'api/orders/'. $order1->id, ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Order retrieved successfully.',
            ])
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'id',
                    'vehicle_id',
                    'key_id',
                    'technician_id',
                    'status',
                    'note',
                    'created_at',
                    'updated_at',
                    'deleted_at'
                ]
            ])
        ;
    }

    /**
     * Test updating a particular order
     */
    public function testCanUpdateOrder()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user, 'api');

        // Create a vehicle with a key and a techician to be used in Order 1
        $vehicle1 = factory(Vehicle::class)->create();
        $key1 = factory(Key::class)->create(['vehicle_id' => $vehicle1->id]);
        $technician1 = factory(Technician::class)->create();
        $order1 = factory(Order::class)->create([
            'vehicle_id'    => $vehicle1->id,
            'key_id'        => $key1->id,
            'technician_id' => $technician1->id,
            'status'        => Order::STATUSES['PENDING']
        ]);

        // Retreieve and verify the orders
        $this->json('GET', 'api/orders', ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Orders retrieved successfully.',
                'data'    => [
                    [
                        'vehicle_id'    => $order1->vehicle_id,
                        'key_id'        => $order1->key_id,
                        'technician_id' => $order1->technician_id
                    ]
                ]
            ])
        ;

        // Update the order
        // Create a vehicle with a key and a techician to be used in Order 1
        $vehicle2 = factory(Vehicle::class)->create();
        $key2 = factory(Key::class)->create(['vehicle_id' => $vehicle2->id]);
        $technician2 = factory(Technician::class)->create();
        $order_update = [
            'vehicle_id'    => $vehicle2->id,
            'key_id'        => $key2->id,
            'technician_id' => $technician2->id,
            'status'        => $order1->status
        ];

        $this->json('PUT', 'api/orders/' . $order_update1->id, $order_update, ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Order updated successfully.',
                'data'    => array(
                    'vehicle_id'    => $order_update['vehicle_id'],
                    'key_id'        => $order_update['key_id'],
                    'technician_id' => $order_update['technician_id']
                )
            ])
        ;
    }

    /**
     * Test can delete a order
     */
    public function testCanDeleteOrder()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user, 'api');

        // Create a vehicle with a key and a techician to be used in Order 1
        $vehicle1 = factory(Vehicle::class)->create();
        $key1 = factory(Key::class)->create(['vehicle_id' => $vehicle1->id]);
        $technician1 = factory(Technician::class)->create();
        $order1 = factory(Order::class)->create([
            'vehicle_id'    => $vehicle1->id,
            'key_id'        => $key1->id,
            'technician_id' => $technician1->id,
            'status'        => Order::STATUSES['PENDING']
        ]);

        // Retreieve and verify the order
        // TODO: validate the actual order with ->assertJsonFrament
        $this->json('GET', 'api/orders/'. $order1->id, ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Order retrieved successfully.',
            ])
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'id',
                    'vehicle_id',
                    'key_id',
                    'technician_id',
                    'created_at',
                    'updated_at',
                    'deleted_at'
                ]
            ])
        ;

        $this->json('DELETE', 'api/orders/' . $order1->id, ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Order deleted successfully.'
            ])
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'id',
                    'vehicle_id',
                    'key_id',
                    'technician_id',
                    'created_at',
                    'updated_at',
                    'deleted_at'
                ]
            ])
        ;

        // Try to retreive the order again, which should not be found.
        $this->json('GET', 'api/orders/'. $order1->id, ['Accept' => 'application/json'])
            ->assertStatus(404)
        ;
    }
}
