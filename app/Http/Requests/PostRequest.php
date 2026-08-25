<?php

namespace App\Http\Requests;

use App\Models\Post;
use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends FormRequest
{
    /**
     * このリクエストを実行する権限があるか
     */
    public function authorize(): bool
    {
        // 投稿者本人かどうかはコントローラ側の PostPolicy で判定している
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
            // TrimStrings ミドルウェアで前後の空白は除去されるため
            // required = 1文字以上 となる
            'postContent' => ['required', 'string', 'max:'.Post::MAX_CONTENT_LENGTH],
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
            'postContent.required' => '投稿内容を1文字以上入力してください',
            'postContent.max' => '投稿内容は'.Post::MAX_CONTENT_LENGTH.'文字以内で入力してください',
        ];
    }
}
