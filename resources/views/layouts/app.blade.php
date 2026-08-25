<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <title>kadai-app | @yield('title')</title>

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('/css/reset.css') }}" />
    <link rel="stylesheet" href="{{ asset('/css/app.css') }}" />
</head>

<body>
    <x-header />

    <div class="page @yield('page-class')">
        @yield('content')
    </div>

    {{-- フッターを出したくない画面は @section('footer') を空で上書きする --}}
    @section('footer')
    <x-footer />
    @show

    <script src="{{ asset('/js/app.js') }}"></script>
    @stack('scripts')
</body>

</html>
