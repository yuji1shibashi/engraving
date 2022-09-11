<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDirectionManagementTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('direction_management', function (Blueprint $table) {
            // カラム情報
            $table->increments('id');
            $table->string('direction', 255)
                ->comment('目標');
            $table->tinyInteger('type')
                ->nullable($value = false)
                ->comment('種別 1: 初級, 2: 中級, 3: 上級');
            $table->tinyInteger('deleted_flg')
                ->nullable($value = false)
                ->default(false)
                ->comment('削除フラグ 0: 無効 1: 有効');

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
        Schema::dropIfExists('direction_management');
    }
}
