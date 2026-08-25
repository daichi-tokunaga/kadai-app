<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use App\Http\Requests\SignupRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        // JavaScript 側のバリデーションにも同じルールを渡す(二重管理を避ける)
        return view('user.signup', [
            'rules' => [
                'asciiPattern' => SignupRequest::ASCII_PATTERN,
                'emailPattern' => SignupRequest::EMAIL_PATTERN,
                'passwordMinLength' => SignupRequest::PASSWORD_MIN_LENGTH,
            ],
        ]);
    }

    /**
     * 新規登録処理
     *
     * メールアドレスの重複チェックを含むバリデーションは SignupRequest が担当する。
     * パスワードは User モデルのミューテタで必ずハッシュ化される。
     */
    public function store(SignupRequest $request): RedirectResponse
    {
        $user = User::create($request->validated());

        // 登録したアカウントでそのままログインさせる
        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('home');
    }
}
