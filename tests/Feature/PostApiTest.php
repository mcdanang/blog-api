<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_get_all_posts()
    {
        $user = User::factory()->create();
        $posts = Post::factory()->count(5)->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->getJson('/api/posts');

        $response->assertStatus(200)
            ->assertJsonCount(5, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'title', 'content', 'user_id', 'created_at', 'updated_at']
                ],
                'links',
                'meta'
            ]);
    }

    public function test_can_create_post()
    {
        $user = User::factory()->create();

        $postData = [
            'title' => 'Test Post',
            'content' => 'This is a test post content.'
        ];

        $response = $this->actingAs($user)->postJson('/api/posts', $postData);

        $response->assertStatus(201)
            ->assertJsonStructure(['id', 'title', 'content', 'user_id', 'created_at', 'updated_at'])
            ->assertJson($postData);

        $this->assertDatabaseHas('posts', $postData);
    }
}
