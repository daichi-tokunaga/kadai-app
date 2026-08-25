> **このブランチは模範解答(`solution`)です。**
> 課題に取り組む場合は `master` ブランチを使ってください。

<h1>初回のみやること</h1>

このリポジトリ(徳永のアカウントにあるリポジトリ)を自身のアカウントにフォークして課題を進めること。

<h2>フォーク手順</h2>

1. https://github.com/daichi-tokunaga/kadai-app/fork にアクセス

1. Ownerが自分のアカウント、Repository nameがkadai-appになっていることを確認

1. 画面下のCreate fork(緑のボタン)を押下

1. 自身のアカウントにコピーされるので以降、そこに作ったものをpushしていく


<h1>環境構築</h1>

<h2>1. 準備</h2>

1. 自身のgithubアカウントからリポジトリをcloneしVSCodeで開く

1. VSCode上で[ctrl + @]でターミナルを開く(以降コマンドはここで入力する)(コマンドプロンプトでも可)

<h2>2. プロジェクトのアップデート</h2>

コマンドを実行

```
composer install
```

<h2>3. 環境設定ファイルの作成</h2>

このアプリはデータベースの接続先などの設定を `.env` ファイルから読み込む。
`.env` は環境ごとに内容が変わるためリポジトリには含まれておらず、自分で用意する。

1. `composer install` を実行すると `.env.example` がコピーされて `.env` が作られる。
作られていない場合は手動でコピーする

```
copy .env.example .env
```

1. アプリケーションキーを発行する

```
php artisan key:generate
```

> `.env` に書いたパスワードなどをうっかり公開しないため、
> `.env` は `.gitignore` に登録されている。**このファイルは commit しないこと。**

<h2>4. データベースの構築</h2>

1. xammpのコントロールパネルからapacheとMySQLを起動

1. xammpのコントロールパネル -> MySQLのAdminを押下しPHPMyAdminを起動

1. データベースのマイグレートコマンドを実行

```
php artisan migrate
```

実行すると下記のようにデータベースを作るか確認されるので yes と入力

```
  WARN  The database 'kadai-app' does not exist on the 'mysql' connection.

Would you like to create it? (yes/no) [no]

> yes <=入力
```

1. データベースに初期データを挿入
```
php artisan db:seed
```

> **すでにデータベースを作成済みの場合**
> テーブル定義が変更されているため、作り直しが必要です。
> 下記のコマンドでテーブルの再作成と初期データ投入をまとめて実行できます。
> (既存のデータはすべて消えます)
>
> ```
> php artisan migrate:fresh --seed
> ```

<h2>5. アプリケーションの起動</h2>

<h3>起動</h3>

```
php artisan serve
```

<h3>アクセスURL</h3>

```
localhost:8000
```

<h3>ログイン</h3>

```
e-mail: oca@email.com
password: oca
```

<h2>6. テストの実行</h2>

テストは SQLite のインメモリデータベースで実行されるため、MySQL を起動していなくても動きます。

```
php artisan test
```

- - -

<h1>課題</h1>

- - -

<h2>問題1</h2>

以下の５つのバグを修正しなさい。

- - -

<h3>Bug1</h3>

>内容: ユーザーを押下してユーザー画面に遷移しても別のユーザーの画面に遷移してしまう。  
修正: 正しいユーザーの情報が表示されること。

- - -

<h3>Bug2</h3>

>内容: 投稿詳細画面( /post/detail/{id} )での右下の削除ボタンを押下しても投稿が削除されずに残っている。  
修正: 削除した投稿がタイムライン等に表示されないようにすること。

- - -

<h3>Bug3</h3>


>内容: 画面右上のログアウトボタンを押下しログアウトしてもログイン情報が残ってしまっている。  
修正: ログアウトするとログイン情報がブラウザから削除されること。

- - -

<h3>Bug4</h3>

>内容: 投稿画面( /post )で投稿する際、内容が0文字または140字より多い場合エラーが発生する。  
修正: 文字数が1～140文字の場合のみ投稿処理を行うようバリデーションを追加すること
>
>>備考1: バリデーションでは投稿ボタン下部にエラーメッセージを表示すること

- - -

<h3>Bug5</h3>

> 内容: 投稿詳細画面( /post/detail/{id} )でログインしているユーザー以外の投稿(他人の投稿)も編集できてしまう。  
修正: 他人の投稿詳細画面では「編集」「削除」のボタンは表示しないこと  

- - -
- - -

<h2>問題2</h2>

<h3>新規登録画面を実装すること</h3>

- - -

>ログインしていない状態ではヘッダーに新規登録ボタンが表示されている。
ボタンを押下すると新規登録画面に遷移するのでそこに新たに新規登録機能を実装すること。

- - -


**新規登録画面要件**

>画面には以下のパーツが存在する。
> * メールアドレス入力フォーム
> * アカウント名入力フォーム
> * パスワード入力フォーム
>* 新規登録ボタン

※実装や画面レイアウトはログイン画面を参考にするとよい。

