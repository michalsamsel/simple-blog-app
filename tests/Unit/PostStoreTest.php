<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class PostStoreTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test which checks if user with account can create post resource.     
     * 
     * @return void
     */
    public function testCreatePostWithAuthentication()
    {
        //Create a user
        Sanctum::actingAs(User::factory()->create(), ['*']);
        $data = [
            'title' => 'Test title',
            'content' => 'Test content'
        ];

        //Created user should be able to store data
        $response = $this->postJson('/api/post/create', $data);
        $response->assertStatus(201);
    }

    /**
     * Test which checks if user without authentication can create post resource.     
     * 
     * @return void
     */
    public function testCreatePostWithoutAuthentication()
    {
        $user = User::factory()->create();

        $data = [
            'user_id' => $user->id,
            'title' => 'Test title',
            'content' => 'Test content'
        ];

        //Without account storing data should be impossible
        $response = $this->postJson('/api/post/create', $data);
        $response->assertStatus(401);
    }

    /**
     * Test validation rules of 'title'     
     * Ruleset: 'required|string|min:1|max:50',
     * 
     * @return void
     */
    public function testTitleValidationInCreatePost()
    {
        //User should be authenticated to store a new resource
        Sanctum::actingAs(User::factory()->create(), ['*']);

        $data = [
            'title' => 'Test title',
            'content' => 'Test content'
        ];

        //Require/Min rule fail
        $data['title'] = "";
        $response = $this->postJson('/api/post/create', $data);
        $response->assertStatus(400);

        //String rule fail
        $data['title'] = 0;
        $response = $this->postJson('/api/post/create', $data);
        $response->assertStatus(400);
        $data['title'] = null;
        $response = $this->postJson('/api/post/create', $data);
        $response->assertStatus(400);
        $data['title'] = false;
        $response = $this->postJson('/api/post/create', $data);
        $response->assertStatus(400);
        $data['title'] = true;
        $response = $this->postJson('/api/post/create', $data);
        $response->assertStatus(400);

        //Max rule fail
        $data['title'] = str_repeat('a', 51);
        $response = $this->postJson('/api/post/create', $data);
        $response->assertStatus(400);

        //All rules should pass
        $data = [
            'title' => 'Test title',
            'content' => 'Test content'
        ];
        $response = $this->postJson('/api/post/create', $data);
        $response->assertStatus(201);
    }

    /**
     * Test validation rules of 'content'     
     * Ruleset: 'required|string|min:1|max:500',
     * 
     * @return void
     */
    public function testContentValidationInCreatePost()
    {
        //User should be authenticated to store a new resource
        Sanctum::actingAs(User::factory()->create(), ['*']);
        $data = [
            'title' => 'Test title',
            'content' => 'Test content'
        ];

        //Require/Min rule fail
        $data['content'] = "";
        $response = $this->postJson('/api/post/create', $data);
        $response->assertStatus(400);

        //String rule fail
        $data['content'] = 0;
        $response = $this->postJson('/api/post/create', $data);
        $response->assertStatus(400);
        $data['content'] = null;
        $response = $this->postJson('/api/post/create', $data);
        $response->assertStatus(400);
        $data['content'] = false;
        $response = $this->postJson('/api/post/create', $data);
        $response->assertStatus(400);
        $data['content'] = true;
        $response = $this->postJson('/api/post/create', $data);
        $response->assertStatus(400);

        //Max rule fail
        $data['content'] = str_repeat('a', 501);
        $response = $this->postJson('/api/post/create', $data);
        $response->assertStatus(400);

        //All rules should pass
        $data = [
            'title' => 'Test title',
            'content' => 'Test content'
        ];
        $response = $this->postJson('/api/post/create', $data);
        $response->assertStatus(201);
    }
}
