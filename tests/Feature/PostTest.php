<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 投稿できる(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/post', [
            'postContent' => 'テスト投稿',
        ]);

        $response->assertRedirect('/');
        $this->assertDatabaseHas('posts', [
            'user_id' => $user->id,
            'content' => 'テスト投稿',
        ]);
    }

    /** @test */
    public function 他人の投稿の編集画面は開けない(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $post = Post::factory()->for($owner)->create();

        $this->actingAs($other)->get("/post/edit/{$post->id}")->assertForbidden();
    }

    /**
     * 画面を経由せず直接リクエストされた場合も防げていること
     *
     * @test
     */
    public function 他人の投稿を直接putしても更新されない(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $post = Post::factory()->for($owner)->create(['content' => '元の内容']);

        $this->actingAs($other)
            ->put("/post/edit/{$post->id}", ['postContent' => '乗っ取り'])
            ->assertForbidden();

        $this->assertSame('元の内容', $post->fresh()->content);
    }

    /** @test */
    public function 他人の投稿を直接deleteしても削除されない(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $post = Post::factory()->for($owner)->create();

        $this->actingAs($other)
            ->delete("/post/delete/{$post->id}")
            ->assertForbidden();

        $this->assertFalse($post->fresh()->is_deleted);
    }

    /** @test */
    public function 存在しない投稿は404になる(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get('/post/detail/999')->assertNotFound();
    }
}
