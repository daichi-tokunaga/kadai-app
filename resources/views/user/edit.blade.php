{{-- プロフィール編集画面 --}}
@extends('layouts.app')

@section('title', 'プロフィール編集')
@section('page-class', 'user-edit-page')

@section('content')
<form action="{{ route('users.update', $user) }}" method="post">
    @csrf
    @method('PUT')

    <div class="user-info">
        <div class="user-name">
            <label for="username">ユーザー名</label>
            <input class="name" type="text" id="username" name="username"
                value="{{ old('username', $user->name) }}" />
        </div>

        <div class="biography">
            <label for="biography">自己紹介</label>
            <textarea name="biography" id="biography" cols="30" rows="10">{{ old('biography', $user->biography) }}</textarea>
        </div>
    </div>

    <x-error-messages />

    <div class="save-button">
        <button class="button-white" type="submit">変更を保存</button>
    </div>
</form>
@endsection
