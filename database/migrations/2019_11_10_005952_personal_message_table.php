<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PersonalMessageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('personal_message', function (Blueprint $table) {

            // カラム情報
            $table->increments('id');
            $table->longText('message')
                ->nullable($value = true)
                ->comment('メッセージ');
            $table->unsignedInteger('to_user_id')
                ->nullable($value = false)
                ->comment('対象ユーザーID');
            $table->unsignedInteger('from_user_id')
                ->nullable($value = false)
                ->comment('作成者ユーザーID');
            $table->dateTime('start_date')
                ->nullable($value = false)
                ->comment('掲載開始日時');
            $table->dateTime('end_date')
                ->nullable($value = false)
                ->comment('掲載終了日時');
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

            // インデックス
            $table->index('start_date');

            // 外部キー制約
            $table->foreign('to_user_id')
                ->references('id')
                ->on('users')
                ->onDelete('RESTRICT')
                ->onUpdate('RESTRICT');
            $table->foreign('from_user_id')
                ->references('id')
                ->on('users')
                ->onDelete('RESTRICT')
                ->onUpdate('RESTRICT');

            // テーブル設定
            $table->engine = 'innoDB';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('personal_message');
    }
}
