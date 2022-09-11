<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\AttendanceStatus as AttendanceStatus;
use App\Models\PhysicalCondition as PhysicalCondition;
/**
 * [API]打刻コントローラークラス
 *
 * 出退勤、休憩に関するAPIをまとめたコントローラークラス。
 *
 * @access public
 * @category punch
 * @package Controller
 */
class PunchController extends Controller
{
    /**
     * 打刻テーブルオブジェクト
     *
     * @access protected
     * @var object
     */
    protected $attendance_status;
    protected $physicalCondition;

    /**
     * インスタンス生成
     *
     * @access public
     */
    public function __construct()
    {
        $this->attendance_status = new AttendanceStatus();
        $this->physicalCondition = new PhysicalCondition();
    }

    /**
     * 出退勤、休憩状況をチェックする
     *
     * @access public
     * @param  Request $request
     * @return array
     */
    public function check(Request $request)
    {
        //必要データを変数に格納
        $work = '';
        $work_type = '';
        $rest = '';
        $rest_type = '';
        $user_id = $request->session()->get('login_user_id');

        //出勤状況を取得する
        $data = $this->attendance_status->check($user_id);
        $type = $data[0]->type ?? 0;

        //初出勤
        if ($type === 0) {
            $work = '出勤';
            $work_type = '1';
            $rest = '休憩開始';
            $rest_type = '3';
        }

        //出勤
        if ($type === 1) {
            $work = '退勤';
            $work_type = '2';
            $rest = '休憩開始';
            $rest_type = '3';
        }

        //退勤
        if ($type === 2) {
            $work = '出勤';
            $work_type = '1';
            $rest = '休憩開始';
            $rest_type = '3';
        }

        //休憩開始
        if ($type === 3) {
            $work = '退勤';
            $work_type = '2';
            $rest = '休憩終了';
            $rest_type = '4';
        }

        //休憩終了
        if ($type === 4) {
            $work = '退勤';
            $work_type = '2';
            $rest = '休憩開始';
            $rest_type = '3';
        }

        //レスポンスを返す
        return [
            'work' => $work,
            'work_type' => $work_type,
            'rest' => $rest,
            'rest_type' => $rest_type
        ];
    }

    /**
     * 出退勤、休憩登録
     *
     * @access public
     * @param  Request $request
     * @return array
     */
    public function create(Request $request)
    {
        //必要データセット
        $type = $request->input('punch_type');
        $user_id = $request->session()->get('login_user_id');

        if($type === '3') {
            if(!$this->attendance_status->CheckBeforeNonTodayWorkStart((int)$user_id)) {
                return response()->json('Failed save data', 500);
            }
        }

        if($type === '2' || $type === '4') {
            if(!$this->attendance_status->CheckBeforeNonTodayAttendance((int)$user_id, (int)$type)) {
                return response()->json('Failed save data', 500);
            }
        }

        //必要データを配列に詰める
        $data = array(
            'user_id' => $user_id,
            'type' => $type
        );

        //登録処理
        $this->attendance_status->insert($data);

        // 出勤時のみ体調を登録
        if ($type === '1') {
            //必要データセット
            $shift_id = $request->input('shift_id');
            $condition = $request->input('condition');
            $data['shift_id'] = $shift_id;
            $data['condition'] = $condition;

            //登録処理
            if (!empty($shift_id)) {
                $this->physicalCondition->condition($data);
            }
        }

        //レスポンスを返す
        return [];
    }
}
