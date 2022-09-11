<?php

namespace App\Models;

use Carbon\Carbon;
use DemeterChain\C;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class AttendanceStatus extends Model
{
    private $nowDate;

    function __construct()
    {
        $this->nowDate = new Carbon();
    }

    /**
     * 打刻データ作成
     *
     * @access public
     * @param array $data
     */
    public function insert(array $data)
    {
        //トランザクション開始
        DB::transaction(function () use ($data) {
            //新規登録処理
            DB::table('attendance_status')->insert([
                'user_id' => $data['user_id'],
                'date' => Carbon::now(),
                'type' => $data['type'],
                'deleted_flg' => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        });
    }

    /**
     * 最後の打刻データ取得
     *
     * @access public
     * @param int $user_id
     * @return array
     */
    public function check(int $user_id)
    {
        //打刻データを取得
        return DB::table('attendance_status')
            ->select('type')
            ->where([
                ['user_id', '=', $user_id],
                ['deleted_flg', '=', 0]
            ])
            ->orderBy('date', 'desc')
            ->limit(1)
            ->get();
    }

    /**
     * 当日シフト一覧取得
     *
     * @access public
     * @param array $data
     */
    public function getTodayShift($user_id, $year, $month)
    {
        // user_idと日付で紐づける
        $today_shift = DB::table('attendance_status')
            ->where('user_id', '=', $user_id)
            ->where(DB::raw('DATE_FORMAT(date, "%Y%m")'), $year . $month)
            ->get();

        return $today_shift;
    }

    /**
     * getAttendanceCurrent
     * ユーザID毎の指定日の最後の打刻データ取得
     * @access public
     * @param int $userId
     * @param Carbon $date
     * @return Collection
     */
    public function getAttendanceCurrent(int $userId, Carbon $date)
    {
        $date = $date->format("Y-m-d");
        $attendanceStatus = DB::table('attendance_status')
            ->select('attendance_status.date', 'attendance_status.type AS type')
            ->where([
                ['attendance_status.deleted_flg', 0],
                ['attendance_status.user_id', $userId],
                [DB::raw('DATE_FORMAT(attendance_status.date, "%Y-%m-%d")'), $date]
            ])
            ->orderBy('attendance_status.date', 'DESC')
            ->first();

        return $attendanceStatus;
    }

    /**
     * getAttendanceStatusByUserId
     * ユーザID毎のshiftデータ取得
     * @access public
     * @param int $userId
     * @param Carbon $dateFrom
     * @param Carbon $dateTo
     * @return Collection $hopeShifts
     */
    public function getAttendanceStatusByUserId(int $userId, Carbon $dateFrom, Carbon $dateTo)
    {
        $attendanceStatus = DB::table('attendance_status')
            ->select('attendance_status.id', 'attendance_status.date', 'attendance_status.type')
            ->where([
                ['attendance_status.deleted_flg', 0],
                ['attendance_status.user_id', $userId],
            ])
            ->whereBetween('attendance_status.date', [$dateFrom, $dateTo])
            ->orderBy('attendance_status.date', 'ASC')
            ->get();

        return $attendanceStatus;
    }

    /**
     * getAttendanceForLastRecordsByUserId
     * ユーザID毎の最新レコードを3件取得
     * @access public
     * @param int $userId
     * @return Collection
     */
    public function getAttendanceForLastRecordsByUserId(int $userId)
    {
        $attendanceStatus = DB::table('attendance_status')
            ->select('attendance_status.date', 'attendance_status.type')
            ->where([
                ['attendance_status.deleted_flg', 0],
                ['attendance_status.user_id', $userId],
            ])
            ->orderBy('attendance_status.id', 'DESC')
            ->first();

        return $attendanceStatus;
    }

    /**
     * getAttendanceForLast2RecordsByUserId
     * ユーザID毎の最新レコードを3件取得
     * @access public
     * @param int $userId
     * @return Collection
     */
    public function getAttendanceForLast3RecordsByUserId(int $userId)
    {
        $attendanceStatus = DB::table('attendance_status')
            ->select('attendance_status.date', 'attendance_status.type')
            ->where([
                ['attendance_status.deleted_flg', 0],
                ['attendance_status.user_id', $userId],
            ])
            ->orderBy('attendance_status.id', 'DESC')
            ->limit(3)
            ->get();

        return $attendanceStatus;
    }

    /**
     * formatDataLists
     * ビュー返却用の形式にフォーマット
     * @access public
     * @param object $attendanceStatuses
     * @return array
     * @throws \Exception
     */
    public function formatDataLists(object $attendanceStatuses): array
    {
        $formatDataLists = [
            'work' => [],
            'rest' => [],
        ];

        $workData = [];
        $restData = [];
        $workStart = [];
        $workEnd = [];
        $restStart = [];
        $restEnd = [];

        foreach ($attendanceStatuses as $key => $attendanceStatus) {

            switch ($attendanceStatus->type) {

                case 1:
                    $workStart[] = [
                        'date' => $attendanceStatus,
                        'id' => $attendanceStatus->id,
                    ];
                    break;

                case 2:
                    $workEnd[] = [
                        'date' => $attendanceStatus,
                        'id' => $attendanceStatus->id,
                    ];
                    break;

                case 3:
                    $restStart[] = [
                        'date' => $attendanceStatus,
                        'id' => $attendanceStatus->id,
                    ];
                    break;

                case 4:
                    $restEnd[] = [
                        'date' => $attendanceStatus,
                        'id' => $attendanceStatus->id,
                    ];
                    break;

                default:
                    break;
            }
        }

        $lengthWorkDate = count($workStart) >= count($workEnd) ? count($workStart) : count($workEnd);

        for ($i = 0; $i < $lengthWorkDate; ++$i) {

            $work = [];

            if (array_key_exists($i, $workStart)) {
                $work['start'] = $workStart[$i];
            }

            if (array_key_exists($i, $workEnd)) {
                $work['end'] = $workEnd[$i];
            }

            if (!empty($work)) {
                $workData[] = $work;
            }
        }

        $lengthRestDate = count($restStart) >= count($restEnd) ? count($restStart) : count($restEnd);

        for ($i = 0; $i < $lengthRestDate; ++$i) {

            $rest = [];

            if (array_key_exists($i, $restStart)) {
                $rest['start'] = $restStart[$i];
            }

            if (array_key_exists($i, $restEnd)) {
                $rest['end'] = $restEnd[$i];
            }

            if (!empty($rest)) {
                $restData[] = $rest;
            }
        }

        $formatDataLists['work'] = $this->setGraphData($workData);
        $formatDataLists['rest'] = $this->setGraphData($restData);

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
        $id = DB::table('attendance_status')->insertGetId($data);
        return $id;
    }

    /**
     * registers
     * 複数レコード追加
     * @param array $dataList
     * @return array
     */
    public function registers(array $dataList): array
    {

        $result = DB::transaction(function () use ($dataList) {

            $result = [];

            foreach ($dataList as $key => $data) {

                $id = DB::table('attendance_status')->insertGetId($data);

                $data['shiftId'] = $id;
                $result[] = $data;
            }

            return $result;
        }, 3);

        return $result;
    }

    /**
     * updateData
     * レコード更新
     * @param int $id
     * @param int $userId
     * @param int $type
     * @param array $data
     * @return bool
     */
    public function updateData(int $id, int $userId, int $type, array $data): bool
    {
        $attendanceStatusId = DB::table('attendance_status')
            ->where([
                ['id', $id],
                ['user_id', $userId],
                ['type', $type]
            ])
            ->update($data);

        return $attendanceStatusId === 2;
    }

    /**
     * updateDataList
     * 複数レコード更新
     * @param int $userId
     * @param array $dataList
     * @return bool
     */
    public function updateDataList(int $userId, array $dataList): bool
    {

        $result = DB::transaction(function () use ($userId, $dataList) {

            $count = 0;

            foreach ($dataList as $key => $data) {

                $result = DB::table('attendance_status')
                    ->where([
                        ['id', $data['id']],
                        ['user_id', $userId],
                        ['type', $data['type']]
                    ])
                    ->update([
                        'date' => $data['date'],
                    ]);

                $count += $result;
            }
            return $count;
        });

        return $result === 2;
    }

    /**
     * deleteShift
     * レコード削除
     * @param int $id
     * @param int $userId
     * @return bool
     */
    public function deleteData(int $id, int $userId): bool
    {

        $attendanceStatusId = DB::table('attendance_status')
            ->where([
                ['id', $id],
                ['user_id', $userId]
            ])
            ->update([
                'deleted_flg' => 1
            ]);

        return $attendanceStatusId > 0;
    }

    /**
     * deleteDataList
     * 複数レコード更新
     * @param int $userId
     * @param array $dataList
     * @return bool
     */
    public function deleteDataList(int $userId, array $dataList): bool
    {

        $result = DB::transaction(function () use ($userId, $dataList) {

            $count = 0;

            foreach ($dataList as $key => $data) {

                $result = DB::table('attendance_status')
                    ->where([
                        ['id', $data['id']],
                        ['user_id', $userId],
                        ['type', $data['type']]
                    ])
                    ->update([
                        'deleted_flg' => 1
                    ]);

                $count += $result;
            }
            return $count;
        });

        return $result === 2;
    }

    /**
     * isEnable
     * シフトデータのユーザIDが操作対象ユーザで合っているか確認
     * @param int $id
     * @param int $userId
     * @param int $type
     * @return bool
     */
    public function isEnable(int $id, int $userId, int $type): bool
    {
        $attendanceStatus = DB::table('attendance_status')
            ->select('id')
            ->where([
                ['id', $id],
                ['user_id', $userId],
                ['type', $type],
                ['deleted_flg', 0],
            ])
            ->first();

        return !empty($attendanceStatus);
    }

    /**
     * CheckBeforeNonTodayAttendance
     * 日跨ぎで打刻が行われている確認する
     * @param int $userId
     * @param int $shiftType
     * @return bool
     * @throws \Exception
     */
    public function CheckBeforeNonTodayWorkStart(int $userId): bool
    {
        $valid = true;
        $last_record = $this->getAttendanceForLastRecordsByUserId($userId);

        // 最新の勤怠種別が出勤時のものか
        if ($last_record->type === 1) {
            $lastWorkStartDate = new Carbon($last_record->date);

            // 出勤日時が当日以外か
            if (!$lastWorkStartDate->isToday()) {

                $today = $this->nowDate;

                // 打刻日時23:59:59 当日0:00:00に休憩を開始した旨のレコードを作成する(用のデータを用意する)
                $endDate = new Carbon($lastWorkStartDate->year . '-' . $lastWorkStartDate->month . '-' . $lastWorkStartDate->day . ' 23:59:59');
                $startDate = new Carbon($today->year . '-' . $today->month . '-' . $today->day . ' 00:00:00');
                $dataList = [
                    [
                    'user_id' => $userId,
                    'type' => 2,
                    'date' => $endDate,
                    ],
                    [
                    'user_id' => $userId,
                    'type' => 1,
                    'date' => $startDate,
                    ]
                ];

                // 用意したデータをテーブルに登録する
                $results = $this->registers($dataList);
                if (count($results) !== count($dataList)) {
                    $valid = false;
                }
            }
        }
        return $valid;
    }


    /**
     * CheckBeforeNonTodayAttendance
     * 日跨ぎで打刻が行われている確認する
     * @param int $userId
     * @param int $shiftType
     * @return bool
     * @throws \Exception
     */
    public function CheckBeforeNonTodayAttendance(int $userId, int $shiftType): bool
    {
        $valid = true;
        $isRestEnd = false;
        $dataList = [];
        $todayDataList = [];
        $today = $this->nowDate;

        // ID降順でレコードを3件取得する
        $attendanceStatuses = $this->getAttendanceForLast3RecordsByUserId($userId);

        foreach ($attendanceStatuses as $key => $attendanceStatus) {

            $type = $attendanceStatus->type;

            // 最新のレコードの勤怠種別が「退勤」の場合はfalseを返す
            if ($key === 0 && $type === 2) {
                $valid = false;
                break;
            }

            if (count($todayDataList) > 0) continue;

            if ($type === 4) $isRestEnd = true;

            // レコードの勤怠種別が「出勤」「休憩開始」の場合
            if ($type === 1 || $type === 3) {

                $date = new Carbon($attendanceStatus->date);

                // 打刻日時が当日かどうか
                if (!$date->isToday()) {

                    // 当日ではない場合、その日の23:59:59でレコードを作成する(用のデータを用意する)
                    $endDate = new Carbon($date->year . '-' . $date->month . '-' . $date->day . ' 23:59:59');
                    $dataList[] = [
                        'user_id' => $userId,
                        'type' => $type === 1 ? 2 : 4,
                        'date' => $endDate,
                    ];

                    if ($type === 1) {

                        // 当日0:00:00に出勤した旨のレコードを作成する(用のデータを用意する)
                        $startDate = new Carbon($today->year . '-' . $today->month . '-' . $today->day . ' 00:00:00');
                        $todayDataList = [
                            'user_id' => $userId,
                            'type' => 1,
                            'date' => $startDate,
                        ];
                    }

                } else if ($type === 3 && $shiftType !== 4 && !$isRestEnd) {

                    // 当日の休憩終了打刻をしていない場合は、退勤時刻と同じ時間に休憩を終了したこととする
                    $dataList[] = [
                        'user_id' => $userId,
                        'type' => 4,
                        'date' => $this->nowDate,
                    ];
                }
            }
        }

        if (count($dataList) > 0) {

            if (!empty($todayDataList)) {
                $dataList[] = $todayDataList;

                if ($shiftType === 4) {

                    // 当日0:00:00に休憩を開始した旨のレコードを作成する(用のデータを用意する)
                    $startDate = new Carbon($today->year . '-' . $today->month . '-' . $today->day . ' 00:00:00');
                    $dataList[] = [
                        'user_id' => $userId,
                        'type' => 3,
                        'date' => $startDate,
                    ];
                }
            }

            // 用意したデータをテーブルに登録する
            $results = $this->registers($dataList);
            if (count($results) !== count($dataList)) {
                $valid = false;
            }
        }

        return $valid;
    }

    /**
     * setGraphData
     * グラフ表示に必要な値を用意する
     * @param array $dataList
     * @return array
     * @throws \Exception
     */
    private function setGraphData(array $dataList): array
    {

        $graphData = [];

        foreach ($dataList as $key => $data) {

            $startDate = null;

            if (isset($data['start'])) {

                $startDate = new Carbon($data['start']['date']->date);
                $graphData[$key]['start'] = [
                    'date' => $startDate->format('H:i'),
                    'id' => $data['start']['date']->id,
                ];
            }

            if (isset($data['end'])) {

                $actualDate = new Carbon($data['end']['date']->date);
                $graphData[$key]['end'] = [
                    'date' => $actualDate->format('H:i'),
                    'id' => $data['end']['date']->id,
                ];

            } else if (isset($data['start'])) {

                $graphData[$key]['end'] = [
                    'date' => $this->setTmpGraphDate($startDate),
                    'id' => -1,
                ];
            }
        }

        return $graphData;
    }

    /**
     * setTmpGraphDate
     * 仮の日時を用意する
     * @param Carbon $date
     * @return Carbon
     * @throws \Exception
     */
    private function setTmpGraphDate(Carbon $date): string
    {
        $tmpDate = $this->nowDate;

        // 始業日と同日か
        if ($date->isToday()) {

            $diffInMinutes = $date->diffInMinutes($this->nowDate);

            // 出勤してから30分以内か
            if ($diffInMinutes < 30) {
                // グラフに表示する為には開始から終了まで30分は差が必要な為、30分加算する
                $tmpDate = $date->addSecond(60 * 30);
            }

        } else {
            // 前日以前の場合、出勤日の最終時間(23:59:59)をセットする
            $tmpDate = new Carbon($date->year . '-' . $date->month . '-' . $date->day . ' 23:59:59');
        }
        return $tmpDate->format('H:i');
    }
}
