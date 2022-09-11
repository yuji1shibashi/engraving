<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * [API]業務コントローラークラス
 *
 * 業務状況に関するAPIをまとめたコントローラークラス。
 *
 * @access public
 * @category salary
 * @package Controller
 */
class SalaryController extends Controller
{
	/**
	 * 時給
	 */
	const HOURLYWAGE = 1000;

	/**
	 * ユーザー業務状況
	 *
	 * @var array
	 */
	private $data = [];

	/**
	 * ユーザーID
	 *
	 * @var string
	 */
	private $user_id = '0';

	/**
	 * 年月
	 *
	 * @var string
	 */
	private $year_month = '';

	/**
	 * シフトデータ
	 *
	 * @var object
	 */
	private $shift_data;

	/**
	 * 打刻データ
	 *
	 * @var object
	 */
	private $punch_data;

	/**
	 * シフト打刻
	 *
	 * @var array
	 */
	private $comparison = [];

    /**
     * リクエスト
     *
     * @var object
     */
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * ログイン状況をチェック
     *
     * @return boolean
     */
    public function check_auth()
    {
        //ログインしているかをチェックする
        if (is_null($this->request->session()->get('login_user_id'))) {
            return true;
        }
        return false;
    }

	/**
	 * 業務状況のデータを取得する
	 *
	 * @param  Request $request
	 * @param  string  $user_id
	 * @param  string  $ym
	 * @return mixed
	 */
	public function list(Request $request, $user_id, $year, $month)
	{
        //ログイン状況をチェック
        if (self::check_auth()) {
            return redirect('login');
        }
		//ユーザーIDを取得する
		$set_id = ($user_id !== '')? $user_id : strval($request->session()->get('login_user_id'));
		//検索年月を取得
		$set_year_month = ($year.'-'.$month !== '00-00')? $year.'-'.$month : date('Y-m', time());
		$this->data['ym_id'] = ($year.'/'.$month !== '00/00')? $year.'/'.$month : date('Y/m', time());

		//プロパティに必要情報をセットする
		self::setInfo($set_id, $set_year_month);

		//対象ユーザーの１ヶ月のシフト情報を取得する
		self::getShiftInfo();

		//対象ユーザーの１ヶ月の業務実績を取得する
		self::getPunchInfo();

		//対象ユーザーの１ヶ月の時間と給料のシフト、実働の比較を取得する
		self::comparison();

		//対象ユーザーの１ヶ月の残業、欠勤、遅刻を取得する
		self::workInfo();

		//ユーザーが管理者の場合はユーザー一覧を取得する
		if ($request->session()->get('login_user_id') === 1) {
			$this->data['users'] = self::getUser();
		}

		//取得した情報を変数に格納
		$list = $this->data;

        //存在する場合はメイン画面に遷移
        return view('salary', compact('list'));
    }

    /**
     * ユーザー一覧取得
     *
     * @return object
     */
    function getUser()
    {
    	//ユーザーレコード全件取得
    	return $users = DB::table('users')
            ->select('id', 'name')
            ->where('deleted_flg', '=', 0)
            ->get();
    }

    /**
     * 対象ユーザーの１ヶ月のシフト情報を取得する
     */
    public function getShiftInfo()
    {
		//シフト総時間を取得する
		$this->data['shift_total'] = self::getTotalShiftTime();
		//シフト総給料を取得する
		$this->data['shift_salary'] = self::getSalary($this->data['shift_total']);
    }

    /**
     * 対象ユーザーのシフト総時間を取得する
     *
     * @return int
     */
    public function getTotalShiftTime()
    {
    	//変数初期化
    	$shift_total = 0;

    	//シフト登録分ループする
    	foreach ($this->shift_data as $date) {
    		//1日の労働時間を加算
    		$shift_total += strtotime($date->end_date) - strtotime($date->start_date);

    		//年月日ごとに時間をセット
    		$ymd = mb_substr($date->start_date, 0, 10);
    		$this->comparison[$ymd]['shift'] = strtotime($date->end_date) - strtotime($date->start_date);
    		$this->comparison[$ymd]['shift_start'] = $date->start_date;
    	}
    	//時間に変換する
    	return intval(floor($shift_total / 3600));
    }

