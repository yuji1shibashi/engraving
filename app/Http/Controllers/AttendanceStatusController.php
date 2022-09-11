<?php

namespace App\Http\Controllers;

use App\Http\Requests\AttendanceStatusValiRequest;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\AttendanceStatus;
use App\Models\User;


class AttendanceStatusController extends Controller
{

    public function __construct()
    {
        $this->user = new User();
        $this->attendanceStatus = new AttendanceStatus();
    }

    public function getUserList()
    {
        $userList = User::all();
        return $userList;
    }

    public function getAttendance($times)
    {
        $userTime = [];
        $work_start = "00:00";
        $work_end = "00:00";
        $rest_start = "00:00";
        $rest_end = "00:00";

        foreach ($times as $key => $time) {
            $day_week = $time->working_date . $time->week;

            //出勤
            if ($time->type === 1) {
                $work_start = $time->working_time;
            }
            //退勤
            if ($time->type === 2) {
                $work_end = $time->working_time;
            }
            //休憩開始
            if ($time->type === 3) {
                $rest_start = $time->working_time;
            }
            //休憩終了
            if ($time->type === 4) {
                $rest_end = $time->working_time;
            }

            $userTime[$day_week] = [
                'work_start' => $work_start,
                'work_end' => $work_end,
                'rest_start' => $rest_start,
                'rest_end' => $rest_end
            ];
        }
        return $userTime;
    }

    public function index(Request $request)
    {
        if ($request->session()->get('login_user_admin') === 1) {
            $userList = $this->getUserList();
        }
        return view('punch_list', compact('userList'));
    }

    public function date(Request $request)
    {
        $month = $request->month;
        $id = $request->id;
        $times = $this->attendanceStatus->getUserTime($id, $month);
        $userTime = $this->getAttendance($times);

        return response($userTime);
    }

