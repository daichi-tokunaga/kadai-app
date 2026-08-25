<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SignupRequest extends FormRequest
{
    /**
     * 半角英数記号(スペースを除く印字可能 ASCII)のみを許容するパターン。
     *
     * デリミタを含まない生のパターンとして持たせ、PHP 側の regex ルールと
     * 画面側の JavaScript の両方から同じものを参照する。
     */
    public const ASCII_PATTERN = '^[\x21-\x7e]+$';

    /**
     * メールアドレスが ●●●@××× の形式であることを確認するパターン。
     */
    public const EMAIL_PATTERN = '^[^\s@]+@[^\s@]+$';

    /**
     * パスワードの最小文字数
     */
    public const PASSWORD_MIN_LENGTH = 8;

    /**
     * このリクエストを実行する権限があるか
     */
    public function authorize(): bool
    {
        // 未ログインユーザーのみ到達するルートなので誰でも実行できる
        return true;
    }

    /**
     * バリデーションルール
     *
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'max:255',
                'regex:/'.self::ASCII_PATTERN.'/',
                'regex:/'.self::EMAIL_PATTERN.'/',
                'email:filter',
                // 既に登録済みのアドレスかどうかをデータベースに問い合わせる
                'unique:users,email',
            ],
            'password' => [
                'required',
                'string',
                'min:'.self::PASSWORD_MIN_LENGTH,
                'regex:/'.self::ASCII_PATTERN.'/',
            ],
        ];
    }

    /**
     * エラーメッセージ
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'アカウント名を入力してください',
            'name.max' => 'アカウント名は255文字以内で入力してください',
            'email.required' => 'メールアドレスを入力してください',
            'email.max' => 'メールアドレスは255文字以内で入力してください',
            'email.regex' => 'メールアドレスは●●●@×××の形式で、半角英数記号のみで入力してください',
            'email.email' => 'メールアドレスは●●●@×××の形式で入力してください',
            'email.unique' => 'このメールアドレスはすでに使用されています',
            'password.required' => 'パスワードを入力してください',
            'password.min' => 'パスワードは'.self::PASSWORD_MIN_LENGTH.'文字以上で入力してください',
            'password.regex' => 'パスワードは半角英数記号のみで入力してください',
        ];
    }
}
