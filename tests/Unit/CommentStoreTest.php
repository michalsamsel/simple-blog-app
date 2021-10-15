<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\Post;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;


class CommentStoreTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test which checks if user with account can create comment resource.     
     * 
     * @return void
     */
    public function testCreateCommentWithAuthentication()
    {
        //Create user and post
        Sanctum::actingAs(User::factory()->create(), ['*']);
        $post = Post::factory()->create();

        $data = [
            'postId' => $post->id,
            'content' => 'Test content'
        ];

        //Created user should be able to store data
        $response = $this->postJson('/api/comment/create', $data);
        $response->assertStatus(200);
    }

    /**
     * Test which checks if user without account can create comment resource.     
     * 
     * @return void
     */
    public function testCreateCommentWithoutAuthentication()
    {
        //Create user and post
        User::factory()->create();
        $post = Post::factory()->create();

        $data = [
            'postId' => $post->id,
            'content' => 'Test content'
        ];

        //Without account storing data should be impossible
        $response = $this->postJson('/api/comment/create', $data);
        $response->assertStatus(401);
    }

    /**
     * Test if comment can be created if there isnt any post in database
     * 
     * @return void
     */
    public function testCreateCommentWithoutPost()
    {
        //Create user
        $user = Sanctum::actingAs(User::factory()->create(), ['*']);

        //Create resource data with fake id
        $data = [
            'postId' => 1,
            'content' => 'Test content'
        ];

        $response = $this->postJson('/api/comment/create', $data);
        $response->assertStatus(400);
    }

    /**
     * Test validation rule of 'postId'
     * ruleset: 'required|integer|exists:posts,id',
     * 
     * @return void
     */
    public function testPostIdValidationInCreateComment()
    {
        Sanctum::actingAs(User::factory()->create(), ['*']);
        $post = Post::factory()->create();

        $data = [
            'postId' => $post->id,
            'content' => 'Test content'
        ];

        //Require rule fail
        $data['postId'] = "";
        $response = $this->postJson('/api/comment/create', $data);
        $response->assertStatus(400);

        //Integer rule fail
        $data['postId'] = "text";
        $response = $this->postJson('/api/comment/create', $data);
        $response->assertStatus(400);
        $data['postId'] = null;
        $response = $this->postJson('/api/comment/create', $data);
        $response->assertStatus(400);
        $data['postId'] = false;
        $response = $this->postJson('/api/comment/create', $data);
        $response->assertStatus(400);

        //Exist rule fail
        $data['postId'] = 2;
        $response = $this->postJson('/api/comment/create', $data);
        $response->assertStatus(400);

        //All rules should pass
        $data = [
            'postId' => $post->id,
            'content' => 'Test content'
        ];
        $response = $this->postJson('/api/comment/create', $data);
        $response->assertStatus(200);
    }

    /**
     * Test validation rules of 'content'     
     * Ruleset: 'required|string|min:1|max:200'
     * 
     * @return void
     */
    public function testContentValidationInCreateComment()
    {
        Sanctum::actingAs(User::factory()->create(), ['*']);
        $post = Post::factory()->create();

        $data = [
            'postId' => $post->id,
            'content' => 'Test content'
        ];

        //Require/Min rule fail
        $data['content'] = "";
        $response = $this->postJson('/api/comment/create', $data);
        $response->assertStatus(400);

        //String rule fail
        $data['content'] = 0;
        $response = $this->postJson('/api/comment/create', $data);
        $response->assertStatus(400);
        $data['content'] = null;
        $response = $this->postJson('/api/comment/create', $data);
        $response->assertStatus(400);
        $data['content'] = false;
        $response = $this->postJson('/api/comment/create', $data);
        $response->assertStatus(400);
        $data['content'] = true;
        $response = $this->postJson('/api/comment/create', $data);
        $response->assertStatus(400);

        //Max rule fail
        $data['content'] = str_repeat('a', 201);
        $response = $this->postJson('/api/comment/create', $data);
        $response->assertStatus(400);

        //All rules should pass
        $data = [
            'postId' => $post->id,
            'content' => 'Test content'
        ];
        $response = $this->postJson('/api/comment/create', $data);
        $response->assertStatus(200);
    }
}
