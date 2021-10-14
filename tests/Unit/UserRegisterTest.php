<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserRegisterTest extends TestCase
{

    use RefreshDatabase;

    /**
     * Test successful registration of a new account.
     * 
     * @return void
     */
    public function testSucessfulRegistration()
    {
        //Data required for registration of the user
        $data = [
            'name' => 'Test User',
            'email' => 'test@email.com',
            'password' => 'Password1!',
            'password_confirmation' => 'Password1!'
        ];

        //Pass data to register api
        $response = $this->postJson('/api/register', $data);

        //User should be created
        $response->assertCreated();
    }

    /**
     * Test failed registration of a new account.
     * 
     * @return void
     */
    public function testFailedRegistration()
    {
        //Data required for registration of the user
        $data = [
            'name' => '',
            'email' => '',
            'password' => '',
            'password_confirmation' => ''
        ];

        //Pass data to register api
        $response = $this->postJson('/api/register', $data);

        //User shouldn't be created
        $response->assertStatus(400);
    }

    /**
     * Test validation rules of variable 'name'
     * Rule set: 'required|string|min:3|max:32|unique:users'
     * 
     * @return void
     */
    public function testValidationRulesOfName()
    {
        //Data required for registration of the user
        $data = [
            'name' => 'Test User',
            'email' => 'test@email.com',
            'password' => 'Password1!',
            'password_confirmation' => 'Password1!'
        ];

        //Test required rule
        $data['name'] = '';
        $response = $this->postJson('/api/register', $data);
        $response->assertStatus(400);

        //Test string rule
        $data['name'] = null;
        $response = $this->postJson('/api/register', $data);
        $response->assertStatus(400);
        $data['name'] = 0;
        $response = $this->postJson('/api/register', $data);
        $response->assertStatus(400);
        $data['name'] = false;
        $response = $this->postJson('/api/register', $data);
        $response->assertStatus(400);
        $data['name'] = true;
        $response = $this->postJson('/api/register', $data);
        $response->assertStatus(400);

        //Test min rule
        $data['name'] = str_repeat('a', 2);
        $response = $this->postJson('/api/register', $data);
        $response->assertStatus(400);

        //Test max rule
        $data['name'] = str_repeat('a', 33);
        $response = $this->postJson('/api/register', $data);
        $response->assertStatus(400);

        //Test unique rule
        $data['name'] = 'Test User';
        User::create($data); //Store user for duplication

        $data['email'] = 'test2@email.com'; //Changed email to do not trigger email validation rules
        $response = $this->postJson('/api/register', $data);
        $response->assertStatus(400);
    }

    /**
     * Test validation rules of variable 'email'
     * Rule set: 'required|email|max:255|unique:users',
     * 
     * @return void
     */
    public function testValidationRulesOfEmail()
    {
        //Data required for registration of the user
        $data = [
            'name' => 'Test User',
            'email' => 'test@email.com',
            'password' => 'Password1!',
            'password_confirmation' => 'Password1!'
        ];

        //Test required rule
        $data['email'] = '';
        $response = $this->postJson('/api/register', $data);
        $response->assertStatus(400);

        //Test string rule
        $data['email'] = null;
        $response = $this->postJson('/api/register', $data);
        $response->assertStatus(400);
        $data['email'] = 0;
        $response = $this->postJson('/api/register', $data);
        $response->assertStatus(400);
        $data['email'] = false;
        $response = $this->postJson('/api/register', $data);
        $response->assertStatus(400);
        $data['email'] = true;
        $response = $this->postJson('/api/register', $data);
        $response->assertStatus(400);
        $data['email'] = "test";
        $response = $this->postJson('/api/register', $data);
        $response->assertStatus(400);

        //Test max rule
        $data['name'] = str_repeat('a', 255) . '@email.com';
        $response = $this->postJson('/api/register', $data);
        $response->assertStatus(400);

        //Test unique rule
        $data['email'] = 'test@email.com';
        User::create($data); //Store user for duplication

        $data['email'] = 'Test User 2'; //Changed name to do not trigger name validation rules
        $response = $this->postJson('/api/register', $data);
        $response->assertStatus(400);
    }

    /**
     * Test validation rules of variable 'password'
     * Rule set: 'required|confirmed|min:6|max:16|letters|mixedCase|numbers|symbols',
     * 
     * @return void
     */
    public function testValidationRulesOfPassword()
    {
        //Data required for registration of the user
        $data = [
            'name' => 'Test User',
            'email' => 'test@email.com',
            'password' => 'Password1!',
            'password_confirmation' => 'Password1!'
        ];

        //Test required rule
        $data['password'] = '';
        $data['password_confirmation'] = $data['password'];
        $response = $this->postJson('/api/register', $data);
        $response->assertStatus(400);

        //Test confirmed rule
        $data['password'] = 'Password1!';
        $data['password_confirmation'] = 'Password2@';
        $response = $this->postJson('/api/register', $data);
        $response->assertStatus(400);

        //Test min rule
        $data['password'] = '!1Qq';
        $data['password_confirmation'] = $data['password'];
        $response = $this->postJson('/api/register', $data);
        $response->assertStatus(400);

        //Test max rule
        $data['password'] = 'Password1!Password1!';
        $data['password_confirmation'] = $data['password'];
        $response = $this->postJson('/api/register', $data);
        $response->assertStatus(400);

        //Test letters rule
        $data['password'] = '123456789!';
        $data['password_confirmation'] = $data['password'];
        $response = $this->postJson('/api/register', $data);
        $response->assertStatus(400);

        //Test mixedCase rule
        $data['password'] = 'password1!';
        $data['password_confirmation'] = $data['password'];
        $response = $this->postJson('/api/register', $data);
        $response->assertStatus(400);
        $data['password'] = 'PASSWORD1!';
        $data['password_confirmation'] = $data['password'];
        $response = $this->postJson('/api/register', $data);
        $response->assertStatus(400);

        //Test numbers rule
        $data['password'] = 'Password!';
        $data['password_confirmation'] = $data['password'];
        $response = $this->postJson('/api/register', $data);
        $response->assertStatus(400);

        //Test symbols rule
        $data['password'] = 'Password1';
        $data['password_confirmation'] = $data['password'];
        $response = $this->postJson('/api/register', $data);
        $response->assertStatus(400);
    }
}
