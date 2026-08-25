<?php

use App\Http\Controllers\FollowController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| 認証状態はここでミドルウェアとしてまとめて指定する。
| 各コントローラ側でログイン判定を書く必要はない。
|
*/

/*-------------------------------------------------------------------------
| 未ログインユーザー専用
| -------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    /** ログイン画面遷移 */
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    /** ログイン処理 */
    Route::post('/login', [LoginController::class, 'store']);

    /** 新規登録画面遷移 */
    Route::get('/signup', [UserController::class, 'create'])->name('signup');
    /** 新規登録処理 */
    Route::post('/signup', [UserController::class, 'store']);
});

/*-------------------------------------------------------------------------
| ログイン必須
| -------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    /*---------------------------------------------------------------------
    | ホーム
    |---------------------------------------------------------------------*/
    Route::get('/', [HomeController::class, 'index'])->name('home');

    /** ログアウト処理 */
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

    /*---------------------------------------------------------------------
    | 投稿
    |---------------------------------------------------------------------*/
    /** 投稿画面遷移 */
    Route::get('/post', [PostController::class, 'create'])->name('posts.create');
    /** 投稿処理 */
    Route::post('/post', [PostController::class, 'store'])->name('posts.store');

    /** 投稿詳細画面遷移 */
    Route::get('/post/detail/{post}', [PostController::class, 'show'])->name('posts.show');

    /** 投稿編集画面遷移 */
    Route::get('/post/edit/{post}', [PostController::class, 'edit'])->name('posts.edit');
    /** 投稿編集処理 */
    Route::put('/post/edit/{post}', [PostController::class, 'update'])->name('posts.update');

    /** 投稿削除処理 */
    Route::delete('/post/delete/{post}', [PostController::class, 'destroy'])->name('posts.destroy');

    /*---------------------------------------------------------------------
    | ユーザー
    |---------------------------------------------------------------------*/
    /** プロフィール編集画面遷移 */
    Route::get('/user/edit/{user}', [UserController::class, 'edit'])->name('users.edit');
    /** プロフィール編集処理 */
    Route::put('/user/edit/{user}', [UserController::class, 'update'])->name('users.update');

    /** フォローリスト画面遷移 */
    Route::get('/user/{user}/follow', [FollowController::class, 'index'])->name('users.followings');
    /** フォロワーリスト画面遷移 */
    Route::get('/user/{user}/follower', [FollowController::class, 'index'])->name('users.followers');

    /** ユーザー画面遷移 */
    Route::get('/user/{user}', [UserController::class, 'show'])->name('users.show');

    /*---------------------------------------------------------------------
    | フォロー
    |---------------------------------------------------------------------*/
    /** フォロー処理 */
    Route::post('/follow/{user}', [FollowController::class, 'store'])->name('follows.store');
    /** フォロー解除処理 */
    Route::delete('/follow/{user}', [FollowController::class, 'destroy'])->name('follows.destroy');
});
