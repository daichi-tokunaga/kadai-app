{{-- 共通ヘッダー --}}
<header>
    <a href="{{ route('home') }}">
        <div class="logo">Kadai-app</div>
    </a>

    @auth
    <div class="user-wrapper">
        <a href="{{ route('users.show', auth()->user()) }}">
            <div class="user">
                <img class="user-icon" src="{{ asset('/img/user_icon.png') }}" alt="" />
                <div class="username">{{ auth()->user()->name }}</div>
            </div>
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="post">
            @csrf
            <button type="submit" class="logout" data-confirm="ログアウトしますか?">
                ログアウト
            </button>
        </form>
    </div>
    @else
    <div class="menu">
        <div class="menu-item"><a href="{{ route('login') }}">ログイン</a></div>
        <div class="menu-item"><a href="{{ route('signup') }}">新規登録</a></div>
    </div>
    @endauth
</header>
