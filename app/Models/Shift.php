<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class Shift extends Model
{
    /**
     * getShiftForTheDay
     * 指定日のshiftデータ取得
     * @access public
     * @param string $date "Y-m-d"
     * @return Collection
     */
    public function getShiftsForTheDay(Carbon $date)
    {
        $date = $date->format("Y-m-d");
        $shifts = DB::table('shift')
            ->join('users', 'users.id' , '=', 'shift.user_id')
            ->select(
                'shift.id',
                'users.id AS userId',
                'users.name AS name',
                DB::raw('DATE_FORMAT(shift.start_date, "%T") AS start'),
                DB::raw('DATE_FORMAT(shift.end_date, "%T") AS end')
            )
            ->where([
                ['shift.deleted_flg', 0],
                ['users.deleted_flg', 0],
                [DB::raw('DATE_FORMAT(shift.start_date, "%Y-%m-%d")'), $date]
            ])
            ->orderBy('shift.start_date', 'ASC')
            ->get();

        return $shifts;
    }

    /**
     * シフト一覧取得
     *
     * @access public
     * @param array $data
     */
    public function punchList($user_id)
    {
        $punchList = DB::table('shift')
        ->where('user_id', '=', $user_id)
        ->get();

        return $punchList;
    }

    /**
     * getLatestShiftByUser
     * ユーザID毎のshiftデータ取得
     * @access public
     * @param int $userId
     * @param Carbon $dateFrom
     * @param Carbon $dateTo
     * @return Collection $hopeShifts
     */
    public function getLatestShiftsByUser(int $userId, Carbon $dateFrom, Carbon $dateTo)
    {
        $shifts = DB::table('shift')
            ->select('shift.id', 'shift.start_date AS start', 'shift.end_date AS end')
            ->where([
                ['shift.deleted_flg', 0],
                ['shift.user_id', $userId],
            ])
            ->whereBetween('shift.start_date', [$dateFrom, $dateTo])
            ->orderBy('shift.start_date', 'ASC')
            ->first();

        return $shifts;
    }

    /**
     * getShiftsByUser
     * ユーザID毎のshiftデータ取得
     * @access public
     * @param int $userId
     * @param Carbon $dateFrom
     * @param Carbon $dateTo
     * @return Collection $hopeShifts
     */
    public function getShiftsByUser(int $userId, Carbon $dateFrom, Carbon $dateTo)
    {
        $shifts = DB::table('shift')
            ->select('shift.id', 'shift.start_date', 'shift.end_date')
            ->where([
                ['shift.deleted_flg', 0],
                ['shift.user_id', $userId],
            ])
            ->whereBetween('shift.start_date', [$dateFrom, $dateTo])
            ->orderBy('shift.start_date', 'ASC')
            ->get();

        return $shifts;
    }

    /**
     * @param Carbon $dateFrom
     * @param Carbon $dateTo
     * @return object
     */
    public function getMonthlyShiftIndex(Carbon $dateFrom, Carbon $dateTo)
    {
        $shifts = DB::table('shift')
            ->select(
                DB::raw('CAST(shift.start_date AS DATE) AS date'),
                DB::raw('GROUP_CONCAT(DISTINCT users.id separator \',\') AS user_ids'),
                DB::raw('GROUP_CONCAT(DISTINCT users.name separator \',\') AS user_names'))
            ->leftJoin('users', 'shift.user_id', '=', 'users.id')
            ->where([
                ['shift.deleted_flg', 0],
                ['users.deleted_flg', 0],
            ])
            ->whereBetween('shift.start_date', [$dateFrom, $dateTo])
            ->groupBy( DB::raw('CAST(shift.start_date AS DATE)'))
            ->orderBy('date', 'ASC')
            // ->orderBy('users.id', 'ASC')
            ->get();

        return $shifts;
    }

    /**
     * formatDataLists
     * ビュー返却用の形式にフォーマット
     * @access public
     * @param object $shifts
     * @return array
     * @throws \Exception
     */
    public function formatDataLists(object $shifts): array
    {
        $formatDataLists = [];

        foreach ($shifts as $key => $shift) {

            $shiftStartDate = new Carbon($shift->start_date);
            $shiftEndDate = new Carbon($shift->end_date);

            $plan = [
                'id' => $shift->id,
                'start' => $shiftStartDate->format('H:i'),
                'end' => $shiftEndDate->format('H:i'),
            ];
            $formatDataLists[] = $plan;
        }

        return $formatDataLists;
    }

    /**
     * register
     * レコード追加
     * @param array $data
     * @return int
     */
    public function register(array $data): int
    {
        $id = DB::table('shift')->insertGetId($data);
        return $id;
    }

    /**
     * updateData
     * レコード更新
     * @param int $id
     * @param int $userId
     * @param array $data
     * @return bool
     */
    public function updateData(int $id, int $userId, array $data): bool
    {
        $shiftId = DB::table('shift')
            ->where([
                ['id', $id],
                ['user_id', $userId]
            ])
            ->update($data);

        return $shiftId > 0;
    }

    /**
     * deleteData
     * レコード削除
     * @param int $id
     * @param int $userId
     * @return bool
     */
    public function deleteData(int $id, int $userId): bool
    {

        $shiftId = DB::table('shift')
            ->where([
                ['id', $id],
                ['user_id', $userId]
            ])
            ->update([
                'deleted_flg' => 1
            ]);

        return $shiftId > 0;
    }

    /**
     * isEnable
     * シフトデータのユーザIDが操作対象ユーザで合っているか確認
     * @param $id
     * @param $userId
     * @return bool
     */
    public function isEnable(int $id, int $userId): bool
    {
        $shift = DB::table('shift')
            ->select('id')
            ->where([
                ['id', $id],
                ['user_id', $userId],
                ['deleted_flg', 0],
            ])
            ->first();

        return !empty($shift);
    }

    /**
     * 次回のシフトを取得
     *
     * @param  int $id
     * @param  string $now
     * @return array
     */
    public function nextShift(int $userId, string $now)
    {
        $shift = DB::table('shift')
            ->select('start_date', 'end_date')
            ->where([
                ['user_id', '=', $userId],
                ['start_date', '>', $now],
                ['deleted_flg', 0],
            ])
            ->first();

        return $shift;
    }

    /**
     * シフト一覧を取得
     *
     * @param  int $id
     * @param  $year $month
     * @return array
     */
    public function getCsvDate($year , $month)
    {
        $shift = DB::table('shift')
            ->select(
                DB::raw('DATE_FORMAT(shift.start_date, "%Y/%m/%d") AS date'), 
                DB::raw('DATE_FORMAT(shift.start_date, "%H:%i") AS start'), 
                DB::raw('DATE_FORMAT(shift.end_date, "%H:%i") AS end'), 
                'users.name')
            ->join('users' , 'shift.user_id' , '=' , 'users.id')
            ->where(DB::raw('DATE_FORMAT(start_date, "%Y%m")'), $year.$month)
            ->orderBy('start_date', 'ASC')
            ->get();

        return $shift;
    }
}
