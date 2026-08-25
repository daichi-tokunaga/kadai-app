{{-- ホーム画面 --}}
@extends('layouts.app')

@section('title', 'ホーム画面')
@section('page-class', 'home-page')

@section('content')
<div class="page-title">ホーム</div>

<div class="post-list">
    @forelse ($posts as $post)
    <div class="post">
        <a href="{{ route('users.show', $post->user) }}">
            <img class="user-icon" src="{{ asset('/img/user_icon.png') }}" alt="" />
        </a>
        <div class="container">
            <a href="{{ route('users.show', $post->user) }}">
                <div class="user-name">{{ $post->user->name }}</div>
            </a>
            <a href="{{ route('posts.show', $post) }}">
                <div class="content">{{ $post->content }}</div>
                <div class="time-stamp">{{ $post->created_at }}</div>
            </a>
        </div>
    </div>
    @empty
    <div class="empty-message">
        まだ投稿がありません。<br />
        ほかのユーザーをフォローするか、自分で投稿してみましょう。
    </div>
    @endforelse
</div>

<div class="pagination-wrapper">
    {{ $posts->links() }}
</div>
@endsection
