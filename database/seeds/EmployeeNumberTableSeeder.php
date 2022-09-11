<?php

use Illuminate\Database\Seeder;

class EmployeeNumberTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('employee_number')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        DB::table('employee_number')->insert([
            [
                'user_id'         => 1,
                'employee_number' => 1,
            ],
            [
                'user_id'         => 2,
                'employee_number' => 2,
            ],
            [
                'user_id'         => 3,
                'employee_number' => 3,
            ],
            [
                'user_id'         => 4,
                'employee_number' => 4,
            ],
            [
                'user_id'         => 5,
                'employee_number' => 5,
            ],
            [
                'user_id'         => 6,
                'employee_number' => 6,
            ],
            [
                'user_id'         => 7,
                'employee_number' => 7,
            ],
            [
                'user_id'         => 8,
                'employee_number' => 8,
            ],
            [
                'user_id'         => 9,
                'employee_number' => 9,
            ],
            [
                'user_id'         => 10,
                'employee_number' => 10,
            ],
            [
                'user_id'         => 11,
                'employee_number' => 11,
            ],
            [
                'user_id'         => 12,
                'employee_number' => 12,
            ],
            [
                'user_id'         => 13,
                'employee_number' => 13,
            ],
        ]);
    }
}