- - -

**機能概要**

>メールアドレス、パスワードを入力した状態で「新規登録」ボタンを押下すると登録処理が開始する。  
登録処理が成功した場合は、そのアカウントにログインしたうえでホーム画面にリダイレクトする。
>>【注意】 メールアドレスはほかのユーザーとかぶってはならないため、データベースに登録する前にすでに存在していないかチェックすること。すでに登録されているアドレスの場合は「新規登録」ボタン上部に「このメールアドレスはすでに使用されています」と表示すること。

- - -

**バリデーション**

>下記ルールにしたがってバリデーションを実装すること

**メールアドレス**
> * ●●●@×××の形式になっていること
> * 半角英数、記号のみを許容すること

**パスワード**

> * 半角英数、記号のみを許容すること
> * 8文字以上であること

- - -

- - -
- - -

<h1>設計メモ</h1>

課題に取り組む前に読むこと。
このアプリは Laravel の標準的な書き方に沿って作られている。
**課題の修正も、下記の書き方に合わせて実装すること。**

- - -

<h2>認証</h2>

Laravel 標準の認証機構(`Auth` ファサード)を使用している。

* `App\Models\User` は `Authenticatable` を継承している
* パスワードは `User` モデルのミューテタで自動的にハッシュ化される(平文はデータベースに入らない)
* ログイン判定は各コントローラではなく `routes/web.php` のミドルウェア(`auth` / `guest`)で行う
  ため、コントローラ内にログイン判定を書く必要はない
* ログイン中のユーザーは `Auth::user()` または `$request->user()` で取得する

- - -

<h2>認可</h2>

「本人だけができる操作」の判定はポリシーに集約している。

| ポリシー | 判定内容 |
| --- | --- |
| `App\Policies\PostPolicy` | 投稿の編集・削除は投稿者本人のみ |
| `App\Policies\UserPolicy` | プロフィール編集は本人のみ / 自分自身はフォローできない |

* コントローラ側は `$this->authorize('update', $post)` で呼び出す(違反時は 403)
* 画面側は `@can('update', $post)` で表示を切り替えられる

- - -

<h2>バリデーション</h2>

フォームリクエスト(`app/Http/Requests/`)に定義する。
コントローラの引数の型を `Request` からフォームリクエストに差し替えるだけで適用される。

| クラス | 対象 |
| --- | --- |
| `LoginRequest` | ログイン |
| `SignupRequest` | 新規登録 |
| `PostRequest` | 投稿 / 投稿編集 |
| `ProfileRequest` | プロフィール編集 |

エラーメッセージの表示には `<x-error-messages />` コンポーネントが使える。
`$errors` に入っているメッセージを一覧で表示する。

JavaScript 側でエラーを表示する場合は `public/js/app.js` の `renderErrors()` が使える。

バリデーションルールを PHP と JavaScript の両方に直接書くと、片方を直し忘れて
食い違いが起きる。`SignupRequest` / `Post::MAX_CONTENT_LENGTH` に定数として定義し、
`data-*` 属性でビューから JavaScript に渡すことで二重管理を避けている。

- - -

<h2>データベース</h2>

| テーブル | 補足 |
| --- | --- |
| `users` | |
| `posts` | `is_deleted` による論理削除。グローバルスコープで全クエリから自動的に除外される |
| `follows` | `users` の多対多の中間テーブル。`(user_id, follow_user_id)` に一意制約があり二重フォローを防ぐ |

* カラム名は Laravel の規約に合わせて `user_id` / `follow_user_id`
* 外部キー制約とインデックスを設定済み
* 文字セットは `utf8mb4`。`utf8`(utf8mb3) では絵文字が保存できず投稿時にエラーになる

<h3>リレーション</h3>

```php
$user->posts        // ユーザーの投稿 (hasMany)
$user->followings   // フォローしているユーザー (belongsToMany)
$user->followers    // フォローされているユーザー (belongsToMany)
$post->user         // 投稿者 (belongsTo)
```

一覧を表示するときは `with()` / `loadCount()` を使い、N+1 クエリを発生させないこと。

- - -

<h2>ルーティング</h2>

* ルートには名前が付いている。リンクは `href="/user/1"` ではなく
  `href="{{ route('users.show', $user) }}"` と書く
* `{user}` / `{post}` はルートモデルバインディングにより、コントローラの引数に
  モデルが直接渡される。存在しない ID は自動的に 404 になるため
  コントローラ側で存在チェックを書く必要はない

- - -

<h2>画面</h2>

* すべての画面は `resources/views/layouts/app.blade.php` を `@extends` する
* CSS は `public/css/app.css` に集約する(各 Blade に `<style>` を書かない)
* JavaScript は `public/js/app.js` に集約する
* 確認ダイアログは `<button data-confirm="...">` と書けば `app.js` が処理する

- - -

<h2>テスト</h2>

```
php artisan test
```

SQLite のインメモリデータベースで実行されるため、MySQL を起動していなくても動く。

- - -

