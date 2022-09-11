<?php

use Illuminate\Database\Seeder;

class PersonalInformationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('personal_information')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        // 性別は0: 男性 1: 女性 とする
        DB::table('personal_information')->insert([
            [
                'user_id' => 1,
                'sex' => 0,
                'birthday' => '1991-05-23 00:00:00',
                'address' => '東京都新宿区大久保2-2-12 2F',
                'telephone' => '0332092622',
                'mail' => 'yuji.ishibashi@fox-hound.jp',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'user_id' => 2,
                'sex' => 0,
                'birthday' => '2000-01-01 00:00:00',
                'address' => '東京都新宿区大久保2-2-12 2F',
                'telephone' => '00000000001',
                'mail' => 'ryo.yoshimine@fox-hound.tech',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'user_id' => 3,
                'sex' => 0,
                'birthday' => '2000-02-02 00:00:00',
                'address' => '東京都新宿区大久保2-2-12 2F',
                'telephone' => '00000000002',
                'mail' => 'yasunori.mizutani@fox-hound.tech',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'user_id' => 4,
                'sex' => 0,
                'birthday' => '2000-03-03 00:00:00',
                'address' => '東京都新宿区大久保2-2-12 2F',
                'telephone' => '00000000003',
                'mail' => 'ryo.okamoto@fox-hound.tech',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'user_id' => 5,
                'sex' => 0,
                'birthday' => '2000-04-04 00:00:00',
                'address' => '東京都新宿区大久保2-2-12 2F',
                'telephone' => '00000000004',
                'mail' => 'akira.tsunematsu@fox-hound.tech',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'user_id' => 6,
                'sex' => 0,
                'birthday' => '2000-05-05 00:00:00',
                'address' => '東京都新宿区大久保2-2-12 2F',
                'telephone' => '00000000005',
                'mail' => 'yuki.kamiya@fox-hound.tech',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],

            [
                'user_id' => 7,
                'sex' => 1,
                'birthday' => '2000-06-06 00:00:00',
                'address' => '東京都新宿区大久保2-2-12 2F',
                'telephone' => '00000000006',
                'mail' => 'kana.tsutsui@fox-hound.tech',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'user_id' => 8,
                'sex' => 0,
                'birthday' => '2000-07-07 00:00:00',
                'address' => '東京都新宿区大久保2-2-12 2F',
                'telephone' => '00000000007',
                'mail' => 'tatsuya.ishii@fox-hound.tech',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'user_id' => 9,
                'sex' => 0,
                'birthday' => '2000-08-08 00:00:00',
                'address' => '東京都新宿区大久保2-2-12 2F',
                'telephone' => '00000000008',
                'mail' => 'rentaro.watanabe@fox-hound.tech',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'user_id' => 10,
                'sex' => 0,
                'birthday' => '2000-09-09 00:00:00',
                'address' => '東京都新宿区大久保2-2-12 2F',
                'telephone' => '00000000009',
                'mail' => 'saito.sato@fox-hound.tech',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'user_id' => 11,
                'sex' => 0,
                'birthday' => '2000-10-10 00:00:00',
                'address' => '東京都新宿区大久保2-2-12 2F',
                'telephone' => '00000000010',
                'mail' => 'hiroki.komiyama@fox-hound.tech',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'user_id' => 12,
                'sex' => 0,
                'birthday' => '2000-11-11 00:00:00',
                'address' => '東京都新宿区大久保2-2-12 2F',
                'telephone' => '00000000011',
                'mail' => 'daichi.yajima@fox-hound.tech',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'user_id' => 13,
                'sex' => 1,
                'birthday' => '2000-12-12 00:00:00',
                'address' => '東京都新宿区大久保2-2-12 2F',
                'telephone' => '00000000012',
                'mail' => 'sayaka.takahashi@fox-hound.tech',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
        ]);
    }
}
