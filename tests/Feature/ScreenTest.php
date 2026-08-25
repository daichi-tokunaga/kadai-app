<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * 各画面が例外なく描画できることを確認するスモークテスト
 */
class ScreenTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 未ログインで表示できる画面(): void
    {
        $this->get('/login')->assertOk()->assertSee('login');
        $this->get('/signup')->assertOk()->assertSee('新規登録');
    }

    /** @test */
    public function ログイン済みで表示できる画面(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->for($user)->create();
        $other = User::factory()->create();

        $this->actingAs($user);

        $this->get('/')->assertOk();
        $this->get('/post')->assertOk();
        $this->get("/post/detail/{$post->id}")->assertOk();
        $this->get("/post/edit/{$post->id}")->assertOk();
        $this->get("/user/{$user->id}")->assertOk();
        $this->get("/user/{$other->id}")->assertOk();
        $this->get("/user/edit/{$user->id}")->assertOk();
        $this->get("/user/{$user->id}/follow")->assertOk();
        $this->get("/user/{$user->id}/follower")->assertOk();
    }

    /** @test */
    public function ログイン済みユーザーはログイン画面と新規登録画面に入れない(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get('/login')->assertRedirect('/');
        $this->actingAs($user)->get('/signup')->assertRedirect('/');
    }

    /**
     * ヘッダーの表示がログイン状態で切り替わること
     *
     * @test
     */
    public function ヘッダーはログイン状態に応じて切り替わる(): void
    {
        $this->get('/login')
            ->assertSee('ログイン')
            ->assertSee('新規登録');

        $user = User::factory()->create(['name' => 'テスト太郎']);

        $this->actingAs($user)->get('/')
            ->assertSee('テスト太郎')
            ->assertSee('ログアウト');
    }

    /**
     * ページネーションが機能していること
     *
     * @test
     */
    public function 投稿が多いときはページ送りが表示される(): void
    {
        $user = User::factory()->create();
        Post::factory()->count(25)->for($user)->create();

        $this->actingAs($user)->get('/')
            ->assertOk()
            ->assertSee('次へ');

        $this->actingAs($user)->get('/?page=2')
            ->assertOk()
            ->assertSee('前へ');
    }
}
