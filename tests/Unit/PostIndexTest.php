<?php

namespace Tests\Unit;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostIndexTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test pagination if correct amount of resources is returned. And correct amount of pages is returned.
     * 
     * @return void
     */
    public function testPaginationIfCorrectDataIsReturned()
    {
        //Create posts and user to pass his id to foreign key
        User::factory(2)->create();
        Post::factory(21)->create();

        $response = $this->getJson('/api/posts?page=1');
        $response->assertStatus(200);
        //Check if correct amount of resources is returned
        $this->assertEquals(10, count($response['posts']['data']));
        //Check if correct amount of total pages is returned
        $this->assertEquals(3, $response['posts']['last_page']);
        //Check if correct amount of resources is returned
        $this->assertEquals(21, $response['posts']['total']);

        $response = $this->getJson('/api/posts?page=3');
        $response->assertStatus(200);
        //Check if correct amount of resources is returned
        $this->assertEquals(1, count($response['posts']['data']));
        //Check if correct amount of total pages is returned
    }

    /**
     * Test pagination when database is empty.
     * 
     * @return void
     */
    public function testPaginationWhenTableIsEmpty()
    {
        $response = $this->getJson('/api/posts?page=1');
        $response->assertStatus(200);
        //Check if correct amount of resources is returned
        $this->assertEquals(0, count($response['posts']['data']));
        //Check if correct amount of total pages is returned
        $this->assertEquals(1, $response['posts']['last_page']);
        //Check if correct amount of resources is returned
        $this->assertEquals(0, $response['posts']['total']);
    }
}
