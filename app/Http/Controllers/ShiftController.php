<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\HopeShift;
use App\Models\AttendanceStatus;
use App\Models\Shift;
use App\Models\User as User;
use Carbon\Carbon;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\ShiftValiRequest as ShiftValiRequest;
use Illuminate\View\View;


class ShiftController extends Controller
{
    // 検索対象日
    private $user;
    private $shift;
    private $hopeShift;
    private $attendanceStatus;

    public function __construct()
    {
        $this->user = new User();
        $this->shift = new Shift();
        $this->hopeShift = new HopeShift();
        $this->attendanceStatus = new AttendanceStatus();
    }

    // /**
    //  * indexAdminShiftDay
    //  * 管理者の日シフトを表示
    //  * @access public
    //  * @param Request $request
    //  */
    public function indexAdminShiftDay(Request $request) {

        // URLにパラメーターがあるか判断する
        if (empty($request->route('day'))) {

            $date = Carbon::now();

            // URLにパラメーターがない場合今日としてリダイレクトする
            return redirect()->route('adminShiftDay', [
                'year' => $date->year,
                'month' => $date->month,
                'day' => $date->day,
            ]);

        }else {

            $year = $request->route('year');
            $month = $request->route('month');
            $day = $request->route('day');
        }

        // 正しい日付ではない場合404ページに遷移する
        if (!checkdate(intval($month), intval($day), intval($year))) {
            abort('404');
        }

        return view('admin.shift_day', compact('year', 'month', 'day'));
    }

    /**
     * indexAdminShiftDay
     * 管理者の月シフトを表示
     * @access public
     * @param Request $request
     * @return Factory|RedirectResponse|View
     */
    public function indexAdminShiftMonth(Request $request) {

        // URLにパラメーターがあるか判断する
        if (empty($request->route('month'))) {

            $date = Carbon::now();

            // URLにパラメーターがない場合今日としてリダイレクトする
            return redirect()->route('adminShiftMonth', [
                'year' => $date->year,
                'month' => $date->month,
            ]);

        }else {

            $year = $request->route('year');
            $month = $request->route('month');
        }

        // 正しい日付ではない場合404ページに遷移する
        if (!checkdate(intval($month), 1, intval($year))) {
            abort('404');
        }

        return view('admin.shift_month', compact('year', 'month'));
    }

    // /**
    //  * indexUserShiftDay
    //  * ユーザーの日シフトを表示
    //  * @access public
    //  * @param Request $request
    //  */
    public function indexUserShiftDay(Request $request) {

        // URLにパラメーターがあるか判断する
        if (empty($request->route('day'))) {

            $date = Carbon::now();

            // URLにパラメーターがない場合今日としてリダイレクトする
            return redirect()->route('userShiftDay', [
                'year' => $date->year,
                'month' => $date->month,
                'day' => $date->day,
            ]);

        }else {

            $year = $request->route('year');
            $month = $request->route('month');
            $day = $request->route('day');
        }

        // 正しい日付ではない場合404ページに遷移する
        if (!checkdate(intval($month), intval($day), intval($year))) {
            abort('404');
        }

        return view('user.shift_day', compact('year', 'month', 'day'));
    }

    /**
     * indexUserShiftDay
     * ユーザーの月シフトを表示
     * @access public
     * @param Request $request
     * @return Factory|RedirectResponse|View
     */
    public function indexUserShiftMonth(Request $request) {

        // URLにパラメーターがあるか判断する
        if (empty($request->route('month'))) {

            $date = Carbon::now();

            // URLにパラメーターがない場合今日としてリダイレクトする
            return redirect()->route('userShiftMonth', [
                'year' => $date->year,
                'month' => $date->month,
            ]);

        }else {

            $year = $request->route('year');
            $month = $request->route('month');
        }

        // 正しい日付ではない場合404ページに遷移する
        if (!checkdate(intval($month), 1, intval($year))) {
            abort('404');
        }

        return view('user.shift_month', compact('year', 'month'));
    }

