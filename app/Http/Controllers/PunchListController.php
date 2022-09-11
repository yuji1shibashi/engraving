<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Shift as Shift;
use App\Models\AttendanceStatus as AttendanceStatus;
use App\Models\User;

/**
 * [API]目標コントローラークラス
 *
 * 目標設定に関するAPIをまとめたコントローラークラス。
 *
 * @access public
 * @category PunchList
 * @package Controller
 */
class PunchListController extends Controller
{
    /**
     * 目標テーブルオブジェクト
     *
     * @access protected
     * @var object
     */
    protected $shift;
    protected $attendancestatus;

    /**
     * インスタンス生成
     *
     * @access public
     */
    public function __construct()
    {
        $this->shift = new shift();
        $this->attendancestatus = new attendancestatus();
    }

    /**
	 * 表示する目標一覧を取得する
	 *
	 * @access public
	 * @param  Request $request
	 * @return mixed
	 */
    public function list(Request $request, $user_id, $year, $month)
    {
        //ログインしているかをチェックする
        if (is_null($request->session()->get('login_user_id'))) {
            return redirect('login');
        }

        $weeks = [
          '日', //0
          '月', //1
          '火', //2
          '水', //3
          '木', //4
          '金', //5
          '土', //6
        ];

        //ユーザーIDを取得する
		$set_id = ($user_id !== '')? $user_id : strval($request->session()->get('login_user_id'));

        // ユーザーデータ取得
		if ($request->session()->get('login_user_admin') === 1 ) {
			$userList = User::all();
        } else {
            $user_id = $request->session()->get('login_user_id');
            $userList = User::find($user_id);
        }

        //目標レコード全件取得
        $targets = $this->attendancestatus->getTodayShift($set_id, $year ,str_pad(intval($month), 2, 0, STR_PAD_LEFT));

        $shift = $this->shift->punchList($set_id);
        $shift_data = json_decode(json_encode($targets), true);
        $targets_shift_data = json_decode(json_encode($shift), true);
        $count = 0;
        $before_date = 0;
        $result = array();

        foreach ($shift_data as $value) {
            $week = $weeks[date('w', strtotime($value["date"]))];
            $value["start_date"] = date("n/j($week)" ,strtotime($value["date"]));
            // 取得した配列を日付ごとにまとめる
            if ($before_date === $value["start_date"] || $count === 0 || $before_date === 0) {

                $result[$value["start_date"]]["created_at"] = $value["start_date"];
                $result[$value["start_date"]]["id"] = $value["id"];
                $result[$value["start_date"]]["user_id"] = $value["user_id"];

                if ($value["type"] === 1) {
                    $result[$value["start_date"]]["start_date"] = date("G:i:s" ,strtotime($value["date"]));
                } else if ($value["type"] === 2) {
                    $result[$value["start_date"]]["end_date"] = date("G:i:s" ,strtotime($value["date"]));
                } else if ($value["type"] === 3) {
                    $result[$value["start_date"]]["break_start_date"] = date("G:i:s" ,strtotime($value["date"]));
                } else if ($value["type"] === 4) {
                    $result[$value["start_date"]]["break_end_date"] = date("G:i:s" ,strtotime($value["date"]));
                }

                // 休憩時間計算
                if (!empty($result[$value["start_date"]]["break_start_date"]) && !empty($result[$value["start_date"]]["break_end_date"])) {
                    $break_differences = strtotime($result[$value["start_date"]]["break_end_date"]) - strtotime($result[$value["start_date"]]["break_start_date"]);
                    $break_time_diff = gmdate("H:i", $break_differences);
                    $result[$value["start_date"]]["break_differences"] = $break_differences;
                    $result[$value["start_date"]]["break_time_diff"] = $break_time_diff;
                }

                // 勤務時間計算
                if (!empty($result[$value["start_date"]]["start_date"]) && !empty($result[$value["start_date"]]["end_date"])) {
                    if (!empty($result[$value["start_date"]]["break_time_diff"])) {
                        $differences = strtotime($result[$value["start_date"]]["end_date"]) - strtotime($result[$value["start_date"]]["start_date"]);
                        $time = $differences - $result[$value["start_date"]]["break_differences"];
                        $diff = gmdate("H:i", $time);
                        $result[$value["start_date"]]["time_diff_int"] = $time;
                        $result[$value["start_date"]]["time_diff"] = $diff;
                    } else {
                        $time = strtotime($result[$value["start_date"]]["end_date"]) - strtotime($result[$value["start_date"]]["start_date"]);
                        $diff = gmdate("H:i", $time);
                        $result[$value["start_date"]]["time_diff_int"] = $time;
                        $result[$value["start_date"]]["time_diff"] = $diff;
                    }
                }

                // 残業計算(登録したシフト+休憩時間と実稼働時間の差分)
                foreach ($targets_shift_data as $shift_value) {
                    $week = $weeks[date('w', strtotime($shift_value["start_date"]))];
                    $start_at = date("n/j" ,strtotime($shift_value["start_date"]))."($week)";
                    if ($start_at === $value["start_date"] && $result[$value["start_date"]]["user_id"] === $shift_value["user_id"] && !empty($result[$value["start_date"]]["end_date"])) {
                        $shift_time =  strtotime($shift_value["end_date"]) - strtotime($shift_value["start_date"]);
                        if (!empty($result[$value["start_date"]]["break_differences"])) {
                            $shift_working_diff = $result[$value["start_date"]]["time_diff_int"] - ($shift_time - ($result[$value["start_date"]]["break_differences"]));
                        } else {
                            $shift_working_diff = $result[$value["start_date"]]["time_diff_int"] - $shift_time;
                        }

                        $over_time_diff = gmdate("H:i", $shift_working_diff);
                        $result[$value["start_date"]]["over_time_diff"] = $over_time_diff;
                    }
                }
            }

            $before_date = $value["start_date"];
        }

        //存在する場合はメイン画面に遷移
        return view('punch_list', compact('result','userList','set_id','year','month'));
    }
}
