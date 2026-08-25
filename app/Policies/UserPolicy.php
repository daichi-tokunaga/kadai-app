<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * プロフィールを編集できるか(本人のみ)
     */
    public function update(User $user, User $target): bool
    {
        return $user->is($target);
    }

    /**
     * フォローできるか(自分自身はフォローできない)
     */
    public function follow(User $user, User $target): bool
    {
        return ! $user->is($target);
    }
}
