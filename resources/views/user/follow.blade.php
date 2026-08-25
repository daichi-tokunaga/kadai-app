{{-- フォロー/フォロワーリスト画面 --}}
@extends('layouts.app')

@section('title', 'フォロー/フォロワーリスト')
@section('page-class', 'follow-page')

@section('content')
<a href="{{ route('users.show', $user) }}">
    <div class="user">{{ $user->name }}</div>
</a>

{{-- どちらのタブを開くかはサーバー側($activeTab)で決まる。
     JavaScript は切り替えのみを担当し、無効時も初期表示は崩れない。 --}}
<div class="tab" id="follow-tabs">
    <button type="button" id="follow-tab"
        class="tab-item follow-tab {{ $activeTab === 'follow' ? 'selected' : '' }}"
        data-tab="follow">
        フォロー
    </button>
    <button type="button" id="follower-tab"
        class="tab-item follower-tab {{ $activeTab === 'follower' ? 'selected' : '' }}"
        data-tab="follower">
        フォロワー
    </button>
</div>

<div id="follow-list" class="follow-list user-list {{ $activeTab === 'follow' ? '' : 'hidden' }}">
    @forelse ($user->followings as $followUser)
    <a href="{{ route('users.show', $followUser) }}">
        <div class="user-list-item">
            <img class="user-icon" src="{{ asset('/img/user_icon.png') }}" alt="" />
            <div class="info">
                <div class="user-name">{{ $followUser->name }}</div>
                <div class="biography">{{ $followUser->biography }}</div>
            </div>
        </div>
    </a>
    @empty
    <div class="empty-message">フォロー中のユーザーはいません。</div>
    @endforelse
</div>

<div id="follower-list" class="follower-list user-list {{ $activeTab === 'follower' ? '' : 'hidden' }}">
    @forelse ($user->followers as $followerUser)
    <a href="{{ route('users.show', $followerUser) }}">
        <div class="user-list-item">
            <img class="user-icon" src="{{ asset('/img/user_icon.png') }}" alt="" />
            <div class="info">
                <div class="user-name">{{ $followerUser->name }}</div>
                <div class="biography">{{ $followerUser->biography }}</div>
            </div>
        </div>
    </a>
    @empty
    <div class="empty-message">フォロワーはいません。</div>
    @endforelse
</div>
@endsection
