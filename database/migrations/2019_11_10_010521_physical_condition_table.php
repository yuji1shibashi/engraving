<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PhysicalConditionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('physical_condition', function (Blueprint $table) {

            // カラム情報
            $table->increments('id');
            $table->unsignedInteger('shift_id')
                ->nullable($value = false)
                ->comment('シフトID');
            $table->unsignedInteger('shift_user_id')
                ->nullable($value = false)
                ->comment('ユーザーID');
            $table->tinyInteger('symptoms')
                ->nullable($value = false)
                ->comment('体調');
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

            // 外部キー制約
            $table->foreign('shift_user_id')
                ->references('id')
                ->on('users')
                ->onDelete('RESTRICT')
                ->onUpdate('RESTRICT');
            $table->foreign('shift_id')
                ->references('id')
                ->on('shift')
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
        Schema::dropIfExists('physical_condition');
    }
}
