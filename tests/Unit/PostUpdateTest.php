<?php

namespace Tests\Unit;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class PostUpdateTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test update as user who owns a resource and is authenticated
     * 
     * @return void
     */
    public function testUpdateAsOwnerOfResource()
    {
        //Only user and owner of resource can update it
        $user = Sanctum::actingAs(User::factory()->create(), ['*']);

        //Original resource
        $post = Post::create([
            'user_id' => $user->id,
            'title' => 'Test title',
            'content' => 'Test content',
        ]);

        //Updated resource values
        $data = [
            'title' => 'Updated title',
            'content' => 'Updated content',
        ];

        //Save changes
        $response = $this->putJson("/api/post/$post->id", $data);
        $response->assertStatus(200);

        //Get resource
        $response = $this->getJson("/api/post/$post->id");

        //Response values should be equal to update values
        $this->assertEquals($user->id, $response['post']['user_id']);
        $this->assertEquals($data['title'], $response['post']['title']);
        $this->assertEquals($data['content'], $response['post']['content']);
    }

    /**
     * Test update as non owner of resource
     * 
     * @return void
     */
    public function testUpdateAsNonOwnerOfResource()
    {
        //Create owner of resource
        $owner = User::factory()->create();

        //Create non owner
        Sanctum::actingAs(User::factory()->create(), ['*']);

        //Original resource
        $post = Post::create([
            'user_id' => $owner->id,
            'title' => 'Test title',
            'content' => 'Test content',
        ]);

        //Updated resource
        $data = [
            'title' => 'Updated title',
            'content' => 'Updated content',
        ];

        //Save changes as non owner
        $response = $this->putJson("/api/post/$post->id", $data);
        //Changes should be impossible
        $response->assertStatus(401);

        //Get resource
        $response = $this->getJson("/api/post/$post->id");

        //Check if values did not change
        $this->assertEquals($owner->id, $response['post']['user_id']);
        $this->assertEquals($post['title'], $response['post']['title']);
        $this->assertEquals($post['content'], $response['post']['content']);
    }

    /**
     * Test update as unauthenticated user
     * 
     * @return void
     */
    public function testUpdateAsUnauthenticated()
    {
        //Create owner of resource
        $user = User::factory()->create();

        //Original resource
        $post = Post::create([
            'user_id' => $user->id,
            'title' => 'Test title',
            'content' => 'Test content',
        ]);

        //Updated resource
        $data = [
            'title' => 'Updated title',
            'content' => 'Updated content',
        ];

        //Save changes as unauthenticated
        $response = $this->putJson("/api/post/$post->id", $data);
        //Changes should be impossible
        $response->assertStatus(401);

        //Get resource
        $response = $this->getJson("/api/post/$post->id");

        //Check if values did not change
        $this->assertEquals($user->id, $response['post']['user_id']);
        $this->assertEquals($post['title'], $response['post']['title']);
        $this->assertEquals($post['content'], $response['post']['content']);
    }

    /**
     * Test validation rules of 'title'     
     * Ruleset: 'required|string|min:1|max:50',
     * 
     * @return void
     */
    public function testTitleValidationRules()
    {
        //Only user and owner of resource can update it
        $user = Sanctum::actingAs(User::factory()->create(), ['*']);

        //Original resource
        $post = Post::create([
            'user_id' => $user->id,
            'title' => 'Test title',
            'content' => 'Test content',
        ]);

        //Updated resource values
        $data = [
            'title' => 'Updated title',
            'content' => 'Updated content',
        ];

        //Require/Min rule fail
        $data['title'] = "";
        $response = $this->putJson("/api/post/" . $post->id, $data);
        $response->assertStatus(400);

        //String rule fail
        $data['title'] = 0;
        $response = $this->putJson("/api/post/" . $post->id, $data);
        $response->assertStatus(400);
        $data['title'] = null;
        $response = $this->putJson("/api/post/" . $post->id, $data);
        $response->assertStatus(400);
        $data['title'] = false;
        $response = $this->putJson("/api/post/" . $post->id, $data);
        $response->assertStatus(400);
        $data['title'] = true;
        $response = $this->putJson("/api/post/" . $post->id, $data);
        $response->assertStatus(400);

        //Max rule fail
        $data['title'] = str_repeat('a', 51);
        $response = $this->putJson("/api/post/" . $post->id, $data);
        $response->assertStatus(400);

        //All rules should pass
        $data = [
            'title' => 'Updated title',
            'content' => 'Updated content',
        ];
        $response = $this->putJson('/api/post/' . $post->id, $data);
        $response->assertStatus(200);
    }

    /**
     * Test validation rules of 'content'     
     * Ruleset: 'required|string|min:1|max:500',
     * 
     * @return void
     */
    public function testContentValidationRules()
    {
        //Only user and owner of resource can update it
        $user = Sanctum::actingAs(User::factory()->create(), ['*']);

        //Original resource
        $post = Post::create([
            'user_id' => $user->id,
            'title' => 'Test title',
            'content' => 'Test content',
        ]);

        //Updated resource values
        $data = [
            'title' => 'Updated title',
            'content' => 'Updated content',
        ];

        //Require/Min rule fail
        $data['content'] = "";
        $response = $this->putJson("/api/post/" . $post->id, $data);
        $response->assertStatus(400);

        //String rule fail
        $data['content'] = 0;
        $response = $this->putJson("/api/post/" . $post->id, $data);
        $response->assertStatus(400);
        $data['content'] = null;
        $response = $this->putJson("/api/post/" . $post->id, $data);
        $response->assertStatus(400);
        $data['content'] = true;
        $response = $this->putJson("/api/post/" . $post->id, $data);
        $response->assertStatus(400);

        //Max rule fail
        $data['content'] = str_repeat('a', 501);
        $response = $this->putJson("/api/post/" . $post->id, $data);
        $response->assertStatus(400);

        //All rules should pass
        $data = [
            'title' => 'Updated title',
            'content' => 'Updated content',
        ];
        $response = $this->putJson('/api/post/' . $post->id, $data);
        $response->assertStatus(200);
    }
}
