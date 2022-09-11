<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePersonalInformationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('personal_information', function (Blueprint $table) {

            // カラム情報
            $table->increments('id');
            $table->unsignedInteger('user_id')
                ->comment('従業員ID');
            $table->tinyInteger('sex')
                ->nullable($value = false)
                ->comment('性別 0: 男性 1: 女性');
            $table->date('birthday')
                ->nullable($value = false)
                ->comment('生年月日');
            $table->string('address', 255)
                ->nullable($value = false)
                ->comment('住所');
            $table->string('telephone', 255)
                ->nullable($value = false)
                ->comment('電話番号');
            $table->string('mail', 255)
                ->nullable($value = false)
                ->comment('メールアドレス');
            $table->longText('memo')
                ->nullable($value = true)
                ->comment('メモ');
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
        Schema::dropIfExists('personal_information');
    }
}
