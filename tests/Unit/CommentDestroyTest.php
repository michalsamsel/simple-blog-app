<?php

namespace Tests\Unit;

use App\Models\Comment;
use App\Models\User;
use App\Models\Post;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CommentDestroyTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test destroy as owner of the resource
     * 
     * @return void
     */
    public function testDestroyResourceWithAuthentication()
    {
        //Only user and owner of resource can destroy it
        $user = Sanctum::actingAs(User::factory()->create(), ['*']);
        $post = Post::factory()->create();

        //Original resource
        $comment = Comment::create([
            'user_id' => $user->id,
            'post_id' => $post->id,
            'content' => 'Test content',
        ]);

        //Delete resource
        $response = $this->deleteJson("/api/comment/$comment->id");
        $response->assertStatus(200);

        //Try to get resource
        $response = $this->getJson("/api/comment/$comment->id");
        $response->assertStatus(404);
    }

    /**
     * Test destroy as non owner of the resource
     * 
     * @return void
     */
    public function testDestroyResourceWithoutAuthentication()
    {
        //Create owner of resource
        $owner = User::factory()->create();

        //Create non owner but authenticated
        Sanctum::actingAs(User::factory()->create(), ['*']);

        $post = Post::factory()->create();

        //Original resource
        $comment = Comment::create([
            'user_id' => $owner->id,
            'post_id' => $post->id,
            'content' => 'Test content',
        ]);

        //Destroy as non owner
        $response = $this->deleteJson("/api/comment/$comment->id");
        //Delete should be impossible
        $response->assertStatus(401);

        //Get resource
        $response = $this->getJson("/api/comment/$comment->id");

        //Check if values did not change
        $this->assertEquals($owner->id, $response['comment']['user_id']);
        $this->assertEquals($post->id, $response['comment']['post_id']);
        $this->assertEquals($comment['content'], $response['comment']['content']);
    }
}
