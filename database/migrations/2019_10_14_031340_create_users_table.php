<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {

            // カラム情報
            $table->increments('id');
            $table->string('name', 255)
                ->nullable($value = false)
                ->comment('従業員名');
            $table->string('password', 255)
                ->nullable($value = false)
                ->comment('パスワード');
            $table->tinyInteger('deleted_flg')
                ->nullable($value = false)
                ->default(false)
                ->comment('削除フラグ 0: 無効 1: 有効');
            $table->dateTime('created_at')
                ->nullable($value = false)
                ->default(DB::raw('CURRENT_TIMESTAMP'))
                ->comment('作成日時');
            $table->dateTime('updated_at')
                ->nullable($value = false)
                ->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'))
                ->comment('更新日時');

            // テーブル設定
            $table->engine = 'innoDB';
//            $table->charset = 'utf8';
//            $table->collation = 'utf8_unicode_ci';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
