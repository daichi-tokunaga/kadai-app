{{-- 投稿画面 --}}
@extends('layouts.app')

@section('title', '投稿')
@section('page-class', 'post-page')

{{-- 投稿画面ではフッターの「投稿する」を出さない --}}
@section('footer')
@endsection

@section('content')
<form class="form" action="{{ route('posts.store') }}" method="post">
    @csrf

    <textarea name="postContent" id="post-content" cols="30" rows="5"
        placeholder="いまどうしてる?">{{ old('postContent') }}</textarea>

    <div class="post-button">
        <button class="button-white" type="submit">投稿する</button>
    </div>
</form>
@endsection
