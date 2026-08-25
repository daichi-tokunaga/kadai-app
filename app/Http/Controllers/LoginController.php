<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller
{
    /**
     * ログイン画面遷移
     */
    public function create(): View
    {
        return view('login');
    }

    /**
     * ログイン処理
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Auth::attempt がメールアドレスの検索とハッシュ照合をまとめて行う
        if (! Auth::attempt($request->validated(), $request->boolean('remember'))) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'ユーザーが存在しないかパスワードが間違っています']);
        }

        // セッションフィクセーション対策としてセッション ID を振り直す
        $request->session()->regenerate();

        return redirect()->intended(route('home'));
    }

    /**
     * ログアウト処理
     */
    public function destroy(Request $request): RedirectResponse
    {
        return redirect()->route('login');
    }
}