    /**
     * creates
     * 稼働実績データを作成
     * @access public
     * @param AttendanceStatusValiRequest $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function creates(AttendanceStatusValiRequest $request)
    {
        try {

            $inputData = $request->validated();

            $userId = $request->session()->get('login_user_admin') ?
                $inputData['userId'] : $request->session()->get('login_user_id');

            $work = $inputData['work'];
            $rests = $inputData['rest'];

            $user = $this->user->getUserSimpleData($userId);
            if (empty($user) || (empty($work) && empty($rests))) {
                return response()->json('Invalid request data', 400);
            }

            $attendanceStatuses = [];

            // 勤務時間
            if (isset($work['startDate']) && isset($work['endDate'])) {

                $startDate = new Carbon($work['startDate']);
                $endDate = new Carbon($work['endDate']);

                if ($startDate->gte($endDate)) {
                    return response()->json('Start date is later than end date', 400);
                }

                $workStartData = [
                    'user_id' => $user->id,
                    'type' => 1,
                    'date' => $work['startDate'],
                ];

                $workEndData = [
                    'user_id' => $user->id,
                    'type' => 2,
                    'date' => $work['endDate'],
                ];

                $attendanceStatuses[] = $workStartData;
                $attendanceStatuses[] = $workEndData;
            }

            // 休憩時間
            if (count($rests) > 0) {

                foreach ($rests as $key => $rest) {
                    if (isset($rest['startDate']) && isset($rest['endDate'])) {

                        $startDate = new Carbon($rest['startDate']);
                        $endDate = new Carbon($rest['endDate']);

                        if ($startDate->gte($endDate)) {
                            return response()->json('Start date is later than end date', 400);
                        }

                        $restStartData = [
                            'user_id' => $user->id,
                            'type' => 3,
                            'date' => $rest['startDate'],
                        ];

                        $restEndData = [
                            'user_id' => $user->id,
                            'type' => 4,
                            'date' => $rest['endDate'],
                        ];

                        $attendanceStatuses[] = $restStartData;
                        $attendanceStatuses[] = $restEndData;
                    }
                }
            }

            $result = $this->attendanceStatus->registers($attendanceStatuses);

            return response()->json($result, 200);

        } catch (Exception $e) {

            response()->json('Failed regist data', 500);
        }
    }

    /**
     * create
     * 稼働実績データを作成2
     * @access public
     * @param AttendanceStatusValiRequest $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function create(AttendanceStatusValiRequest $request)
    {
        try {

            $inputData = $request->validated();

            if (!isset($inputData['startDate']) || !isset($inputData['endDate'])) {
                return response()->json('Not found Params: startDate or endDate', 400);
            }

            $startDate = new Carbon($inputData['startDate']);
            $endDate = new Carbon($inputData['endDate']);
            if ($startDate->gte($endDate)) {
                return response()->json('Start date is later than end date', 400);
            }

            $userId = $request->session()->get('login_user_admin') ?
                $inputData['userId'] : $request->session()->get('login_user_id');

            $registerType = array_key_exists('type', $inputData) ? (int)$inputData['type'] : 0;

            $user = $this->user->getUserSimpleData($userId);
            if (empty($user) || !in_array($registerType, [1, 2], true)) {
                return response()->json('Invalid request data', 400);
            }
            $attendanceStatuses = [];

            if ($registerType === 1) {
                $startType = 1;
                $endType = 2;
            } else {
                $startType = 3;
                $endType = 4;
            }

            $startData = [
                'user_id' => $user->id,
                'type' => $startType,
                'date' => $inputData['startDate'],
            ];

            $endData = [
                'user_id' => $user->id,
                'type' => $endType,
                'date' => $inputData['endDate'],
            ];

            $attendanceStatuses[] = $startData;
            $attendanceStatuses[] = $endData;

            $result = $this->attendanceStatus->registers($attendanceStatuses);

            return response()->json($result, 200);

        } catch (Exception $e) {

            response()->json('Failed regist data', 500);
        }
    }

    /**
     * update
     * 稼働実績データを更新
     * @access public
     * @param AttendanceStatusValiRequest $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function update(AttendanceStatusValiRequest $request)
    {

        try {

            $inputData = $request->validated();

            if (!isset($inputData['start']['shiftId']) || !isset($inputData['start']['date'])) {
                return response()->json('Not found Params: start', 400);
            }

            if (!isset($inputData['end']['shiftId']) || !isset($inputData['end']['date'])) {
                return response()->json('Not found Params: end', 400);
            }

            $startDate = new Carbon($inputData['start']['date']);
            $endDate = new Carbon($inputData['end']['date']);
            if ($startDate->gte($endDate)) {
                return response()->json('Start date is later than end date', 400);
            }

            $userId = $request->session()->get('login_user_admin') ?
                $inputData['userId'] : $request->session()->get('login_user_id');

            $user = $this->user->getUserSimpleData($userId);
            $registerType = array_key_exists('type', $inputData) ? (int)$inputData['type'] : 0;

            if (empty($user) || !in_array($registerType, [1, 2], true)) {
                return response()->json('Invalid request data', 400);
            }

            if ($registerType === 1) {
                $startType = 1;
                $endType = 2;
            } else {
                $startType = 3;
                $endType = 4;
            }

            $startShiftId = (int)$inputData['start']['shiftId'];
            $endShiftId = (int)$inputData['end']['shiftId'];

            if (!$this->attendanceStatus->isEnable($startShiftId, $user->id, $startType) || !$this->attendanceStatus->isEnable($endShiftId, $user->id, $endType)) {
                return response()->json('Invalid request data', 400);
            }

            $attendanceStatuses = [];

            $startData = [
                'id' => $startShiftId,
                'type' => $startType,
                'date' => $inputData['start']['date'],
            ];

            $endData = [
                'id' => $endShiftId,
                'type' => $endType,
                'date' => $inputData['end']['date'],
            ];

            $attendanceStatuses[] = $startData;
            $attendanceStatuses[] = $endData;

            $result = $this->attendanceStatus->updateDataList($user->id, $attendanceStatuses);

            if (!$result) {
//                throw new Exception('Failed update');
                response()->json('Failed updated data', 500);
            }

            return response()->json('Succeeded updated data', 200);

        } catch (Exception $e) {
            response()->json('Failed updated data', 500);
        }
    }

    /**
     * delete
     * 稼働データを削除
     * @access public
     * @param AttendanceStatusValiRequest $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function delete(AttendanceStatusValiRequest $request)
    {
        try {

            $inputData = $request->validated();

            $userId = $request->session()->get('login_user_admin') ?
                $inputData['userId'] : $request->session()->get('login_user_id');

            $user = $this->user->getUserSimpleData($userId);
            $registerType = array_key_exists('type', $inputData) ? (int)$inputData['type'] : 0;

            if (empty($user) || !in_array($registerType, [1, 2], true)) {
                return response()->json('Invalid request data', 400);
            }

            if ($registerType === 1) {
                $startType = 1;
                $endType = 2;
            } else {
                $startType = 3;
                $endType = 4;
            }

            $startShiftId = array_key_exists('startShiftId', $inputData) ? (int)$inputData['startShiftId'] : 0;
            $endShiftId = array_key_exists('endShiftId', $inputData) ? (int)$inputData['endShiftId'] : 0;

            if (!$this->attendanceStatus->isEnable($startShiftId, $user->id, $startType) || !$this->attendanceStatus->isEnable($endShiftId, $user->id, $endType)) {
                return response()->json('Invalid request data', 400);
            }

            $attendanceStatuses = [];

            $startData = [
                'id' => $startShiftId,
                'type' => $startType,
            ];

            $endData = [
                'id' => $endShiftId,
                'type' => $endType,
            ];

            $attendanceStatuses[] = $startData;
            $attendanceStatuses[] = $endData;

            $result = $this->attendanceStatus->deleteDataList($user->id, $attendanceStatuses);

            if (!$result) {
//                throw new Exception('Failed delete');
                response()->json('Failed delete data', 500);
            }

            return response()->json('Succeeded delete data', 200);

        } catch (Exception $e) {
            response()->json('Failed delete data', 500);
        }
    }
}
