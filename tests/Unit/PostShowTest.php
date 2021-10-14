<?php

namespace Tests\Unit;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostShowTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test getting element out of database
     *
     * @ return void
     */
    public function testGetExistingPost()
    {
        $user = User::factory()->create();
        //Store example resource
        $post = Post::create([
            'user_id' => $user->id,
            'title' => 'Test title',
            'content' => 'Test content',
        ]);

        $response = $this->getJson("/api/post/$post->id");
        $response->assertStatus(200);

        //Check if each value is the same before and after saving it
        $this->assertEquals($post['user_id'], $response['post']['user_id']);
        $this->assertEquals($post['title'], $response['post']['title']);
        $this->assertEquals($post['content'], $response['post']['content']);
    }

    /**
     * Test getting an non existing element out of database
     *
     * @ return void
     */
    public function testGetNonExistingPost()
    {
        $response = $this->getJson('/api/post/1');
        //Non existing resource shouldnt be found
        $response->assertStatus(404);
    }
}
