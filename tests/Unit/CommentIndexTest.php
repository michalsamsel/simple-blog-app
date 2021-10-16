<?php

namespace Tests\Unit;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentIndexTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test pagination if correct amount of resources is returned. And correct amount of pages is returned.
     * 
     * @return void
     */
    public function testPaginationIfCorrectDataIsReturned()
    {
        //Comments require at least one user and post
        User::factory()->create();
        $post = Post::factory()->create();
        Comment::factory(31)->create();

        $response = $this->getJson('/api/post/' . $post->id . '/comments?page=1');
        $response->assertStatus(200);
        //Check if correct amount of resources is returned
        $this->assertEquals(15, count($response['comments']['data']));
        //Check if correct amount of total pages is returned
        $this->assertEquals(3, $response['comments']['last_page']);
        //Check if correct amount of resources is returned
        $this->assertEquals(31, $response['comments']['total']);

        $response = $this->getJson('/api/post/' . $post->id . '/comments?page=3');
        $response->assertStatus(200);
        //Check if correct amount of resources is returned
        $this->assertEquals(1, count($response['comments']['data']));
        //Check if correct amount of total pages is returned
    }

    /**
     * Test pagination when database is empty.
     * 
     * @return void
     */
    public function testPaginationWhenTableIsEmpty()
    {
        //Comments require at least one user and post
        User::factory()->create();
        $post = Post::factory()->create();

        $response = $this->getJson('/api/post/' . $post->id . '/comments?page=1');
        $response->assertStatus(200);
        //Check if correct amount of resources is returned
        $this->assertEquals(0, count($response['comments']['data']));
        //Check if correct amount of total pages is returned
        $this->assertEquals(1, $response['comments']['last_page']);
        //Check if correct amount of resources is returned
        $this->assertEquals(0, $response['comments']['total']);
    }
}
