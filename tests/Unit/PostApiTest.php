<?php

namespace Tests\Unit;

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
                'total'
            ]);
    }

    public function test_can_show_post()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->getJson("/api/posts/{$post->id}");

        $response->assertStatus(200)
            ->assertJsonStructure(['id', 'title', 'content', 'user_id', 'created_at', 'updated_at'])
            ->assertJson([
                'id' => $post->id,
                'title' => $post->title,
                'content' => $post->content,
                'user_id' => $post->user_id,
            ]);
    }

    public function test_show_post_not_found()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->getJson('/api/posts/999');

        $response->assertStatus(404)
            ->assertJson(['error' => 'Post not found']);
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

    public function test_can_update_post()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);

        $updatedPostData = [
            'title' => 'Updated Title',
            'content' => 'Updated content.'
        ];

        $response = $this->actingAs($user)->putJson("/api/posts/{$post->id}", $updatedPostData);

        $response->assertStatus(200)
            ->assertJsonStructure(['id', 'title', 'content', 'user_id', 'created_at', 'updated_at'])
            ->assertJson($updatedPostData);

        $this->assertDatabaseHas('posts', $updatedPostData + ['id' => $post->id]);
    }

    public function test_update_post_unauthorized()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user1->id]);

        $updatedPostData = [
            'title' => 'Updated Title',
            'content' => 'Updated content.'
        ];

        $response = $this->actingAs($user2)->putJson("/api/posts/{$post->id}", $updatedPostData);

        $response->assertStatus(403)
            ->assertJson(['error' => 'Unauthorized']);
    }

    public function test_can_delete_post()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->deleteJson("/api/posts/{$post->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    }

    public function test_delete_post_unauthorized()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user1->id]);

        $response = $this->actingAs($user2)->deleteJson("/api/posts/{$post->id}");

        $response->assertStatus(403)
            ->assertJson(['error' => 'Unauthorized']);
    }
}
