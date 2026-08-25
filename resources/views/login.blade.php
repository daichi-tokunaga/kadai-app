{{-- ログイン画面 --}}
@extends('layouts.app')

@section('title', 'ログイン')
@section('page-class', 'form-page')

{{-- 未ログイン画面ではフッターの「投稿する」を出さない --}}
@section('footer')
@endsection

@section('content')
<form class="form" action="{{ route('login') }}" method="post">
    @csrf

    <div class="title">ログイン</div>

    <div class="form-item email">
        <label for="email">Email</label>
        <input type="text" id="email" name="email" value="{{ old('email') }}" />
    </div>

    <div class="form-item password">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" />
    </div>

    <x-error-messages />

    <div class="submit-button">
        <button class="button-white" type="submit">login</button>
    </div>

    <a href="{{ route('signup') }}">
        <div class="form-link">アカウントをお持ちでない方はこちら</div>
    </a>
</form>
@endsection
