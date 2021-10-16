<?php

namespace Tests\Unit;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class PostDestroyTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test destroy as owner of the resource
     * 
     * @return void
     */
    public function testDestroyAsOwnerOfResource()
    {
        //Only user and owner of resource can destroy it
        $user = Sanctum::actingAs(User::factory()->create(), ['*']);

        //Original resource
        $post = Post::create([
            'user_id' => $user->id,
            'title' => 'Test title',
            'content' => 'Test content',
        ]);

        //Delete resource
        $response = $this->deleteJson('/api/post/' .$post->id);
        $response->assertStatus(200);

        //Try to get resource
        $response = $this->getJson('/api/post/' .$post->id);
        $response->assertStatus(404);
    }

    /**
     * Test destroy as non owner of the resource
     * 
     * @return void
     */
    public function testDestroyAsNonOwnerOfResource()
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

        //Destroy as non owner
        $response = $this->deleteJson('/api/post/' .$post->id);
        //Delete should be impossible
        $response->assertStatus(401);

        //Get resource
        $response = $this->getJson('/api/post/' .$post->id);
        $response->assertStatus(200);
    }

    /**
     * Test destroy as unauthenticated user
     * 
     * @return void
     */
    public function testDestroyAsUnauthenticated()
    {
        //Create owner of resource
        $user = User::factory()->create();

        //Original resource
        $post = Post::create([
            'user_id' => $user->id,
            'title' => 'Test title',
            'content' => 'Test content',
        ]);

        //Destroy as unauthenticated
        $response = $this->deleteJson('/api/post/' .$post->id);
        //Delete should be impossible
        $response->assertStatus(401);

        //Get resource
        $response = $this->getJson('/api/post/' .$post->id);
        $response->assertStatus(200);
    }
}
