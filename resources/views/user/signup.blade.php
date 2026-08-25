{{-- 新規登録画面 --}}
@extends('layouts.app')

@section('title', '新規登録')
@section('page-class', 'form-page')

{{-- 未ログイン画面ではフッターの「投稿する」を出さない --}}
@section('footer')
@endsection

@section('content')
{{--
    バリデーションルールはサーバー側(SignupRequest)を唯一の正とし、
    data 属性経由で JavaScript に渡すことで二重管理を避けている。
--}}
<form class="form" id="signup-form" action="{{ route('signup') }}" method="post" novalidate
    data-ascii-pattern="{{ $rules['asciiPattern'] }}"
    data-email-pattern="{{ $rules['emailPattern'] }}"
    data-password-min-length="{{ $rules['passwordMinLength'] }}">
    @csrf

    <div class="title">新規登録</div>

    <div class="form-item email">
        <label for="email">Email</label>
        <input type="text" id="email" name="email" value="{{ old('email') }}" />
    </div>

    <div class="form-item name">
        <label for="name">アカウント名</label>
        <input type="text" id="name" name="name" value="{{ old('name') }}" />
    </div>

    <div class="form-item password">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" />
    </div>

    {{-- サーバー側のエラー(メールアドレス重複を含む)とクライアント側のエラーを
         どちらも「新規登録」ボタンの上に表示する --}}
    <x-error-messages />
    <div class="error-message font-red" id="signup-client-errors"></div>

    <div class="submit-button">
        <button class="button-white" type="submit">新規登録</button>
    </div>

    <a href="{{ route('login') }}">
        <div class="form-link">すでにアカウントをお持ちの方はこちら</div>
    </a>
</form>
@endsection
