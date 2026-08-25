<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PostController extends Controller
{
    /**
     * 投稿画面遷移
     */
    public function create(): View
    {
        return view('post.index');
    }

    /**
     * 投稿処理
     */
    public function store(PostRequest $request): RedirectResponse
    {
        // バリデーションは PostRequest が担当する。
        // 失敗した場合はここに到達せず、入力内容付きで投稿画面に戻される。
        $request->user()->posts()->create([
            'content' => $request->validated()['postContent'],
        ]);

        return redirect()->route('home');
    }

    /**
     * 投稿詳細画面遷移
     *
     * 存在しない ID は自動的に 404 になる。
     */
    public function show(Post $post): View
    {
        $post->load('user');

        return view('post.detail', [
            'post' => $post,
            'user' => $post->user,
        ]);
    }

    /**
     * 投稿編集画面遷移
     */
    public function edit(Post $post): View
    {
        // 投稿者本人でなければ 403
        $this->authorize('update', $post);

        return view('post.edit', compact('post'));
    }

    /**
     * 投稿編集処理
     */
    public function update(PostRequest $request, Post $post): RedirectResponse
    {
        // 画面を経由せず直接 PUT された場合もここで弾かれる
        $this->authorize('update', $post);

        $post->update([
            'content' => $request->validated()['postContent'],
        ]);

        return redirect()->route('posts.show', $post);
    }

    /**
     * 投稿削除処理
     */
    public function destroy(Post $post): RedirectResponse
    {
        $this->authorize('delete', $post);

        // 論理削除
        $post->is_deleted = true;
        $post->save();

        return redirect()->route('home');
    }
}
