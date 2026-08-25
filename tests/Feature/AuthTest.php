<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function パスワードは平文ではなくハッシュで保存される(): void
    {
        $user = User::create([
            'name' => 'テスト',
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $this->assertNotSame('password123', $user->password);
        $this->assertTrue(Hash::check('password123', $user->password));
    }

    /** @test */
    public function 正しい認証情報でログインできる(): void
    {
        $user = User::factory()->create(['email' => 'test@example.com']);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $response->assertRedirect('/');
        $this->assertAuthenticatedAs($user);
    }

    /** @test */
    public function 誤ったパスワードではログインできずエラーが返る(): void
    {
        User::factory()->create(['email' => 'test@example.com']);

        $response = $this->from('/login')->post('/login', [
            'email' => 'test@example.com',
            'password' => 'wrong-password',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    /**
     * Bug3: ログアウトするとログイン情報がブラウザから削除されること
     *
     * @test
     */
    public function ログアウトするとセッションからログイン情報が消える(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $response->assertRedirect('/login');
        $this->assertGuest();

        // ログアウト後にホームへアクセスするとログイン画面に戻される
        $this->get('/')->assertRedirect('/login');
    }

    /** @test */
    public function 未ログインではホーム画面にアクセスできない(): void
    {
        $this->get('/')->assertRedirect('/login');
    }
}
