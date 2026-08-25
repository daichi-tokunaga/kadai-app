<?php

namespace Database\Seeders;

use App\Models\Post;
use DateTime;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Post::create([
            'user_id' => 2,
            'content' => '完璧を目指すよりもとにかくやってしまうことだ',
            'created_at' => new DateTime('2020-11-01 00:00:00'),
        ]);
        Post::create([
            'user_id' => 2,
            'content' => '「ジョーのデバッグの法則」というのがあります。それは、すべてのバグは最後にプログラムを修正した個所からプラスマイナス3ステートメント以内にある、というものです',
            'created_at' => new DateTime('2020-11-02 00:00:00'),
        ]);
        Post::create([
            'user_id' => 2,
            'content' => 'もしそれがよい考えなら、思い切ってそれをしなさい。許可をもらうよりも、謝るほうが簡単だから',
            'created_at' => new DateTime('2020-11-03 00:00:00'),
        ]);
        Post::create([
            'user_id' => 2,
            'content' => 'コンピュータはとても高速にとても正確な間違いをおかす。',
            'created_at' => new DateTime('2020-11-04 00:00:00'),
        ]);
        Post::create([
            'user_id' => 2,
            'content' => '私のソフトウェアにはバグが無い。その場に応じてランダムな仕様が生み出されるだけだ。',
            'created_at' => new DateTime('2020-11-05 00:00:00'),
        ]);
        Post::create([
            'user_id' => 2,
            'content' => '実際、悪いプログラマーと良いプログラマーの違いは、自分のコードとデータ構造のどちらをより重要と考えるかということだと主張します。 悪いプログラマーはコードを心配します。 優れたプログラマーは、データ構造とその関係について心配しています。',
            'created_at' => new DateTime('2020-11-06 00:00:00'),
        ]);
        Post::create([
            'user_id' => 2,
            'content' => '新しいプログラミング言語を学ぶ唯一の方法は、その言語でプログラムを書くことです。',
            'created_at' => new DateTime('2020-11-07 00:00:00'),
        ]);
        Post::create([
            'user_id' => 2,
            'content' => 'たぶん動くと思うからリリースしようぜ',
            'created_at' => new DateTime('2020-11-08 00:00:00'),
        ]);
        Post::create([
            'user_id' => 2,
            'content' => 'シンプルであることを保て。',
            'created_at' => new DateTime('2020-11-09 00:00:00'),
        ]);
        Post::create([
            'user_id' => 2,
            'content' => 'あなたたちの多くはプログラマの美徳をよく知っている。
            もちろんこの三つ、怠惰、短気、傲慢。',
            'created_at' => new DateTime('2020-11-10 00:00:00'),
        ]);
        Post::create([
            'user_id' => 3,
            'content' => '重要だと思えることならば、成功する確率が低くてもそれをやるべきである',
            'created_at' => new DateTime('2020-11-01 01:00:00'),
        ]);
        Post::create([
            'user_id' => 3,
            'content' => '変化は恐れずに受け入れなければならない',
            'created_at' => new DateTime('2020-11-03 02:00:00'),
        ]);
        Post::create([
            'user_id' => 3,
            'content' => 'アイデアを実行することはアイデアを思い付くより難しい',
            'created_at' => new DateTime('2020-11-03 02:30:00'),
        ]);
        Post::create([
            'user_id' => 3,
            'content' => '新しい舞台に立つことを恐れるな',
            'created_at' => new DateTime('2020-11-04 00:10:00'),
        ]);
        Post::create([
            'user_id' => 3,
            'content' => '会社を作ることは、ケーキを焼くようなもの。全ての材料を正しい割合で入れなければならない',
            'created_at' => new DateTime('2020-11-05 03:10:00'),
        ]);
        Post::create([
            'user_id' => 3,
            'content' => '一緒に働いている人を好きかどうかはとても大切だ',
            'created_at' => new DateTime('2020-11-06 10:10:00'),
        ]);
        Post::create([
            'user_id' => 3,
            'content' => '耳の痛い意見を聞くことは大事。とくに友人がそのようなことを言ったら、耳を傾けるべき',
            'created_at' => new DateTime('2020-11-07 05:20:00'),
        ]);
        Post::create([
            'user_id' => 3,
            'content' => '彼は解雇されました',
            'created_at' => new DateTime('2020-11-08 00:10:00'),
        ]);
        Post::create([
            'user_id' => 3,
            'content' => 'これまで週80時間働いていたのを、120時間働くだけだ',
            'created_at' => new DateTime('2020-11-08 00:11:00'),
        ]);
        Post::create([
            'user_id' => 3,
            'content' => '最後には太陽が拡張して地球上の全生物を破壊する',
            'created_at' => new DateTime('2020-11-10 20:14:00'),
        ]);
    }
}
