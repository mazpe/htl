<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Technician;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TechnicianTest extends TestCase
{

    /**
     * Test validation response for required fields
     */
    public function testCantCreateWithoutValidatingTechnicianFields()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user, 'api');

        $this->json('POST', 'api/technicians', ['Accept' => 'application/json'])
            ->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'Validation Error.',
                'data'    => [
                    'first_name'  => ['The first name field is required.'],
                    'last_name'  => ['The last name field is required.'],
                    'truck_number' => ['The truck number field is required.']
                ]
            ])
        ;
    }

    /**
     * Test a technician was created successfully
     */
    public function testCanCreateTechnician()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user, 'api');

        $technician = [
            'first_name'   => 'First Name',
            'last_name'    => 'Last Name',
            'truck_number' => '1234'
        ];

        $this->json('POST', 'api/technicians', $technician, ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Technician created successfully.',
                'data'    => array(
                    'first_name'   => $technician['first_name'],
                    'last_name'    => $technician['last_name'],
                    'truck_number' => $technician['truck_number']
                )
            ])
        ;
    }

    /**
     * Test technicians get listed
     */
    public function testCanListTechnicians()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user, 'api');

        // Create two technicians using the technician factory passing the vehicle we created
        $technician1 = factory(Technician::class)->create();
        $technician2 = factory(Technician::class)->create();

        // Retreieve and verify the technicians
        $this->json('GET', 'api/technicians', ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Technicians retrieved successfully.',
                'data'    => [
                    [
                        'first_name'  => $technician1->first_name,
                        'last_name'   => $technician1->last_name,
                        'truck_number' => $technician1->truck_number
                    ],
                    [
                        'first_name'  => $technician2->first_name,
                        'last_name'   => $technician2->last_name,
                        'truck_number' => $technician2->truck_number
                    ]
                ]
            ])
        ;
    }

    /**
     * Test reading a particular technician
     */
    public function testCanShowParticularTechnician()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user, 'api');

        // Create a technician using the technician factory passing the vehicle we created
        $technician1 = factory(Technician::class)->create();

        // Retreieve and verify the technician
        // TODO: validate the actual technician with ->assertJsonFrament
        $this->json('GET', 'api/technicians/'. $technician1->id, ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Technician retrieved successfully.',
            ])
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'id',
                    'last_name',
                    'truck_number',
                    'active',
                    'created_at',
                    'updated_at',
                    'deleted_at'
                ]
            ])
        ;
    }

    /**
     * Test updating a particular technician
     */
    public function testCanUpdateTechnician()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user, 'api');

        // Create a technician using the technician factory passing the vehicle we created
        $technician1 = factory(Technician::class)->create();

        // Retreieve and verify the technicians
        $this->json('GET', 'api/technicians', ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Technicians retrieved successfully.',
                'data'    => [
                    [
                        'first_name'   => $technician1->first_name,
                        'last_name'    => $technician1->last_name,
                        'truck_number' => $technician1->truck_number
                    ]
                ]
            ])
        ;

        // Update the technician
        $technician = [
            'first_name'  => 'First Name',
            'last_name'   => 'New Technician',
            'truck_number' => 'Updated Technician',
            'price'       => '2.50'
        ];

        $this->json('PUT', 'api/technicians/' . $technician1->id, $technician, ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Technician updated successfully.',
                'data'    => array(
                    'first_name'   => $technician['first_name'],
                    'last_name'    => $technician['last_name'],
                    'truck_number' => $technician['truck_number']
                )
            ])
        ;
    }

    /**
     * Test can delete a technician
     */
    public function testCanDeleteTechnician()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user, 'api');

        // Create a technician using the technician factory passing the vehicle we created
        $technician = factory(Technician::class)->create();

        // Retreieve and verify the technician
        // TODO: validate the actual technician with ->assertJsonFrament
        $this->json('GET', 'api/technicians/'. $technician->id, ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Technician retrieved successfully.',
            ])
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'id',
                    'last_name',
                    'truck_number',
                    'active',
                    'created_at',
                    'updated_at',
                    'deleted_at'
                ]
            ])
        ;

        $this->json('DELETE', 'api/technicians/' . $technician->id, ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Technician deleted successfully.'
            ])
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'id',
                    'last_name',
                    'truck_number',
                    'active',
                    'created_at',
                    'updated_at',
                    'deleted_at'
                ]
            ])
        ;

        // Try to retreive the technician again, which should not be found.
        $this->json('GET', 'api/technicians/'. $technician->id, ['Accept' => 'application/json'])
            ->assertStatus(404)
        ;
    }
}
