<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('users')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        //登録ユーザー、パスワード、管理権限（0:一般　1:管理者）を設定
        // 性別は0: 男性 1: 女性 とする
        DB::table('users')->insert([
                [
                    'name' => '石橋祐治',
                    'password' => password_hash('aaaa', PASSWORD_DEFAULT),
                    'deleted_flg' => 0
                ],
                [
                    'name' => '吉峯諒',
                    'password' => password_hash('bbbb', PASSWORD_DEFAULT),
                    'deleted_flg' => 0
                ],
                [
                    'name' => '水谷康徳',
                    'password' => password_hash('cccc', PASSWORD_DEFAULT),
                    'deleted_flg' => 0
                ],
                [
                    'name' => '岡本亮',
                    'password' => password_hash('dddd', PASSWORD_DEFAULT),
                    'deleted_flg' => 0
                ],
                [
                    'name' => '恒松晶',
                    'password' => password_hash('eeee', PASSWORD_DEFAULT),
                    'deleted_flg' => 0
                ],
                [
                    'name' => '神谷祐樹',
                    'password' => password_hash('ffff', PASSWORD_DEFAULT),
                    'deleted_flg' => 0
                ], [
                    'name' => '筒井佳那',
                    'password' => password_hash('gggg', PASSWORD_DEFAULT),
                    'deleted_flg' => 0
                ],
                [
                    'name' => '石井達也',
                    'password' => password_hash('hhhh', PASSWORD_DEFAULT),
                    'deleted_flg' => 0
                ],
                [
                    'name' => '渡部蓮太朗',
                    'password' => password_hash('iiii', PASSWORD_DEFAULT),
                    'deleted_flg' => 0
                ],
                [
                    'name' => '佐藤才斗',
                    'password' => password_hash('jjjj', PASSWORD_DEFAULT),
                    'deleted_flg' => 0
                ], [
                    'name' => '小宮山博基',
                    'password' => password_hash('kkkk', PASSWORD_DEFAULT),
                    'deleted_flg' => 0
                ],
                [
                    'name' => '矢島大地',
                    'password' => password_hash('llll', PASSWORD_DEFAULT),
                    'deleted_flg' => 0
                ],
                [
                    'name' => '高橋冴香',
                    'password' => password_hash('mmmm', PASSWORD_DEFAULT),
                    'deleted_flg' => 0
                ],
            ]
        );
    }
}
