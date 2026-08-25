<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * 1ページあたりの投稿表示件数
     */
    private const PER_PAGE = 20;

    /**
     * ホーム画面遷移
     *
     * 自分とフォロー中のユーザーの投稿を新しい順に表示する。
     */
    public function index(Request $request): View
    {
        $loginUser = $request->user();

        // 「自分 + フォロー中のユーザー」の ID を1クエリで取得する
        $userIds = $loginUser->followings()
            ->pluck('users.id')
            ->push($loginUser->id);

        // 並べ替えも絞り込みも SQL 側で行う。
        // with('user') により投稿者の取得が1クエリにまとまる(N+1 の解消)。
        // 削除済みの投稿は SoftDeletes によって自動的に除外される。
        $posts = Post::query()
            ->whereIn('user_id', $userIds)
            ->with('user')
            ->latest()
            ->latest('id')
            ->paginate(self::PER_PAGE);

        return view('home', compact('posts'));
    }
}
