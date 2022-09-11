<?php

namespace App\Http\Controllers;

use App\Http\Requests\HopeShiftValiRequest;
use App\Models\HopeShift;
use App\Models\User as User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class HopeShiftController extends Controller
{
    private $user;
    private $hopeShift;

    public function __construct()
    {
        $this->user = new User();
        $this->hopeShift = new HopeShift();
    }

    /**
     * create
     * シフト希望データを作成
     * @access public
     * @param $request
     * @return JsonResponse
     */
    public function create(HopeShiftValiRequest $request)
    {

        try {

            $inputData = $request->validated();

            $startDate = new Carbon($inputData['startDate']);
            $endDate = new Carbon($inputData['endDate']);

            if ($startDate->gte($endDate)) {
                return response()->json('Start date is later than end date', 400);
            }

            $userId = $request->session()->get('login_user_admin') ?
                $inputData['userId'] : $request->session()->get('login_user_id');

            $user = $this->user->getUserSimpleData($userId);
            if (empty($user)) {
                return response()->json('Invalid request data', 400);
            }

            $hopeShiftData = [
                'user_id' => $user->id,
                'start_date' => $inputData['startDate'],
                'end_date' => $inputData['endDate'],
            ];

            $hopeShiftData['memo'] = array_key_exists('memo', $inputData) ? $inputData['memo'] : null;
            $id = $this->hopeShift->register($hopeShiftData);

            if ($id < 1) {
//                throw new Exception('Failed regist');
                response()->json('Failed regist data', 500);
            }

            $hopeShiftData['shiftId'] = $id;
            $hopeShiftData['name'] = $user->name;

            return response()->json($hopeShiftData, 200);

        } catch (Exception $e) {

            response()->json('Failed regist data', 500);
        }
    }

    /**
     * update
     * シフト希望データを更新
     * @access public
     * @param $request
     * @return JsonResponse
     */
    public function update(HopeShiftValiRequest $request)
    {
        try {
            $inputData = $request->validated();

            $startDate = new Carbon($inputData['startDate']);
            $endDate = new Carbon($inputData['endDate']);

            if ($startDate->gte($endDate)) {
                return response()->json('Start date is later than end date', 400);
            }

            // 管理者以外の場合は自分のセッションIDを取得する
            $userId = $request->session()->get('login_user_admin') ?
                $inputData['userId'] : $request->session()->get('login_user_id');

            $user = $this->user->getUserSimpleData($userId);
            $shiftId = array_key_exists('shiftId', $inputData) ? (int)$inputData['shiftId'] : 0;

            if (empty($user) || !$this->hopeShift->isEnable($shiftId, $user->id)) {
                return response()->json('Invalid request data', 400);
            }

            $hopeShiftData = [
                'start_date' => $inputData['startDate'],
                'end_date' => $inputData['endDate'],
            ];

            $hopeShiftData['memo'] = array_key_exists('memo', $inputData) ? $inputData['memo'] : null;

            $result = $this->hopeShift->updateData($shiftId, $user->id, $hopeShiftData);

            if (!$result) {
//                throw new Exception('Failed update');
                response()->json('Failed update data', 500);
            }

            return response()->json('Succeeded updated data', 200);

        } catch (Exception $e) {

            response()->json('Failed update data', 500);
        }
    }

    /**
     * delete
     * シフト希望データを削除
     * @access public
     * @param $request
     * @return JsonResponse
     */
    public function delete(Request $request)
    {
        try {

            $inputData = $request->post();

            // 管理者以外の場合は自分のセッションIDを取得する
            $userId = $request->session()->get('login_user_admin') ?
                $inputData['userId'] : $request->session()->get('login_user_id');

            $user = $this->user->getUserSimpleData($userId);
            $shiftId = array_key_exists('shiftId', $inputData) ? (int)$inputData['shiftId'] : 0;

            if (empty($user) || !$this->hopeShift->isEnable($shiftId, $user->id)) {
                return response()->json('Invalid request data', 400);
            }

            $result = $this->hopeShift->deleteData($shiftId, $user->id);

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
