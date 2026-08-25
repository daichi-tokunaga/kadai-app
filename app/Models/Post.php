<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Post extends Model
{
    use HasFactory;

    /**
     * 投稿本文の最大文字数
     */
    public const MAX_CONTENT_LENGTH = 140;

    /**
     * 一括代入を許可する属性
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'content',
    ];

    /**
     * 属性のキャスト
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_deleted' => 'boolean',
    ];

    /**
     * モデルの初期化処理
     */
    protected static function booted(): void
    {
        // 論理削除された投稿を、この Post に対するすべてのクエリから除外する。
        //
        // 呼び出し側ごとに where('is_deleted', false) を書くと必ず書き漏らしが出るため、
        // グローバルスコープとしてモデル側に一度だけ定義する。
        // タイムライン・ユーザー画面に加えて、ルートモデルバインディングにも効くので
        // 削除済みの投稿の詳細画面( /post/detail/{id} )は自動的に 404 になる。
        static::addGlobalScope('notDeleted', function (Builder $query) {
            $query->where('is_deleted', false);
        });
    }

    /**
     * この投稿を投稿したユーザー
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
