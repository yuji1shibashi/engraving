<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class HopeShift extends Model
{
    /**
     * getHopeShiftByUserId
     * ユーザID毎のhope_shiftデータ取得
     * @access public
     * @param int $userId
     * @param Carbon $dateFrom
     * @param Carbon $dateTo
     * @return Collection $hopeShifts
     */
    public function getHopeShiftsByUser(int $userId, Carbon $dateFrom, Carbon $dateTo): object
    {
        $hopeShifts = DB::table('hope_shift')
            ->select('id', 'start_date', 'end_date')
            ->where([
                ['deleted_flg', 0],
                ['user_id', $userId]
            ])
            ->whereBetween('start_date', [$dateFrom, $dateTo])
            ->orderBy('start_date', 'ASC')
            ->get();

        return $hopeShifts;
    }

    /**
     * formatDataLists
     * ビュー返却用の形式にフォーマット
     * @access public
     * @param object $hopeShifts
     * @return array
     * @throws \Exception
     */
    public function formatDataLists(object $hopeShifts): array
    {
        $formatDataLists = [];

        foreach ($hopeShifts as $key => $hopeShift) {

            $hopeStartDate = new Carbon($hopeShift->start_date);
            $hopeEndDate = new Carbon($hopeShift->end_date);

            $hope = [
                'id' => $hopeShift->id,
                'start' => $hopeStartDate->format('H:i'),
                'end' => $hopeEndDate->format('H:i'),
            ];
            $formatDataLists[] = $hope;
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
        $id = DB::table('hope_shift')->insertGetId($data);
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
        $shiftId = DB::table('hope_shift')
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

        $shiftId = DB::table('hope_shift')
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

        $hopeShift = DB::table('hope_shift')
            ->select('id')
            ->where([
                ['id', $id],
                ['user_id', $userId],
                ['deleted_flg', 0],
            ])
            ->first();

        return !empty($hopeShift);
    }
}
