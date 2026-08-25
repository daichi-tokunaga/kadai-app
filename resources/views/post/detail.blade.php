{{-- 投稿詳細画面 --}}
@extends('layouts.app')

@section('title', '投稿詳細')
@section('page-class', 'post-detail-page')

@section('content')
<div class="post">
    <a href="{{ route('users.show', $user) }}">
        <div class="user-info">
            <img class="user-icon" src="{{ asset('/img/user_icon.png') }}" alt="" />
            <div class="user-name">{{ $user->name }}</div>
        </div>
    </a>
    <a href="{{ route('posts.show', $post) }}">
        <div class="content">{{ $post->content }}</div>
        <div class="time-stamp">{{ $post->created_at }}</div>
    </a>

    <div class="menu">
        <div class="menu-item font-blue">
            <a href="{{ route('posts.edit', $post) }}">編集</a>
        </div>
        <form action="{{ route('posts.destroy', $post) }}" method="post">
            @csrf
            @method('DELETE')
            <button type="submit" class="menu-item font-red" data-confirm="削除しますか?">
                削除
            </button>
        </form>
    </div>
</div>
@endsection
