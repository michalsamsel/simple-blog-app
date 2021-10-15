<?php

namespace Tests\Unit;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostAndUserIndexTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test pagination if correct amount of resources is returned.
     * 
     * @return void
     */
    public function testPaginationIfCorrectDataIsReturned()
    {
        //Create two users
        $users = User::factory(2)->create();

        foreach ($users as $user) {
            //Create resources for pagination
            Post::factory(21)->create([
                'user_id' => $user->id,
                'title' => 'Test title',
                'content' => 'Test conent',
            ]);

            $response = $this->getJson('/api/user/' . $user->id . '/posts?page=1');
            $response->assertStatus(200);
            //Check if correct amount of resources is returned
            $this->assertEquals(10, count($response['posts']['data']));
            //Check if correct amount of total pages is returned
            $this->assertEquals(3, $response['posts']['last_page']);
            //Check if correct amount of resources is returned
            $this->assertEquals(21, $response['posts']['total']);

            $response = $this->getJson('/api/user/' . $user->id . '/posts?page=3');
            $response->assertStatus(200);
            //Check if correct amount of resources is returned
            $this->assertEquals(1, count($response['posts']['data']));
            //Check if correct amount of total pages is returned
        }
    }

    /**
     * Test pagination when database is empty.
     * 
     * @return void
     */
    public function testPaginationWhenTableIsEmpty()
    {
        $response = $this->getJson('/api/user/1/posts?page=1');
        $response->assertStatus(200);
        //Check if correct amount of resources is returned
        $this->assertEquals(0, count($response['posts']['data']));
        //Check if correct amount of total pages is returned
        $this->assertEquals(1, $response['posts']['last_page']);
        //Check if correct amount of resources is returned
        $this->assertEquals(0, $response['posts']['total']);
    }
}
