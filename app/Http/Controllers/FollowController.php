<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FollowController extends Controller
{
    /**
     * フォロー/フォロワーリスト画面遷移
     *
     * /user/{id}/follow と /user/{id}/follower で同じ画面を使い、
     * どちらのタブを開いた状態にするかをサーバー側で決める。
     */
    public function index(Request $request, User $user): View
    {
        $user->load(['followings', 'followers']);

        $activeTab = $request->routeIs('users.followers') ? 'follower' : 'follow';

        return view('user.follow', compact('user', 'activeTab'));
    }

    /**
     * フォロー処理
     */
    public function store(Request $request, User $user): RedirectResponse
    {
        // 自分自身はフォローできない
        $this->authorize('follow', $user);

        $request->user()->follow($user);

        return redirect()->route('users.show', $user);
    }

    /**
     * フォロー解除処理
     */
    public function destroy(Request $request, User $user): RedirectResponse
    {
        $request->user()->unfollow($user);

        return redirect()->route('users.show', $user);
    }
}