    /**
     * 対象ユーザーの１ヶ月のシフト情報を取得する
     */
    public function getPunchInfo()
    {
		//業務実績の総時間を取得する
		$this->data['actual_total'] = self::getTotalPunchTime();
		//業務実績の総給料を取得する
		$this->data['actual_salary'] = self::getSalary($this->data['actual_total']);
    }

    /**
     * 対象ユーザーの打刻総時間を取得する
     *
     * @return int
     */
    public function getTotalPunchTime()
    {
    	//変数初期化
    	$work_start = '';
    	$work_end = '';
    	$rest_start = '';
    	$rest_end = '';
    	$punch_total = 0;

    	//比較年月日を習得する
		$ymd = isset($this->punch_data[0])? mb_substr($this->punch_data[0]->date, 0, 10) : '';
		$this->comparison[$ymd]['work'] = 0;

		//打刻データ分ループする
    	foreach ($this->punch_data as $date) {
    		//打刻した年月日と一致しない場合
    		if (mb_substr($date->date, 0, 10) !== $ymd) {
    			//変数リセット
    			$work_start = '';
				$work_end = '';
				$rest_start = '';
				$rest_end = '';

				//比較年月日をセットする
	    		$ymd = mb_substr($date->date, 0, 10);
	    		$this->comparison[$ymd]['work'] = 0;
	    	}

	    	//出勤
    		if ($date->type === 1) {
    			//出勤に値がセットされていなければ出勤データをセット
    			if ($work_start === '') {
    				$work_start = $date->date;
    				$this->comparison[$ymd]['work_start'] = $date->date;
    			}
    			//出勤時間がセットした値より少なければセット
    			if (strtotime($date->date) < strtotime($work_start)) {
					$work_start = $date->date;
					$this->comparison[$ymd]['work_start'] = $date->date;
    			}
    		}

    		//退勤
    		if ($date->type === 2) {
    			//退勤に値がセットされていなければ退勤データをセット
    			if ($work_end === '') {
    				$work_end = $date->date;
    			}
    			//退勤時間がセットした値より多ければセット
    			if (strtotime($date->date) > strtotime($work_end)) {
					$work_send = $date->date;
    			}
    		}

    		//休憩開始
    		if ($date->type === 3) {
    			//休憩開始に値がセットされていなければ休憩開始データをセット
    			if ($rest_start === '') {
    				$rest_start = $date->date;
    			}
    			//休憩開始時間がセットした値より少なければセット
    			if (strtotime($date->date) < strtotime($rest_start)) {
					$rest_start = $date->date;
    			}
    		}

    		//休憩終了
    		if ($date->type === 4) {
    			//休憩終了に値がセットされていなければ休憩終了データをセット
    			if ($rest_end === '') {
    				$rest_end = $date->date;
    			}
    			//休憩終了時間がセットした値より多ければセット
    			if (strtotime($date->date) > strtotime($rest_end)) {
					$rest_end = $date->date;
    			}
    		}

    		//出勤、退勤がセットされている場合
    		if ($work_start !== '' && $work_end !== '') {
    			//勤務時間を加算する
    			$punch_total += strtotime($work_end) - strtotime($work_start);

				//年月日ごとに時間をセット
				$ymd = mb_substr($work_start, 0, 10);
				$this->comparison[$ymd]['work'] += strtotime($work_end) - strtotime($work_start);

    			//出勤、退勤リセット
    			$work_start = '';
				$work_end = '';
    		}

    		//休憩開始、休憩終了がセットされている場合
    		if ($rest_start !== '' && $rest_end !== '') {
    			//勤務時間を減算する
    			$punch_total -= strtotime($rest_end) - strtotime($rest_start);
    			//休憩開始、休憩終了リセット
				$rest_start = '';
				$rest_end = '';
    		}
    	}
    	//時間に変換する
    	return intval(floor($punch_total / 3600));
    }

