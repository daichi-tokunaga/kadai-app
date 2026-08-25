<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
{
    /**
     * 自己紹介の最大文字数(users.biography のカラム長に合わせる)
     */
    public const BIOGRAPHY_MAX_LENGTH = 140;

    /**
     * このリクエストを実行する権限があるか
     */
    public function authorize(): bool
    {
        // 本人以外の編集はルート側の can:update,user で弾いている
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
            'username' => ['required', 'string', 'max:255'],
            'biography' => ['nullable', 'string', 'max:'.self::BIOGRAPHY_MAX_LENGTH],
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
            'username.required' => 'ユーザー名を入力してください',
            'username.max' => 'ユーザー名は255文字以内で入力してください',
            'biography.max' => '自己紹介は'.self::BIOGRAPHY_MAX_LENGTH.'文字以内で入力してください',
        ];
    }
}
