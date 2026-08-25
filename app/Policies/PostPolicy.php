<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PostPolicy
{
    use HandlesAuthorization;

    /**
     * 投稿を編集できるか(投稿者本人のみ)
     */
    public function update(User $user, Post $post): bool
    {
        return $user->id === $post->user_id;
    }

    /**
     * 投稿を削除できるか(投稿者本人のみ)
     */
    public function delete(User $user, Post $post): bool
    {
        return $this->update($user, $post);
    }
}
