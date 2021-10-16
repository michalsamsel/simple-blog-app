<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UserLogoutTest extends TestCase
{
    use RefreshDatabase;

    /** 
     * Test sucessful logout of the user
     * 
     * @return void
     */

    public function testSucessfulLogout()
    {
        //Create user session
        Sanctum::actingAs(User::factory()->create(), ['*']);

        //User should be logged out
        $response = $this->postJson('/api/logout');
        $response->assertStatus(200);
    }

    /** 
     * Test failed logout of the user
     * 
     * @return void
     */

    public function testFailedLogout()
    {
        //Session isn't created so endpoint should fail
        $response = $this->postJson('/api/logout');
        $response->assertStatus(401);
    }
}
