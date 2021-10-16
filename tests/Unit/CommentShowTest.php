<?php

namespace Tests\Unit;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentShowTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test getting an element out of database
     *
     * @return void
     */
    public function testGetExistingComment()
    {
        //Create user and post
        $user = User::factory()->create();
        $post = Post::factory()->create();

        //Store example resource
        $comment = Comment::create([
            'user_id' => $user->id,
            'post_id' => $post->id,
            'content' => 'Test content',
        ]);

        $response = $this->getJson("/api/comment/$comment->id");
        $response->assertStatus(200);

        //Check if each value is the same before and after saving it
        $this->assertEquals($user->id, $response['comment']['user_id']);
        $this->assertEquals($post->id, $response['comment']['post_id']);
        $this->assertEquals($comment['content'], $response['comment']['content']);
    }

    /**
     * Test getting an non existing element out of database
     *
     * @ return void
     */
    public function testGetNonExistingComment()
    {
        $response = $this->getJson('/api/comment/1');
        //Non existing resource shouldnt be found
        $response->assertStatus(404);
    }
}
