<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 存在しないユーザーは404になる(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get('/user/999')->assertNotFound();
    }

    /** @test */
    public function 自分のプロフィールを編集できる(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->put("/user/edit/{$user->id}", [
                'username' => '新しい名前',
                'biography' => '新しい自己紹介',
            ])
            ->assertRedirect("/user/{$user->id}");

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => '新しい名前',
            'biography' => '新しい自己紹介',
        ]);
    }

    /**
     * セッションに保存したユーザーを使い回すと起きていた「編集しても
     * ヘッダーの名前が変わらない」問題が起きないこと
     *
     * @test
     */
    public function プロフィール編集後はヘッダーの表示名も更新される(): void
    {
        $user = User::factory()->create(['name' => '古い名前']);

        $this->actingAs($user)->put("/user/edit/{$user->id}", [
            'username' => '新しい名前',
            'biography' => '',
        ]);

        $this->actingAs($user->fresh())
            ->get('/')
            ->assertSee('新しい名前')
            ->assertDontSee('古い名前');
    }

    /** @test */
    public function 他人のプロフィールは編集できない(): void
    {
        $user = User::factory()->create();
        $other = User::factory()->create(['name' => '他人']);

        $this->actingAs($user)->get("/user/edit/{$other->id}")->assertForbidden();

        $this->actingAs($user)
            ->put("/user/edit/{$other->id}", ['username' => '乗っ取り', 'biography' => ''])
            ->assertForbidden();

        $this->assertSame('他人', $other->fresh()->name);
    }
}
