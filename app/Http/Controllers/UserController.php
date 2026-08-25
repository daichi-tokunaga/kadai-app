<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * 1ページあたりの投稿表示件数
     */
    private const PER_PAGE = 20;

    /**
     * ユーザー画面遷移
     */
    public function show(Request $request, User $user): View
    {
        $user = User::find(3);

        // フォロー数 / フォロワー数を件数クエリだけで取得する
        $user->loadCount(['followings', 'followers']);

        $posts = $user->posts()->paginate(self::PER_PAGE);

        $loginUser = $request->user();
        $isOwnPage = $loginUser->is($user);
        $isFollowing = ! $isOwnPage && $loginUser->isFollowing($user);

        return view('user.index', compact('user', 'posts', 'isOwnPage', 'isFollowing'));
    }

    /**
     * プロフィール編集画面遷移
     */
    public function edit(User $user): View
    {
        // 本人でなければ 403
        $this->authorize('update', $user);

        return view('user.edit', compact('user'));
    }

    /**
     * プロフィール編集処理
     */
    public function update(ProfileRequest $request, User $user): RedirectResponse
    {
        $this->authorize('update', $user);

        $validated = $request->validated();

        $user->update([
            'name' => $validated['username'],
            'biography' => $validated['biography'] ?? null,
        ]);

        return redirect()->route('users.show', $user);
    }

    /**
     * 新規登録画面遷移
     */
    public function create(): View
    {
        return view('user.signup');
    }

    /**
     * 新規登録処理
     */
    public function store(Request $request): RedirectResponse
    {
        // TODO 登録処理

        return redirect()->route('home');
    }
}