<h2>未整理の項目</h2>

* `package.json` / `vite.config.js` が存在するが、現状 Vite は使用しておらず
  `public/` 配下の CSS / JS を直接読み込んでいる

- - -
- - -

<h1>模範解答の解説</h1>

> このブランチ(`solution`)は課題の解答例です。
> `master` との差分がそのまま「Bug1〜5 と問題2 をどう直したか」になります。
>
> ```
> git diff master solution
> ```

- - -

<h2>Bug1: 別のユーザーの画面が表示される</h2>

`UserController::show()` に `$user = User::find(3);` があり、URL の ID を無視して
常に ID=3 のユーザーを表示していた。この1行を削除するだけでよい。

ルートモデルバインディングにより、引数の `User $user` には
URL の `{user}` に対応するユーザーがすでに入っている。

```php
public function show(Request $request, User $user): View
{
    // $user はルートから自動的に解決済み
```

- - -

<h2>Bug2: 削除した投稿が残る</h2>

`posts.is_deleted` を `true` にはしていたが、取得時に絞り込んでいなかった。

呼び出し側ごとに `where('is_deleted', false)` を書くと必ず書き漏らしが出るため、
`Post` モデルにグローバルスコープとして一度だけ定義する。

```php
protected static function booted(): void
{
    static::addGlobalScope('notDeleted', function (Builder $query) {
        $query->where('is_deleted', false);
    });
}
```

これでタイムライン・ユーザー画面に加えて、ルートモデルバインディングにも効くので
削除済み投稿の詳細画面( /post/detail/{id} )は自動的に 404 になる。

- - -

<h2>Bug3: ログアウトしてもログイン情報が残る</h2>

リダイレクトするだけでセッションを破棄していなかった。

```php
Auth::logout();

// セッション自体を破棄し、CSRF トークンも再発行する
$request->session()->invalidate();
$request->session()->regenerateToken();
```

`Auth::logout()` だけではセッションに残った他のデータが引き継がれてしまうため、
`invalidate()` まで行うのが定石。

- - -

<h2>Bug4: 投稿のバリデーションが無い</h2>

`App\Http\Requests\PostRequest` を追加し、コントローラの引数の型を
`Request` から `PostRequest` に差し替えた。

```php
'postContent' => ['required', 'string', 'max:'.Post::MAX_CONTENT_LENGTH],
```

`TrimStrings` ミドルウェアが前後の空白を除去するため、`required` が
そのまま「1文字以上」の条件になる。

画面側は投稿ボタンの下に `<x-error-messages />` を置いて表示する。
JavaScript 側の検証は `app.js` の `setupPostValidation()` にあり、
上限文字数は `Post::MAX_CONTENT_LENGTH` を `data-max-length` 属性で受け取っている。

- - -

<h2>Bug5: 他人の投稿にも編集・削除ボタンが出る</h2>

`PostPolicy` はすでにあるので、ビューで `@can` を使って表示を切り替えるだけ。

```blade
@can('update', $post)
<div class="menu">
    ...
</div>
@endcan
```

- - -

<h2>問題2: 新規登録</h2>

| ファイル | 役割 |
| --- | --- |
| `App\Http\Requests\SignupRequest` | バリデーション(メールアドレスの重複チェックを含む) |
| `UserController::create()` | 画面表示。JavaScript にルールを渡す |
| `UserController::store()` | 登録 + そのままログイン |
| `resources/views/user/signup.blade.php` | 画面 |
| `public/js/app.js` の `setupSignupValidation()` | クライアント側の検証 |

<h3>バリデーションを二重管理しない</h3>

同じルールを PHP と JavaScript に別々に書くと、片方を直し忘れて食い違う。
`SignupRequest` に定数として定義し、`data-*` 属性でビューから JavaScript に渡している。

```php
// SignupRequest
public const ASCII_PATTERN = '^[\x21-\x7e]+$';
public const EMAIL_PATTERN = '^[^\s@]+@[^\s@]+$';
public const PASSWORD_MIN_LENGTH = 8;
```

```blade
<form id="signup-form"
    data-ascii-pattern="{{ $rules['asciiPattern'] }}"
    data-password-min-length="{{ $rules['passwordMinLength'] }}">
```

```js
const asciiPattern = new RegExp(form.dataset.asciiPattern);
```

<h3>メールアドレスの重複</h3>

`unique:users,email` ルールと、専用のエラーメッセージで対応している。
自分でデータベースを検索して判定する必要はない。

```php
'email.unique' => 'このメールアドレスはすでに使用されています',
```

<h3>パスワード</h3>

`User` モデルのミューテタで自動的にハッシュ化されるため、
コントローラ側では平文のまま渡してよい。

- - -

<h2>テスト</h2>

```
php artisan test
```

`tests/Feature/` に41件。Bug1〜5 に対応するテストにはコメントで対応を記載している。
画面でボタンを隠すだけでは URL を直接叩かれた場合に防げないため、
「直接 PUT / DELETE しても弾かれること」も検証している。
