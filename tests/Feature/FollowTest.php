<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FollowTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function フォローとフォロー解除ができる(): void
    {
        $user = User::factory()->create();
        $target = User::factory()->create();

        $this->actingAs($user)
            ->post("/follow/{$target->id}")
            ->assertRedirect("/user/{$target->id}");
        $this->assertTrue($user->fresh()->isFollowing($target));

        $this->actingAs($user)
            ->delete("/follow/{$target->id}")
            ->assertRedirect("/user/{$target->id}");
        $this->assertFalse($user->fresh()->isFollowing($target));
    }

    /** @test */
    public function 同じ相手を二重にフォローしても行は増えない(): void
    {
        $user = User::factory()->create();
        $target = User::factory()->create();

        $this->actingAs($user)->post("/follow/{$target->id}");
        $this->actingAs($user)->post("/follow/{$target->id}");

        $this->assertDatabaseCount('follows', 1);
    }

    /** @test */
    public function 自分自身はフォローできない(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post("/follow/{$user->id}")->assertForbidden();

        $this->assertDatabaseCount('follows', 0);
    }

    /** @test */
    public function フォローしていない相手を解除してもエラーにならない(): void
    {
        $user = User::factory()->create();
        $target = User::factory()->create();

        $this->actingAs($user)
            ->delete("/follow/{$target->id}")
            ->assertRedirect("/user/{$target->id}");
    }

    /** @test */
    public function タイムラインには自分とフォロー中のユーザーの投稿だけが出る(): void
    {
        $user = User::factory()->create();
        $followed = User::factory()->create();
        $stranger = User::factory()->create();

        Post::factory()->for($user)->create(['content' => '自分の投稿']);
        Post::factory()->for($followed)->create(['content' => 'フォロー中の投稿']);
        Post::factory()->for($stranger)->create(['content' => '無関係な投稿']);

        $user->follow($followed);

        $this->actingAs($user)
            ->get('/')
            ->assertOk()
            ->assertSee('自分の投稿')
            ->assertSee('フォロー中の投稿')
            ->assertDontSee('無関係な投稿');
    }

    /** @test */
    public function フォローリスト画面はurlに応じたタブが開いた状態になる(): void
    {
        $user = User::factory()->create();
        $followed = User::factory()->create(['name' => 'フォロー中の人']);
        $follower = User::factory()->create(['name' => 'フォロワーの人']);

        $user->follow($followed);
        $follower->follow($user);

        // /follow はフォロータブが選択された状態
        $this->actingAs($user)
            ->get("/user/{$user->id}/follow")
            ->assertOk()
            ->assertSee('フォロー中の人')
            ->assertSee('フォロワーの人');

        // /follower はフォロワータブが選択された状態
        $this->actingAs($user)
            ->get("/user/{$user->id}/follower")
            ->assertOk()
            ->assertSee('フォロワーの人');
    }
}
