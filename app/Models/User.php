<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasFactory;

    /**
     * 一括代入を許可する属性
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'biography',
        'password',
    ];

    /**
     * 配列/JSON 化の際に隠す属性
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * パスワードは代入時に必ずハッシュ化する。
     * これにより平文がデータベースに入ることはない。
     */
    protected function password(): Attribute
    {
        return Attribute::make(
            set: fn (string $value) => Hash::make($value),
        );
    }

    /**
     * このユーザーの投稿
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class)->latest();
    }

    /**
     * このユーザーがフォローしているユーザー
     */
    public function followings(): BelongsToMany
    {
        return $this->belongsToMany(self::class, 'follows', 'user_id', 'follow_user_id')
            ->withTimestamps();
    }

    /**
     * このユーザーをフォローしているユーザー
     */
    public function followers(): BelongsToMany
    {
        return $this->belongsToMany(self::class, 'follows', 'follow_user_id', 'user_id')
            ->withTimestamps();
    }

    /**
     * このユーザーが $user をフォローしているか判定する
     */
    public function isFollowing(User $user): bool
    {
        // ロード済みならクエリを投げずに判定する
        if ($this->relationLoaded('followings')) {
            return $this->followings->contains($user->id);
        }

        return $this->followings()->whereKey($user->id)->exists();
    }

    /**
     * $user をフォローする。自分自身・二重フォローは無視される。
     */
    public function follow(User $user): void
    {
        if ($this->is($user)) {
            return;
        }

        // syncWithoutDetaching は既存行があれば何もしないため二重フォローにならない
        $this->followings()->syncWithoutDetaching([$user->id]);
    }

    /**
     * $user のフォローを解除する。フォローしていなければ何も起きない。
     */
    public function unfollow(User $user): void
    {
        $this->followings()->detach($user->id);
    }
}
