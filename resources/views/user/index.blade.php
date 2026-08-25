{{-- ユーザー画面 --}}
@extends('layouts.app')

@section('title', 'ユーザー')
@section('page-class', 'user-page')

@section('content')
<div class="page-container">
    <div class="user-info">
        <div class="user">
            <div class="user-row">
                <img class="user-icon" src="{{ asset('/img/user_icon.png') }}" alt="" />

                @if ($isOwnPage)
                <a href="{{ route('users.edit', $user) }}">
                    <button class="button-white edit" type="button">編集</button>
                </a>
                @elseif ($isFollowing)
                {{-- フォロー解除。confirm でキャンセルした場合に送信されないよう
                     submit イベント側で制御する(app.js の data-confirm) --}}
                <form action="{{ route('follows.destroy', $user) }}" method="post">
                    @csrf
                    @method('DELETE')
                    <button class="button-white" type="submit" data-confirm="フォローを解除しますか?">
                        フォロー済み
                    </button>
                </form>
                @else
                <form action="{{ route('follows.store', $user) }}" method="post">
                    @csrf
                    <button class="button-black" type="submit">フォロー</button>
                </form>
                @endif
            </div>
            <div class="user-name">{{ $user->name }}</div>
        </div>

        <div class="biography">{{ $user->biography }}</div>

        <div class="follow-info">
            <a href="{{ route('users.followings', $user) }}">
                <div class="follow">{{ $user->followings_count }} フォロー中</div>
            </a>
            <a href="{{ route('users.followers', $user) }}">
                <div class="follower">{{ $user->followers_count }} フォロワー</div>
            </a>
        </div>
    </div>

    <div class="post-list">
        <div class="title">投稿一覧</div>

        @forelse ($posts as $post)
        <a href="{{ route('posts.show', $post) }}">
            <div class="post">
                <img class="user-icon" src="{{ asset('/img/user_icon.png') }}" alt="" />
                <div class="container">
                    <div class="user-name">{{ $user->name }}</div>
                    <div class="content">{{ $post->content }}</div>
                    <div class="time-stamp">{{ $post->created_at }}</div>
                </div>
            </div>
        </a>
        @empty
        <div class="empty-message">まだ投稿がありません。</div>
        @endforelse

        <div class="pagination-wrapper">
            {{ $posts->links() }}
        </div>
    </div>
</div>
@endsection
