<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\OauthClient;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthenticationTest extends TestCase
{
    /**
     * Test validation response for required fields
     */
    public function testRequiredFieldsForRegistration()
    {
        $this->json('POST', 'api/register', ['Accept' => 'application/json'])
            ->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'Validation Error.',
                'data'    => [
                    'name'      => ['The name field is required.'],
                    'email'     => ['The email field is required.'],
                    'password'  => ['The password field is required.'],
                    'confirmed' => ['The confirmed field is required.']
                ]
            ]);
    }

    /*
     * Test the password and confirmed matching validation
     */
    public function testConfirmedPassword()
    {
        $registration = [
            'name'      => 'John Doe',
            'email'     => 'doe@email.com',
            'password'  => '123456',
            'confirmed' => 'asdfg'
        ];

        $this->json('POST', 'api/register', $registration, ['Accept' => 'application/json'])
            ->assertStatus(422)
            ->assertJson([
                'message' => 'Validation Error.',
                'data'    => [
                    'confirmed' => ['The confirmed and password must match.']
                ]
            ]);
    }

    /*
     * Test creating a successful registration
     */
    public function testSuccessfulRegistration()
    {
        $registration = [
            'name'      => 'John Doe',
            'email'     => 'doe@email.com',
            'password'  => '123456',
            'confirmed' => '123456'
        ];

        $this->json('POST', 'api/register', $registration, ['Accept' => 'application/json'])
            ->assertStatus(201)
            ->assertJson(['success' => true])
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'token',
                    'name',
                    'email'
                ]
            ]);
    }

    /**
     * Test login validations
     */
    public function testLoginMustEnterRequiredFields()
    {
        $this->json('POST', 'oauth/token')
            ->assertStatus(400)
            ->assertJson([
                'error'             => 'unsupported_grant_type',
                'error_description' => 'The authorization grant type is not ' .
                                       'supported by the authorization server.',
                'hint'              => 'Check that all required parameters ' .
                                       'have been provided',
                'message'           => 'The authorization grant type is not ' .
                                       'supported by the authorization server.'
            ]);
    }

    /**
     * Test successful Oauth login
     */
    public function testSuccessfulOauthLogin()
    {
        // Register the user
        $registration = [
            'name'      => 'John Doe',
            'email'     => 'doe@email.com',
            'password'  => '123456',
            'confirmed' => '123456'
        ];
        // Test user was successfully registered
        $this->json('POST', 'api/register', $registration, ['Accept' => 'application/json'])
            ->assertStatus(201)
            ->assertJson(['success' => true])
        ;

        // Passports default password_grant client
        $oauth_client_id = env('PASSPORT_CLIENT_ID');
        $outh_client = OauthClient::findOrFail($oauth_client_id);
        // Oauth login data
        $login = [
            'username'      => $registration['email'],
            'password'      => $registration['password'],
            'grant_type'    => 'password',
            'client_id'     => $oauth_client_id,
            'client_secret' => $outh_client->secret
        ];
        // Authenticate the login and get the access_token and refresh_token
        $this->json('POST', 'oauth/token', $login, ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJson(['token_type' => 'Bearer'])
            ->assertJsonStructure([
                'token_type',
                'expires_in',
                'access_token',
                'refresh_token'
            ]);
    }
}
