<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller,
    Session;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * ホーム画面遷移
     */
    public function index(Request $request)
    {
        // セッションにログイン情報があるか確認
        if (!Session::exists('user')) {
            // ログインしていなければログインページへ
            return redirect('/login');
        }

        // ログイン中のユーザーの情報を取得する
        $loginUser = Session::get('user');

        // フォローしているユーザーを取得
        $users = $loginUser->followUsers();
        // ログインしているユーザー自身も表示に含める
        array_push($users, $loginUser);
        // 各ユーザーの投稿を取得
        $posts = [];
        foreach ($users as $user) {
            foreach ($user->posts() as $post) {
                array_push($posts, array('user' => $user, 'post' => $post));
            }
        }

        // 投稿を時系列順に並べ替え
        if (count($posts) > 0) {
            $posts = $this->sort($posts);
        }
        
        // 画面表示
        return view('home', compact('posts'));
    }

    /**
     * ホームに表示する投稿を時系列順に並べ替え
     */
    private function sort($array)
    {
        foreach ($array as $key => $value) {
            $standard_key_array[$key] = $value['post']['created_at'];
        }
        array_multisort($standard_key_array, SORT_DESC, $array);

        return $array;
    }
}
