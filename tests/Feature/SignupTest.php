<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * 問題2: 新規登録機能
 */
class SignupTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 新規登録に成功するとログイン状態でホームへ遷移する(): void
    {
        $response = $this->post('/signup', [
            'name' => 'newuser',
            'email' => 'new@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/');
        $this->assertDatabaseHas('users', [
            'email' => 'new@example.com',
            'name' => 'newuser',
        ]);
        $this->assertAuthenticatedAs(User::whereEmail('new@example.com')->first());
    }

    /** @test */
    public function 登録済みのメールアドレスは専用のメッセージで拒否される(): void
    {
        User::factory()->create(['email' => 'taken@example.com']);

        $response = $this->from('/signup')->post('/signup', [
            'name' => 'newuser',
            'email' => 'taken@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/signup');
        $response->assertSessionHasErrors([
            'email' => 'このメールアドレスはすでに使用されています',
        ]);
        $this->assertGuest();
    }

    /**
     * @test
     *
     * @dataProvider invalidPayloads
     */
    public function バリデーションに違反する入力は登録されない(array $payload, string $errorKey): void
    {
        $response = $this->post('/signup', $payload);

        $response->assertSessionHasErrors($errorKey);
        $this->assertDatabaseCount('users', 0);
        $this->assertGuest();
    }

    /**
     * @return array<string, array{0: array<string, string>, 1: string}>
     */
    public static function invalidPayloads(): array
    {
        $valid = [
            'name' => 'newuser',
            'email' => 'new@example.com',
            'password' => 'password123',
        ];

        return [
            'アットマークがない' => [['email' => 'new.example.com'] + $valid, 'email'],
            'メールアドレスが全角' => [['email' => 'ｎｅｗ@example.com'] + $valid, 'email'],
            'メールアドレスが空' => [['email' => ''] + $valid, 'email'],
            'パスワードが7文字' => [['password' => 'pass123'] + $valid, 'password'],
            'パスワードが全角を含む' => [['password' => 'passwordあ'] + $valid, 'password'],
            'パスワードが空' => [['password' => ''] + $valid, 'password'],
            'アカウント名が空' => [['name' => ''] + $valid, 'name'],
        ];
    }
}