    /**
     * 給料計算機能
     *
     * @param  int $time
     * @return string
     */
    public function getSalary(int $time)
    {
    	//時間 × 時給
    	return number_format($time * self::HOURLYWAGE);
    }

    /**
     * プロパティに必要情報をセットする
     *
     * @param string $user_id
     * @param string $year_month
     */
    public function setInfo(string $user_id, string $year_month)
    {
    	$this->data['user_id'] = $this->user_id = $user_id;
    	$this->year_month = $year_month;

    	//対象ユーザーの対象年月のシフト打刻データを取得する
    	$this->punch_data = DB::table('attendance_status')
			->select('date', 'type')
			->where([
				['user_id', '=', $this->user_id],
				['date', 'like', $this->year_month.'%'],
				['deleted_flg', '=', 0]
			])
			->orderBy('date', 'asc')
			->get();

		//対象ユーザーの対象年月のシフト一覧を取得する
    	$this->shift_data = DB::table('shift')
			->select('start_date', 'end_date')
			->where([
				['user_id', '=', $this->user_id],
				['start_date', 'like', $this->year_month.'%'],
				['deleted_flg', '=', 0]
			])
			->get();

		//ユーザーが働いていた年月取得
		$this->data['ym'] = DB::table('shift')
			->select(DB::raw("DATE_FORMAT(`start_date`, '%Y/%m') AS time"))
			->where([
				['user_id', '=', $this->user_id],
				['deleted_flg', '=', 0]
			])
			->groupBy('time')
			->get();
    }

    /**
     * 対象ユーザーの１ヶ月の時間と給料のシフト、実働の比較を行う
     */
    public function comparison()
    {
    	//必要情報を変数に格納
    	$actual = $this->data['actual_total'];
    	$shift = $this->data['shift_total'];
    	$actual_salary = str_replace(',', '', $this->data['actual_salary']);
    	$shift_salary = str_replace(',', '', $this->data['shift_salary']);

    	//符号の判定を行い給料の差額を格納
    	if ($actual_salary - $shift_salary > 0) {
    		$this->data['salary_compare'] = '+'.number_format($actual_salary - $shift_salary);
    	} else {
    		$this->data['salary_compare'] = number_format($actual_salary - $shift_salary);
    	}

    	//符号の判定を行い総勤務時間の差を格納
    	$this->data['total_compare'] = ($actual - $shift > 0)? '+'.$actual - $shift : $actual - $shift;
    }

    /**
     * 対象ユーザーの１ヶ月の残業、欠勤、遅刻を取得
     */
    public function workInfo()
    {
    	//必要情報を変数に格納
		$tardy = 0;
		$absence = 0;
    	$overtime = 0;

    	//今日の日付取得
    	$today = date('Y-m-d', time());

    	//取得した日付分ループ
    	foreach ($this->comparison as $ymd => $value) {
            //シフト登録がない場合
            if (empty($value['shift_start'])) {
                //シフトに入ってないかつ打刻データがある場合は残業に加算
                if (isset($value['work'])) {
                    $overtime += $value['work'];
                }
                break;
            }
    		//データが存在しないまたは、未来の日付の場合は処理を抜ける
    		if (isset($value['work']) && $value['work'] === 0 || $today <= mb_substr($value['shift_start'], 0, 10)) {
	    		break;
	    	}
	    	//欠勤判定
			if (empty($value['work'])) {
				$absence += 1;
				continue;
			}
			//遅刻判定
			if ($value['shift_start'] < $value['work_start']) {
				$tardy += 1;
			}
			//残業時間判定
    		if (($value['shift'] < $value['work'])) {
    			$overtime += $value['work'] - $value['shift'];
    		}
    	}

    	//遅刻データ格納
    	$this->data['tardy'] = $tardy;
    	//欠勤データ格納
		$this->data['absence'] = $absence;
		//残業データ格納
    	$this->data['overtime'] = intval(floor($overtime / 3600));
    }
}
