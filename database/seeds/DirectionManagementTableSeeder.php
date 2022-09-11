<?php

use Illuminate\Database\Seeder;

class DirectionManagementTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('direction_management')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        //登録ユーザー、パスワード、管理権限（0:一般　1:管理者）を設定
        // 性別は0: 男性 1: 女性 とする
        DB::table('direction_management')->insert([
                [
                    'direction' => '元気な挨拶ができること',
                    'type' => 1,
                    'deleted_flg' => 0
                ],
                [
                    'direction' => '丁寧な言葉遣いができること',
                    'type' => 1,
                    'deleted_flg' => 0
                ],
                [
                    'direction' => '手洗いうがいがきちんとできること',
                    'type' => 1,
                    'deleted_flg' => 0
                ],
                [
                    'direction' => '空いた食器はすぐに片すこと',
                    'type' => 1,
                    'deleted_flg' => 0
                ],
                [
                    'direction' => 'お冷と食器を正しい人数分用意すること',
                    'type' => 1,
                    'deleted_flg' => 0
                ],
                [
                    'direction' => 'テーブルの片づけがきちんとできること',
                    'type' => 1,
                    'deleted_flg' => 0
                ],
                [
                    'direction' => 'お店を常にきれいにできること',
                    'type' => 1,
                    'deleted_flg' => 0
                ],
                [
                    'direction' => 'お客様のご案内ができること',
                    'type' => 2,
                    'deleted_flg' => 0
                ],
                [
                    'direction' => 'オーダーを正確に取れること',
                    'type' => 2,
                    'deleted_flg' => 0
                ],
                [
                    'direction' => '料理を正しい手順で提供できること',
                    'type' => 2,
                    'deleted_flg' => 0
                ],
                [
                    'direction' => 'レジ打ちが正確にできること',
                    'type' => 2,
                    'deleted_flg' => 0
                ],
                [
                    'direction' => 'ホールと厨房の連携がきちんとできること',
                    'type' => 3,
                    'deleted_flg' => 0
                ],
                [
                    'direction' => 'お客様のテーブル状況が把握できること',
                    'type' => 3,
                    'deleted_flg' => 0
                ],
                [
                    'direction' => '限定メニューをお客様に勧めることができること',
                    'type' => 3,
                    'deleted_flg' => 0
                ],
                [
                    'direction' => 'ドリンクやデザート素早く作れること',
                    'type' => 3,
                    'deleted_flg' => 0
                ],
                [
                    'direction' => '新人育成ができること',
                    'type' => 3,
                    'deleted_flg' => 0
                ]
            ]
        );
    }
}
