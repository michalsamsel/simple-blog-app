<?php

namespace Tests\Unit;

use Hash;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UserLoginTest extends TestCase
{
    use RefreshDatabase;

    /** 
     * Test sucessful login of the user
     * 
     * @return void
     */

    public function testSucessfulLogin()
    {
        //Create user
        $data = [
            'name' => 'Test User',
            'email' => 'test@email.com',
            'password' => Hash::make('Password1!'),
        ];
        User::create($data);

        //Credentials for loggin in
        $credentials  = [
            'email' => 'test@email.com',
            'password' => 'Password1!'
        ];

        //User should be loged in
        $response = $this->postJson('/api/login', $credentials);
        $response->assertStatus(200);
    }

    /** 
     * Test failed login of the user
     * 
     * @return void
     */
    public function testFailedLogin()
    {
        //Create user
        $data = [
            'name' => 'Test User',
            'email' => 'test@email.com',
            'password' => Hash::make('Password1!'),
        ];
        User::create($data);

        //Credentials for loggin in with wrong email
        $credentials  = [
            'email' => 'false@email.com',
            'password' => 'Password1!'
        ];
        $response = $this->postJson('/api/login', $credentials);
        $response->assertStatus(401);

        //Credentials for loggin in with wrong password
        $credentials  = [
            'email' => 'test@email.com',
            'password' => 'False1!',
        ];
        $response = $this->postJson('/api/login', $credentials);
        $response->assertStatus(401);
    }
}
