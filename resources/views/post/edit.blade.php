{{-- 投稿編集画面 --}}
@extends('layouts.app')

@section('title', '投稿編集')
@section('page-class', 'post-edit-page')

@section('content')
<form id="post-form" action="{{ route('posts.update', $post) }}" method="post" novalidate
    data-max-length="{{ \App\Models\Post::MAX_CONTENT_LENGTH }}">
    @csrf
    @method('PUT')

    <div class="title">投稿を編集</div>

    <div class="post">
        <textarea name="postContent" id="post-content" cols="30" rows="10">{{ old('postContent', $post->content) }}</textarea>
    </div>

    <div class="counter" id="post-counter"></div>

    <div class="save-button">
        <button class="button-white" type="submit">変更を保存</button>
    </div>

    <x-error-messages />
    <div class="error-message font-red" id="post-client-errors"></div>
</form>
@endsection
