<?php

namespace Tests\Unit;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;


class CommentUpdateTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test update if passed data is changes with existing object
     * 
     * @return void
     */
    public function testUpdateIfSwapsValues()
    {
        //Only user and owner of resource can update it
        $user = Sanctum::actingAs(User::factory()->create(), ['*']);
        $post = Post::factory()->create();

        //New resource
        $comment = Comment::create([
            'user_id' => $user->id,
            'post_id' => $post->id,
            'content' => 'Test content',
        ]);

        //Updated resource data
        $data = [
            'content' => 'Updated content',
        ];

        //Save changes
        $response = $this->putJson("/api/comment/$comment->id", $data);
        $response->assertStatus(200);

        //Get resource
        $response = $this->getJson("/api/comment/$comment->id");

        //Response values should be equal to update values                
        $this->assertEquals($data['content'], $response['comment']['content']);
    }

    /**
     * Test update as non owner of resource
     * 
     * @return void
     */
    public function testUpdateAsNonOwnerOfResource()
    {
        $owner = User::factory()->create();
        $post = Post::factory()->create();

        //New resource
        $comment = Comment::create([
            'user_id' => $owner->id,
            'post_id' => $post->id,
            'content' => 'Test content',
        ]);

        //Updated resource data
        $data = [
            'content' => 'Updated content',
        ];

        //Create non owner user
        Sanctum::actingAs(User::factory()->create(), ['*']);

        //Try to save changes
        $response = $this->putJson("/api/comment/$comment->id", $data);
        $response->assertStatus(401);

        //Get resource
        $response = $this->getJson("/api/comment/$comment->id");

        //Response values should be equal to original values                
        $this->assertEquals($comment['content'], $response['comment']['content']);
    }

    /**
     * Test validation rules of 'content'     
     * Ruleset: 'required|string|min:1|max:500',
     * 
     * @return void
     */
    public function testContentValidationRules()
    {
        //Create user and post
        $user = Sanctum::actingAs(User::factory()->create(), ['*']);
        $post = Post::factory()->create();

        $comment = Comment::create([
            'user_id' => $user->id,
            'post_id' => $post->id,
            'content' => 'Test content'
        ]);

        //Updated resource values
        $data = [
            'content' => 'Updated content',
        ];

        //Required/Min rule fail
        $data['content'] = "";
        $response = $this->putJson('/api/comment/' . $comment->id, $data);
        $response->assertStatus(400);

        //String rule fail
        $data['content'] = null;
        $response = $this->putJson('/api/comment/' . $comment->id, $data);
        $response->assertStatus(400);
        $data['content'] = 0;
        $response = $this->putJson('/api/comment/' . $comment->id, $data);
        $response->assertStatus(400);
        $data['content'] = false;
        $response = $this->putJson('/api/comment/' . $comment->id, $data);
        $response->assertStatus(400);
        $data['content'] = true;
        $response = $this->putJson('/api/comment/' . $comment->id, $data);
        $response->assertStatus(400);

        //Max rule fail
        $data['content'] = str_repeat('a', 501);
        $response = $this->putJson('/api/comment/' . $comment->id, $data);
        $response->assertStatus(400);

        //All rules should pass
        $data = [
            'content' => 'Updated content',
        ];
        $response = $this->putJson('/api/comment/' . $comment->id, $data);
        $response->assertStatus(200);
    }
}
