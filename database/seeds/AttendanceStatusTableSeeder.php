<?php

use Illuminate\Database\Seeder;

class AttendanceStatusTableSeeder extends Seeder
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
        DB::table('attendance_status')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        DB::table('attendance_status')->insert([

            [
                'user_id' => 1,
                'date' => new DateTime('2019-09-01 10:00:00'),
                'type' => 1,
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'date' => new DateTime('2019-09-01 13:00:00'),
                'type' => 3,
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'date' => new DateTime('2019-09-01 14:00:00'),
                'type' => 4,
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'date' => new DateTime('2019-09-01 17:00:00'),
                'type' => 2,
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'date' => new DateTime('2019-09-03 10:00:00'),
                'type' => 1,
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'date' => new DateTime('2019-09-03 14:00:00'),
                'type' => 2,
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'date' => new DateTime('2019-09-06 12:00:00'),
                'type' => 1,
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'date' => new DateTime('2019-09-06 16:00:00'),
                'type' => 3,
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'date' => new DateTime('2019-09-06 17:00:00'),
                'type' => 4,
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'date' => new DateTime('2019-09-06 22:00:00'),
                'type' => 2,
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'date' => new DateTime('2019-09-10 10:00:00'),
                'type' => 1,
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'date' => new DateTime('2019-09-10 14:00:00'),
                'type' => 2,
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'date' => new DateTime('2019-09-16 12:00:00'),
                'type' => 1,
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'date' => new DateTime('2019-09-16 16:00:00'),
                'type' => 3,
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'date' => new DateTime('2019-09-16 17:00:00'),
                'type' => 4,
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'date' => new DateTime('2019-09-16 22:00:00'),
                'type' => 2,
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'date' => new DateTime('2019-09-21 10:00:00'),
                'type' => 1,
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'date' => new DateTime('2019-09-21 13:00:00'),
                'type' => 3,
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'date' => new DateTime('2019-09-21 14:00:00'),
                'type' => 4,
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'date' => new DateTime('2019-09-21 19:00:00'),
                'type' => 2,
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'date' => new DateTime('2019-09-22 10:00:00'),
                'type' => 1,
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'date' => new DateTime('2019-09-22 13:00:00'),
                'type' => 3,
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'date' => new DateTime('2019-09-22 14:00:00'),
                'type' => 4,
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'date' => new DateTime('2019-09-22 19:00:00'),
                'type' => 2,
                'deleted_flg' => 0
            ],[
                'user_id' => 1,
                'date' => new DateTime('2019-09-24 10:00:00'),
                'type' => 1,
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'date' => new DateTime('2019-09-24 13:00:00'),
                'type' => 3,
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'date' => new DateTime('2019-09-24 14:00:00'),
                'type' => 4,
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'date' => new DateTime('2019-09-24 19:00:00'),
                'type' => 2,
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'date' => new DateTime('2019-09-28 10:00:00'),
                'type' => 1,
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'date' => new DateTime('2019-09-28 14:00:00'),
                'type' => 2,
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'date' => new DateTime('2019-09-28 18:00:00'),
                'type' => 1,
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'date' => new DateTime('2019-09-28 22:00:00'),
                'type' => 2,
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'date' => new DateTime('2019-09-30 10:00:00'),
                'type' => 1,
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'date' => new DateTime('2019-09-30 14:00:00'),
                'type' => 3,
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'date' => new DateTime('2019-09-30 15:00:00'),
                'type' => 4,
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'date' => new DateTime('2019-09-30 18:00:00'),
                'type' => 2,
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'date' => new DateTime('2019-10-01 10:00:00'),
                'type' => 1,
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'date' => new DateTime('2019-10-01 13:00:00'),
                'type' => 3,
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'date' => new DateTime('2019-10-01 14:00:00'),
                'type' => 4,
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'date' => new DateTime('2019-10-01 17:00:00'),
                'type' => 2,
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'date' => new DateTime('2019-10-02 10:00:00'),
                'type' => 1,
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'date' => new DateTime('2019-10-02 14:00:00'),
                'type' => 2,
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'date' => new DateTime('2019-10-05 12:00:00'),
                'type' => 1,
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'date' => new DateTime('2019-10-05 16:00:00'),
                'type' => 3,
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'date' => new DateTime('2019-10-05 17:00:00'),
                'type' => 4,
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'date' => new DateTime('2019-10-05 22:00:00'),
                'type' => 2,
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'date' => new DateTime('2019-10-07 10:00:00'),
                'type' => 1,
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'date' => new DateTime('2019-10-07 13:00:00'),
                'type' => 3,
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'date' => new DateTime('2019-10-07 14:00:00'),
                'type' => 4,
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'date' => new DateTime('2019-10-07 17:00:00'),
                'type' => 2,
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'date' => new DateTime('2019-10-08 10:00:00'),
                'type' => 1,
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'date' => new DateTime('2019-10-08 13:00:00'),
                'type' => 3,
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'date' => new DateTime('2019-10-08 14:00:00'),
                'type' => 4,
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'date' => new DateTime('2019-10-08 17:00:00'),
                'type' => 2,
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'date' => new DateTime('2019-10-10 10:00:00'),
                'type' => 1,
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'date' => new DateTime('2019-10-10 14:00:00'),
                'type' => 2,
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'date' => new DateTime('2019-10-12 12:00:00'),
                'type' => 1,
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'date' => new DateTime('2019-10-12 16:00:00'),
                'type' => 3,
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'date' => new DateTime('2019-10-12 17:00:00'),
                'type' => 4,
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'date' => new DateTime('2019-10-12 22:00:00'),
                'type' => 2,
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'date' => new DateTime('2019-10-14 10:00:00'),
                'type' => 1,
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'date' => new DateTime('2019-10-14 13:00:00'),
                'type' => 3,
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'date' => new DateTime('2019-10-14 14:00:00'),
                'type' => 4,
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'date' => new DateTime('2019-10-14 17:00:00'),
                'type' => 2,
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'date' => new DateTime('2019-10-21 10:00:00'),
                'type' => 1,
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'date' => new DateTime('2019-10-21 13:00:00'),
                'type' => 3,
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'date' => new DateTime('2019-10-21 14:00:00'),
                'type' => 4,
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'date' => new DateTime('2019-10-21 17:00:00'),
                'type' => 2,
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'date' => new DateTime('2019-10-24 10:00:00'),
                'type' => 1,
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'date' => new DateTime('2019-10-24 13:00:00'),
                'type' => 3,
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'date' => new DateTime('2019-10-24 14:00:00'),
                'type' => 4,
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'date' => new DateTime('2019-10-24 19:00:00'),
                'type' => 2,
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'date' => new DateTime('2019-10-25 10:00:00'),
                'type' => 1,
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'date' => new DateTime('2019-10-25 14:00:00'),
                'type' => 2,
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'date' => new DateTime('2019-10-25 18:00:00'),
                'type' => 1,
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'date' => new DateTime('2019-10-25 22:00:00'),
                'type' => 2,
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'date' => new DateTime('2019-10-27 10:00:00'),
                'type' => 1,
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'date' => new DateTime('2019-10-27 14:00:00'),
                'type' => 3,
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'date' => new DateTime('2019-10-27 15:00:00'),
                'type' => 4,
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'date' => new DateTime('2019-10-27 18:00:00'),
                'type' => 2,
                'deleted_flg' => 0
            ],

            [
                'user_id' => 1,
                'date' => new DateTime('2019-11-01 10:00:00'),
                'type' => 1,
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'date' => new DateTime('2019-11-01 13:00:00'),
                'type' => 3,
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'date' => new DateTime('2019-11-01 14:00:00'),
                'type' => 4,
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'date' => new DateTime('2019-11-01 17:00:00'),
                'type' => 2,
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'date' => new DateTime('2019-11-03 10:00:00'),
                'type' => 1,
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'date' => new DateTime('2019-11-03 14:00:00'),
                'type' => 2,
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'date' => new DateTime('2019-11-08 12:00:00'),
                'type' => 1,
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'date' => new DateTime('2019-11-08 16:00:00'),
                'type' => 3,
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'date' => new DateTime('2019-11-08 17:00:00'),
                'type' => 4,
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'date' => new DateTime('2019-11-08 22:00:00'),
                'type' => 2,
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'date' => new DateTime('2019-11-12 10:00:00'),
                'type' => 1,
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'date' => new DateTime('2019-11-12 14:00:00'),
                'type' => 2,
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'date' => new DateTime('2019-11-13 12:00:00'),
                'type' => 1,
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'date' => new DateTime('2019-11-13 16:00:00'),
                'type' => 3,
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'date' => new DateTime('2019-11-13 17:00:00'),
                'type' => 4,
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'date' => new DateTime('2019-11-13 22:00:00'),
                'type' => 2,
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'date' => new DateTime('2019-11-14 10:00:00'),
                'type' => 1,
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'date' => new DateTime('2019-11-14 13:00:00'),
                'type' => 3,
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'date' => new DateTime('2019-11-14 14:00:00'),
                'type' => 4,
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'date' => new DateTime('2019-11-14 19:00:00'),
                'type' => 2,
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'date' => new DateTime('2019-11-16 10:00:00'),
                'type' => 1,
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'date' => new DateTime('2019-11-16 14:00:00'),
                'type' => 2,
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'date' => new DateTime('2019-11-16 18:00:00'),
                'type' => 1,
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'date' => new DateTime('2019-11-16 22:00:00'),
                'type' => 2,
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'date' => new DateTime('2019-11-17 10:00:00'),
                'type' => 1,
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'date' => new DateTime('2019-11-17 14:00:00'),
                'type' => 3,
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'date' => new DateTime('2019-11-17 15:00:00'),
                'type' => 4,
                'deleted_flg' => 0
            ],
            [
                'user_id' => 1,
                'date' => new DateTime('2019-11-17 18:00:00'),
                'type' => 2,
                'deleted_flg' => 0
            ],
            [
                'user_id' => 2,
                'date' => new DateTime('2019-10-24 11:00:00'),
                'type' => 1,
                'deleted_flg' => 0
            ],
            [
                'user_id' => 2,
                'date' => new DateTime('2019-10-24 14:30:00'),
                'type' => 3,
                'deleted_flg' => 0
            ],
            [
                'user_id' => 2,
                'date' => new DateTime('2019-10-24 15:30:00'),
                'type' => 4,
                'deleted_flg' => 0
            ],
            [
                'user_id' => 2,
                'date' => new DateTime('2019-10-24 19:30:00'),
                'type' => 2,
                'deleted_flg' => 0
            ],
            [
                'user_id' => 2,
                'date' => new DateTime('2019-10-25 10:15:00'),
                'type' => 1,
                'deleted_flg' => 0
            ],
            [
                'user_id' => 2,
                'date' => new DateTime('2019-10-25 14:00:00'),
                'type' => 3,
                'deleted_flg' => 0
            ],
            [
                'user_id' => 2,
                'date' => new DateTime('2019-10-25 14:30:00'),
                'type' => 4,
                'deleted_flg' => 0
            ],
            [
                'user_id' => 2,
                'date' => new DateTime('2019-10-25 16:30:00'),
                'type' => 3,
                'deleted_flg' => 0
            ],
            [
                'user_id' => 2,
                'date' => new DateTime('2019-10-25 17:00:00'),
                'type' => 4,
                'deleted_flg' => 0
            ],
            [
                'user_id' => 2,
                'date' => new DateTime('2019-10-25 18:30:00'),
                'type' => 2,
                'deleted_flg' => 0
            ],
            [
                'user_id' => 2,
                'date' => new DateTime('2019-10-26 15:00:00'),
                'type' => 1,
                'deleted_flg' => 0
            ],
            [
                'user_id' => 2,
                'date' => new DateTime('2019-10-26 20:00:00'),
                'type' => 2,
                'deleted_flg' => 0
            ],
            [
                'user_id' => 3,
                'date' => new DateTime('2019-10-26 20:00:00'),
                'type' => 1,
                'deleted_flg' => 0
            ],
            [
                'user_id' => 3,
                'date' => new DateTime('2019-10-26 23:59:00'),
                'type' => 2,
                'deleted_flg' => 0
            ],
            [
                'user_id' => 3,
                'date' => new DateTime('2019-10-27 00:00:00'),
                'type' => 1,
                'deleted_flg' => 0
            ],
            [
                'user_id' => 3,
                'date' => new DateTime('2019-10-27 01:00:00'),
                'type' => 3,
                'deleted_flg' => 0
            ],
            [
                'user_id' => 3,
                'date' => new DateTime('2019-10-27 02:00:00'),
                'type' => 4,
                'deleted_flg' => 0
            ],
            [
                'user_id' => 3,
                'date' => new DateTime('2019-10-27 03:30:00'),
                'type' => 2,
                'deleted_flg' => 1
            ],
            [
                'user_id' => 3,
                'date' => new DateTime('2019-10-27 04:00:00'),
                'type' => 2,
                'deleted_flg' => 0
            ],
        ]);
    }
}