    /**
     * loadAdmin
     * 各ユーザの1日のシフトデータを返却
     * @access public
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function loadAdmin(Request $request)
    {

        try {

            $date = new Carbon($request->post('date'));
            $dateTo = clone $date;
            $dateTo->addDay()->subSecond();

            $shiftData = [
                'date' => $date->format('Y-m-d'),
                'users' => [],
            ];
            $users = $this->user->getUsersSimpleData();

            foreach ($users as $key => $user) {

                $userId = $user->id;

                $shift = [
                    'hope' => [],
                    'plan' => [],
                    'actual' => [
                        'work' => [],
                        'rest' => [],
                    ]
                ];

                // 希望シフト
                $hopeShifts = $this->hopeShift->getHopeShiftsByUser($userId, $date, $dateTo);
                $shift['hope'] = $this->hopeShift->formatDataLists($hopeShifts);

                // シフト予定
                $planShifts = $this->shift->getShiftsByUser($userId, $date, $dateTo);
                $shift['plan'] = $this->shift->formatDataLists($planShifts);

                // 実働稼働
                $attendanceShifts = $this->attendanceStatus->getAttendanceStatusByUserId($userId, $date, $dateTo);
                $shift['actual'] = $this->attendanceStatus->formatDataLists($attendanceShifts);

                $usersData = [
                    'userId' => $userId,
                    'name' => $user->name,
                    'shift' => $shift,
                ];

                $shiftData['users'][] = $usersData;
            }

            return response()->json($shiftData, 200);

        } catch (Exception $e) {

            response()->json('Failed load data', 500);
        }
    }

    /**
     * loadUser
     * ユーザの一週間のシフトデータを返却
     * @access public
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function loadUser(Request $request)
    {
        try {

            $dateRange = [];
            $userId = $request->session()->get('login_user_id');
            $postDate = new Carbon($request->post('date'));

            $user = $this->user->getUserSimpleData($userId);

            if (empty($user)) {
                return response()->json('User not found', 400);
            }

            $shiftData = [
                'userId' => $user->id,
                'name' => $user->name,
                'shift' => [],
            ];

            for ($i = 0; $i < 7; ++$i) {

                if ($i === 0) {
                    $dateRange[] = $postDate;
                    continue;
                }

                $dateTo = clone $postDate;
                $dateRange[] = $dateTo->addDay($i);
            }

            foreach ($dateRange as $key => $date) {

                $shift = [
                    'date' => $date->format('Y-m-d'),
                    'hope' => [],
                    'plan' => [],
                    'actual' => [
                        'work' => [],
                        'rest' => [],
                    ]
                ];

                $dateTo = clone $date;
                $dateTo->addDay()->subSecond();

                // 希望シフト
                $hopeShifts = $this->hopeShift->getHopeShiftsByUser($userId, $date, $dateTo);
                $shift['hope'] = $this->hopeShift->formatDataLists($hopeShifts);

                // シフト予定
                $planShifts = $this->shift->getShiftsByUser($userId, $date, $dateTo);
                $shift['plan'] = $this->shift->formatDataLists($planShifts);

                // 実働稼働
                $attendanceShifts = $this->attendanceStatus->getAttendanceStatusByUserId($userId, $date, $dateTo);
                $shift['actual'] = $this->attendanceStatus->formatDataLists($attendanceShifts);

                $shiftData['shift'][] = $shift;
            }

            return response()->json($shiftData);

        } catch (Exception $e) {

            response()->exception();
        }
    }

    /**
     * loadCalendar
     * 月カレンダーのシフトデータを返却
     * @access public
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function loadCalendar(Request $request)
    {
        try {

            $date = new Carbon($request->post('date') . '-01');
            $dateTo = clone $date;
            $dateTo->addMonth()->subSecond();

            $shiftData = [
                'dateFrom' => $date->format('Y-m-d'),
                'dateTo' => $dateTo->format('Y-m-d'),
                'days' => [],
            ];

            $shifts = $this->shift->getMonthlyShiftIndex($date, $dateTo);

            $dayShiftData = [];
            if (!empty($shifts)) {

                foreach ($shifts as $key => $shift) {

                    $users = [];

                    $userIds = explode(',', $shift->user_ids);
                    $userNames = explode(',', $shift->user_names);

                    $lengthUser = count($userIds) >= count($userNames) ? count($userIds) : count($userNames);

                    if ($lengthUser) {
                        for ($i = 0; $i < $lengthUser; ++$i) {
                            $user = [
                                'id' => $userIds[$i],
                                'name' => $userNames[$i],
                            ];
                            $users[] = $user;
                        }
                    }

                    $dayShiftData[$shift->date] = $users;
                }
            }

            $dayDiff = $date->diffInDays($dateTo);
            for ($i = 0; $i < $dayDiff; ++$i) {

                $userList = [];

                if ($i > 0) {
                    $date->addDay();
                }

                $day = $date->format('Y-m-d');


                if (array_key_exists($day, $dayShiftData)) {

                    $userList[] = $dayShiftData[$day];
                }

                $dayData = [
                    'date' => $day,
                    'users' => $userList,
                ];

                $shiftData['days'][] = $dayData;
            }

            return response()->json($shiftData, 200);

        } catch (Exception $e) {

            response()->json('Failed load data', 500);
        }
    }

    /**
     * create
     * 予定シフトデータを作成
     * @access public
     * @param ShiftValiRequest $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function create(ShiftValiRequest $request)
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

            $shiftData = [
                'user_id' => $user->id,
                'start_date' => $inputData['startDate'],
                'end_date' => $inputData['endDate'],
            ];

            $id = $this->shift->register($shiftData);

            if ($id < 1) {
//                throw new Exception('Failed regist');
                response()->json('Failed regist data', 500);
            }

            $shiftData['shiftId'] = $id;
            $shiftData['name'] = $user->name;

            return response()->json($shiftData, 200);

        } catch (Exception $e) {

            response()->json('Failed regist data', 500);
        }
    }

    /**
     * update
     * 予定シフトデータを更新
     * @access public
     * @param $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function update(ShiftValiRequest $request)
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

            if (empty($user) || !$this->shift->isEnable($shiftId, $user->id)) {
                return response()->json('Invalid request data', 400);
            }

            $shiftData = [
                'user_id' => $user->id,
                'start_date' => $inputData['startDate'],
                'end_date' => $inputData['endDate'],
            ];

            $result = $this->shift->updateData($shiftId, $user->id, $shiftData);

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
     * 予定シフトデータを削除
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

            if (empty($user) || !$this->shift->isEnable($shiftId, $user->id)) {
                return response()->json('Invalid request data', 400);
            }

            $result = $this->shift->deleteData($shiftId, $user->id);

            if (!$result) {
//                throw new Exception('Failed delete');
                response()->json('Failed delete data', 500);
            }

            return response()->json('Succeeded delete data', 200);

        } catch (Exception $e) {

            response()->json('Failed delete data', 500);
        }
    }

    /**
     * シフトのCSV出力
     *
     * @param  Request $request
     */
    public function getShiftMonthCsv(Request $request)
    {
        try {

            //年月日、時間を取得
            $year = $request->route('year');
            $month = $request->route('month');
            $date = Carbon::now();

            //シフトデータ取得
            $shifts = $this->shift->getCsvDate($year,$month);

            //CSV形式で情報をファイルに出力のための準備
            $csvFileName =  date("YmdHis") . '.csv';

            //ヘッダー設定
            $lists = array(
                array(
                    mb_convert_encoding('日付',"SJIS", "UTF-8"),
                    mb_convert_encoding('社員名',"SJIS", "UTF-8"),
                    mb_convert_encoding('開始予定',"SJIS", "UTF-8"),
                    mb_convert_encoding('終了予定',"SJIS", "UTF-8")
                )
            );

            $check = '';
            //月のシフト分ループ
            foreach ($shifts as $key => $shift) {
                //既にセット済みの日付は入れない
                if ($check === $shift->date) {
                    $date = '';
                //未セットの日付はセットする
                } else {
                    $check = $shift->date;
                    $date = $shift->date;

                }
                //シフトデータを格納
                $lists[] = array(
                    $date,
                    mb_convert_encoding($shift->name,"SJIS", "UTF-8"),
                    $shift->start,
                    $shift->end
                );
            }
            //csvを開く
            $res = fopen($csvFileName, 'w');

            //エラーが起こった場合はメッセージ表示
            if ($res === FALSE) {
                throw new Exception('ファイルの書き込みに失敗しました。');
            }

            //csvにセットする分ループ
            foreach ($lists as $list) {
                fputcsv($res , $list);
            }
            //csvを閉じる
            fclose($res);

        // ここで渡されるファイルがダウンロード時のファイル名になる
        header("Content-Type: application/octet-stream");
        header('Content-Disposition: attachment; filename='.$csvFileName);
        header('Content-Transfer-Encoding: binary');
        header('Content-Length: ' . filesize($csvFileName));
        readfile($csvFileName);

        } catch(Exception $e) {
            echo $e->getMessage();
        }
    }
}
