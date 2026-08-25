{{-- 投稿画面 --}}
@extends('layouts.app')

@section('title', '投稿')
@section('page-class', 'post-page')

{{-- 投稿画面ではフッターの「投稿する」を出さない --}}
@section('footer')
@endsection

@section('content')
<form class="form" id="post-form" action="{{ route('posts.store') }}" method="post" novalidate
    data-max-length="{{ \App\Models\Post::MAX_CONTENT_LENGTH }}">
    @csrf

    <textarea name="postContent" id="post-content" cols="30" rows="5"
        placeholder="いまどうしてる?">{{ old('postContent') }}</textarea>

    <div class="counter" id="post-counter"></div>

    <div class="post-button">
        <button class="button-white" type="submit">投稿する</button>
    </div>

    {{-- 投稿ボタンの下部にエラーメッセージを表示する --}}
    <x-error-messages />
    <div class="error-message font-red" id="post-client-errors"></div>
</form>
@endsection
