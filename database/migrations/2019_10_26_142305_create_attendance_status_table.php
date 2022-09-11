<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttendanceStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendance_status', function (Blueprint $table) {
            // カラム情報
            $table->increments('id');
            $table->unsignedInteger('user_id')
                ->comment('従業員ID');
            $table->dateTime('date')
                ->nullable($value = false)
                ->comment('記録日時');
            $table->tinyInteger('type')
                ->nullable($value = false)
                ->comment('打刻タイプ 1: 出勤, 2: 退勤, 3: 休憩開始, 4: 休憩終了');
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
            $table->index('date');

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
        Schema::dropIfExists('attendance_status');
    }
}
