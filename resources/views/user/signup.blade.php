{{-- 新規登録画面 --}}
@extends('layouts.app')

@section('title', '新規登録')
@section('page-class', 'signup-page')

{{-- 未ログイン画面ではフッターの「投稿する」を出さない --}}
@section('footer')
@endsection

@section('content')
<h2>課題: ここに新規登録ページを作ること</h2>

<h3>必要なフォームパーツ</h3>
<ul>
    <li>Email入力フォーム</li>
    <li>アカウント名入力フォーム</li>
    <li>パスワード入力フォーム</li>
    <li>登録ボタン</li>
</ul>

<h3>バリデーションルール</h3>

<h4>メールアドレス</h4>
<ul>
    <li>●●●@×××の形式になっていること</li>
    <li>半角英数、記号のみを許容すること</li>
</ul>

<h4>パスワード</h4>
<ul>
    <li>半角英数、記号のみを許容すること</li>
    <li>8文字以上であること</li>
</ul>

<p>JavascriptとUserControllerの両方で同じバリデーションを設けること</p>
@endsection
