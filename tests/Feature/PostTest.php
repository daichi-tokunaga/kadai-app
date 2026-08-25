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

    /**
     * Bug4: 1〜140文字の場合のみ投稿処理を行う
     *
     * @test
     */
    public function 空の投稿は保存されない(): void
    {
        $user = User::factory()->create();

        $response = $this->from('/post')->actingAs($user)->post('/post', [
            'postContent' => '   ',
        ]);

        $response->assertRedirect('/post');
        $response->assertSessionHasErrors('postContent');
        $this->assertDatabaseCount('posts', 0);
    }

    /**
     * Bug4: 1〜140文字の場合のみ投稿処理を行う
     *
     * @test
     */
    public function 百四十一文字の投稿は保存されない(): void
    {
        $user = User::factory()->create();

        $response = $this->from('/post')->actingAs($user)->post('/post', [
            'postContent' => str_repeat('あ', Post::MAX_CONTENT_LENGTH + 1),
        ]);

        $response->assertRedirect('/post');
        $response->assertSessionHasErrors('postContent');
        $this->assertDatabaseCount('posts', 0);
    }

    /** @test */
    public function 百四十文字ちょうどの投稿は保存される(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post('/post', [
            'postContent' => str_repeat('あ', Post::MAX_CONTENT_LENGTH),
        ]);

        $this->assertDatabaseCount('posts', 1);
    }

    /**
     * Bug2: 削除した投稿がタイムライン等に表示されないこと
     *
     * @test
     */
    public function 削除した投稿はタイムラインにも詳細画面にも出てこない(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->for($user)->create(['content' => '消える投稿']);

        $this->actingAs($user)->delete("/post/delete/{$post->id}")
            ->assertRedirect('/');

        // 論理削除フラグが立っている
        // (グローバルスコープにより fresh() では取得できないため DB を直接確認する)
        $this->assertDatabaseHas('posts', ['id' => $post->id, 'is_deleted' => true]);

        // タイムラインに出てこない
        $this->actingAs($user)->get('/')->assertDontSee('消える投稿');

        // 投稿詳細も 404
        $this->actingAs($user)->get("/post/detail/{$post->id}")->assertNotFound();

        // ユーザー画面にも出てこない
        $this->actingAs($user)->get("/user/{$user->id}")->assertDontSee('消える投稿');
    }

    /**
     * Bug5: 他人の投稿詳細画面では「編集」「削除」のボタンを表示しない
     *
     * @test
     */
    public function 他人の投稿詳細には編集と削除のボタンが出ない(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $post = Post::factory()->for($owner)->create();

        $this->actingAs($other)
            ->get("/post/detail/{$post->id}")
            ->assertOk()
            ->assertDontSee("/post/edit/{$post->id}")
            ->assertDontSee('削除');
    }

    /** @test */
    public function 自分の投稿詳細には編集と削除のボタンが出る(): void
    {
        $owner = User::factory()->create();
        $post = Post::factory()->for($owner)->create();

        $this->actingAs($owner)
            ->get("/post/detail/{$post->id}")
            ->assertOk()
            ->assertSee("/post/edit/{$post->id}")
            ->assertSee('削除');
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

        $this->assertDatabaseHas('posts', ['id' => $post->id, 'is_deleted' => false]);
    }

    /** @test */
    public function 存在しない投稿は404になる(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get('/post/detail/999')->assertNotFound();
    }
}
