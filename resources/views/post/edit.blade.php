{{-- 投稿編集画面 --}}
@extends('layouts.app')

@section('title', '投稿編集')
@section('page-class', 'post-edit-page')

@section('content')
<form action="{{ route('posts.update', $post) }}" method="post">
    @csrf
    @method('PUT')

    <div class="title">投稿を編集</div>

    <div class="post">
        <textarea name="postContent" id="post-content" cols="30" rows="10">{{ old('postContent', $post->content) }}</textarea>
    </div>

    <div class="save-button">
        <button class="button-white" type="submit">変更を保存</button>
    </div>
</form>
@endsection
