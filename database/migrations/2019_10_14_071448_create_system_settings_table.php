<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSystemSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('system_settings', function (Blueprint $table) {

            // カラム情報
            $table->increments('id');
            $table->unsignedInteger('user_id')
                ->comment('従業員ID');
            $table->string('language', 255)
                ->nullable($value = false)
                ->comment('言語設定');
            $table->tinyInteger('admin')
                ->nullable($value = false)
                ->comment('権限種別 0: 一般, 1: 管理者');
            $table->dateTime('created_at')
                ->nullable($value = false)
                ->default(DB::raw('CURRENT_TIMESTAMP'))
                ->comment('作成日時');
            $table->dateTime('updated_at')
                ->nullable($value = false)
                ->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'))
                ->comment('更新日時');

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
        Schema::dropIfExists('system_settings');
    }
}
