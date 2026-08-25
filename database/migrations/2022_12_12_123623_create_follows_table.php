<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('follows', function (Blueprint $table) {
            $table->id();
            /** フォローする側 */
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            /** フォローされる側 */
            $table->foreignId('follow_user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();

            // 同じ相手を二重フォローできないようにする
            $table->unique(['user_id', 'follow_user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('follows');
    }
};
