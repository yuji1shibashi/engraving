<?php

use Illuminate\Database\Seeder;

class HopeShiftTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws Exception
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('hope_shift')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        DB::table('hope_shift')->insert([
            [
                'user_id' => 1,
                'start_date' => new DateTime('2019-10-24 10:00:00'),
                'end_date' => new DateTime('2019-10-24 19:00:00'),
                'memo' => null,
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'start_date' => new DateTime('2019-10-25 10:00:00'),
                'end_date' => new DateTime('2019-10-25 14:00:00'),
                'memo' => null,
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'start_date' => new DateTime('2019-10-25 18:00:00'),
                'end_date' => new DateTime('2019-10-25 22:00:00'),
                'memo' => null,
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'start_date' => new DateTime('2019-10-24 10:00:00'),
                'end_date' => new DateTime('2019-10-24 19:00:00'),
                'memo' => '急用により出勤不可',
                'deleted_flg' => 1
            ],
            [
                'user_id' => 1,
                'start_date' => new DateTime('2019-10-27 10:00:00'),
                'end_date' => new DateTime('2019-10-27 18:00:00'),
                'memo' => null,
                'deleted_flg' => 0
            ],
            [
                'user_id' => 2,
                'start_date' => new DateTime('2019-10-24 10:00:00'),
                'end_date' => new DateTime('2019-10-24 20:30:00'),
                'memo' => null,
                'deleted_flg' => 0
            ],
            [
                'user_id' => 2,
                'start_date' => new DateTime('2019-10-25 08:00:00'),
                'end_date' => new DateTime('2019-10-25 18:30:00'),
                'memo' => '残業できません。',
                'deleted_flg' => 0
            ],
            [
                'user_id' => 2,
                'start_date' => new DateTime('2019-10-26 15:30:00'),
                'end_date' => new DateTime('2019-10-26 20:00:00'),
                'memo' => null,
                'deleted_flg' => 0
            ],
            [
                'user_id' => 3,
                'start_date' => new DateTime('2019-10-26 20:00:00'),
                'end_date' => new DateTime('2019-10-26 23:59:59'),
                'memo' => null,
                'deleted_flg' => 0
            ],
            [
                'user_id' => 3,
                'start_date' => new DateTime('2019-10-27 00:00:00'),
                'end_date' => new DateTime('2019-10-27 05:00:00'),
                'memo' => null,
                'deleted_flg' => 0
            ]
        ]);
    }
}
