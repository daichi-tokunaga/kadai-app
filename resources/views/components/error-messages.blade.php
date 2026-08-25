{{-- バリデーションエラーの一覧表示(サーバー側) --}}
@if ($errors->any())
<div class="error-message font-red">
    <ul>
        @foreach ($errors->all() as $message)
        <li>{{ $message }}</li>
        @endforeach
    </ul>
</div>
@endif
