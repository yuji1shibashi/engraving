<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);
        $this->call(SystemSettingsTableSeeder::class);
        $this->call(PersonalInformationTableSeeder::class);
        $this->call(ShiftTableSeeder::class);
        $this->call(HopeShiftTableSeeder::class);
        $this->call(AttendanceStatusTableSeeder::class);
        $this->call(DirectionManagementTableSeeder::class);
        $this->call(EmployeeNumberTableSeeder::class);
    }
}
