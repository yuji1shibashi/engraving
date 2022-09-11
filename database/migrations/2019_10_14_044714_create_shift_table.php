<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShiftTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shift', function (Blueprint $table) {

            // カラム情報
            $table->increments('id');
            $table->unsignedInteger('user_id')
                ->comment('従業員ID');
            $table->dateTime('start_date')
                ->nullable($value = false)
                ->comment('出勤時間');
            $table->dateTime('end_date')
                ->nullable($value = false)
                ->comment('退勤時間');
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
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('RESTRICT')
                ->onUpdate('RESTRICT');

            // テーブル設定
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
        Schema::dropIfExists('shift');
    }
}
