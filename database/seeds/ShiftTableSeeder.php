<?php

use Illuminate\Database\Seeder;

class ShiftTableSeeder extends Seeder
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
        DB::table('shift')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        DB::table('shift')->insert([
            [
                'user_id' => 1,
                'start_date' => new DateTime('2019-09-01 10:00:00'),
                'end_date' => new DateTime('2019-09-01 17:00:00'),
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'start_date' => new DateTime('2019-09-03 10:00:00'),
                'end_date' => new DateTime('2019-09-03 12:00:00'),
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'start_date' => new DateTime('2019-09-06 11:00:00'),
                'end_date' => new DateTime('2019-09-06 17:00:00'),
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'start_date' => new DateTime('2019-09-10 10:00:00'),
                'end_date' => new DateTime('2019-09-10 14:00:00'),
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'start_date' => new DateTime('2019-09-11 10:00:00'),
                'end_date' => new DateTime('2019-09-11 14:00:00'),
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'start_date' => new DateTime('2019-09-16 10:00:00'),
                'end_date' => new DateTime('2019-09-16 19:00:00'),
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'start_date' => new DateTime('2019-09-21 10:00:00'),
                'end_date' => new DateTime('2019-09-21 19:00:00'),
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'start_date' => new DateTime('2019-09-22 10:00:00'),
                'end_date' => new DateTime('2019-09-22 20:00:00'),
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'start_date' => new DateTime('2019-09-24 10:00:00'),
                'end_date' => new DateTime('2019-09-24 19:00:00'),
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'start_date' => new DateTime('2019-09-28 15:00:00'),
                'end_date' => new DateTime('2019-09-28 21:00:00'),
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'start_date' => new DateTime('2019-09-30 10:00:00'),
                'end_date' => new DateTime('2019-09-30 19:00:00'),
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'start_date' => new DateTime('2019-10-01 10:00:00'),
                'end_date' => new DateTime('2019-10-01 17:00:00'),
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'start_date' => new DateTime('2019-10-02 10:00:00'),
                'end_date' => new DateTime('2019-10-02 14:00:00'),
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'start_date' => new DateTime('2019-10-05 12:00:00'),
                'end_date' => new DateTime('2019-10-05 20:00:00'),
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'start_date' => new DateTime('2019-10-07 10:00:00'),
                'end_date' => new DateTime('2019-10-07 15:00:00'),
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'start_date' => new DateTime('2019-10-08 10:00:00'),
                'end_date' => new DateTime('2019-10-08 16:00:00'),
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'start_date' => new DateTime('2019-10-10 10:00:00'),
                'end_date' => new DateTime('2019-10-10 15:00:00'),
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'start_date' => new DateTime('2019-10-12 13:00:00'),
                'end_date' => new DateTime('2019-10-12 17:00:00'),
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'start_date' => new DateTime('2019-10-14 10:00:00'),
                'end_date' => new DateTime('2019-10-14 17:00:00'),
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'start_date' => new DateTime('2019-10-21 10:00:00'),
                'end_date' => new DateTime('2019-10-21 17:00:00'),
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'start_date' => new DateTime('2019-10-24 10:00:00'),
                'end_date' => new DateTime('2019-10-24 18:00:00'),
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'start_date' => new DateTime('2019-10-25 10:00:00'),
                'end_date' => new DateTime('2019-10-25 14:00:00'),
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'start_date' => new DateTime('2019-10-25 18:00:00'),
                'end_date' => new DateTime('2019-10-25 22:00:00'),
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'start_date' => new DateTime('2019-10-24 10:00:00'),
                'end_date' => new DateTime('2019-10-24 19:00:00'),
                'deleted_flg' => 1
            ],
            [
                'user_id' => 1,
                'start_date' => new DateTime('2019-10-27 10:00:00'),
                'end_date' => new DateTime('2019-10-27 18:00:00'),
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'start_date' => new DateTime('2019-11-01 10:00:00'),
                'end_date' => new DateTime('2019-11-01 17:00:00'),
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'start_date' => new DateTime('2019-11-03 10:00:00'),
                'end_date' => new DateTime('2019-11-03 14:00:00'),
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'start_date' => new DateTime('2019-11-05 10:00:00'),
                'end_date' => new DateTime('2019-11-05 17:00:00'),
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'start_date' => new DateTime('2019-11-08 12:00:00'),
                'end_date' => new DateTime('2019-11-08 22:00:00'),
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'start_date' => new DateTime('2019-11-12 10:00:00'),
                'end_date' => new DateTime('2019-11-12 14:00:00'),
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'start_date' => new DateTime('2019-11-13 10:00:00'),
                'end_date' => new DateTime('2019-11-13 20:00:00'),
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'start_date' => new DateTime('2019-11-14 10:00:00'),
                'end_date' => new DateTime('2019-11-14 18:00:00'),
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'start_date' => new DateTime('2019-11-16 14:00:00'),
                'end_date' => new DateTime('2019-11-16 16:00:00'),
                'deleted_flg' => 0
            ],
            [
                'user_id' => 2,
                'start_date' => new DateTime('2019-10-24 11:00:00'),
                'end_date' => new DateTime('2019-10-24 19:30:00'),
                'deleted_flg' => 0
            ],
            [
                'user_id' => 2,
                'start_date' => new DateTime('2019-10-25 10:00:00'),
                'end_date' => new DateTime('2019-10-25 18:30:00'),
                'deleted_flg' => 0
            ],
            [
                'user_id' => 2,
                'start_date' => new DateTime('2019-10-26 15:00:00'),
                'end_date' => new DateTime('2019-10-26 20:00:00'),
                'deleted_flg' => 0
            ],
            [
                'user_id' => 3,
                'start_date' => new DateTime('2019-10-26 20:00:00'),
                'end_date' => new DateTime('2019-10-26 23:59:59'),
                'deleted_flg' => 0
            ],
            [
                'user_id' => 3,
                'start_date' => new DateTime('2019-10-27 00:00:00'),
                'end_date' => new DateTime('2019-10-27 05:00:00'),
                'deleted_flg' => 0
            ],
        ]);
    }
}
