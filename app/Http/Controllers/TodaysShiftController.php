<?php
namespace App\Http\Controllers;

use App\Models\Shift;
use Carbon\Carbon;
use App\Models\AttendanceStatus;
use App\Models\PhysicalCondition;
use App\Models\GeneralMessage;
use App\Models\PersonalMessage;
use Illuminate\Http\Request;

class TodaysShiftController extends Controller
{
    private $shift;
    private $date;
    private $attendanceStatus;
    private $physicalCondition;

    public function __construct(Shift $shift, Carbon $date, AttendanceStatus $attendanceStatus, PhysicalCondition $physicalCondition, PersonalMessage $personalMessage)
    {
        $this->shift = $shift;
        $this->date = $date;
        $this->attendanceStatus = $attendanceStatus;
        $this->physicalCondition = $physicalCondition;
        $this->personalMessage = $personalMessage;
    }

    /**
     *当日シフト表示
     */
    protected function list(Request $request)
    {
        //ログインしているかをチェックする
        if (is_null($request->session()->get('login_user_id'))) {
            return redirect('login');
        }

        $todaysShifts = ($this->shift)->getShiftsForTheDay($this->date);

        // 勤怠状況を追加
        $todaysShifts = $this->addAttendance($todaysShifts, $this->date);

        //体調追加
        $todaysShifts = $this->getCondition($todaysShifts);

        //全体info表示用に現在に該当するinfoを取得
        $generalMessages = GeneralMessage::where('start_date', '<=', date('Y-m-d 00:00:00'))
            ->where('end_date', '>=', date('Y-m-d 00:00:00'))
            ->where('deleted_flg', 0)
            ->orderBy('start_date', 'DESC')
            ->get();

        return view('main',[
            'shifts' => $todaysShifts
            , 'general_messages' => $generalMessages
        ]);
    }

    /**
     *当日シフトに勤怠状況を追加
     */
    private function addAttendance(object $shifts, Carbon $date):object
    {
        foreach ($shifts as $shift) {
            $currentStatus = ($this->attendanceStatus)->getAttendanceCurrent($shift->userId, $date);
            $type = $currentStatus->type ?? 0;
            $start = $shift->start;
            $shift->attendance = $this->convertStatus($type, $start);
        }

        return $shifts;
    }

    /**
     *タイプを勤務状況の文字列(色変更用クラス)に変換
     */
    private function convertStatus(int $type, string $start):string
    {
        $attendance = "not_attending";
        $start = strtotime($start);
        $limit = $start + 2 * 60 * 60;
        $current = strtotime(($this->date)->format("H:i:s"));

        if ($current <= $start && $type === 0) {
            $attendance =  "not_attending";
        }else if ($current <= $start && $type === 1) {
            $attendance =  "attended";
        }else if ($current <= $limit && $type === 0) {
            $attendance =  "late";
        }else if ($limit < $current && $type === 0) {
            $attendance =  "absence";
        }else if ($type === 3) {
            $attendance =  "break";
        }else if ($type === 1 || $type === 4) {
            $attendance =  "attended";
        }else if ($type === 2) {
            $attendance =  "out";
        }
        return $attendance;
    }

    /**
     *勤務状況の色変更用クラスを再設定
     */
    public function reset(Request $request)
    {
        $userId = $request->session()->get('login_user_id');
        $dateStart = new Carbon(($this->date)->format("Y-m-d 00:00:00"));
        $dateEnd = new Carbon(($this->date)->format("Y-m-d 23:59:59"));
        $shift = ($this->shift)->getLatestShiftsByUser($userId, $dateStart, $dateEnd);
        if (!empty($shift)) {
            $attendance = ($this->attendanceStatus)->getAttendanceCurrent($userId, $this->date);
            $status = $this->convertStatus($attendance->type, $shift->start); 
            $condition = $this->physicalCondition->getCondition($userId, $shift->id)->symptoms;

            return[
                'user_id' => $userId,
                'status' => $status,
                'condition' => $condition
            ];
        } else {
            return [];
        }
    }

    /**
     * 体調の情報を取得
     */
    public function getCondition(object $shifts)
    {
        foreach ($shifts as $shift) {
            $symptoms = $this->physicalCondition->getCondition(
                $shift->userId,
                $shift->id
            );

            $shift->symptoms = $symptoms->symptoms ?? '';
        }
        return $shifts;
    }

    /**
     * 次の情報を取得
     */
    public function nextShift(Request $request)
    {
        $week = [
          '日', //0
          '月', //1
          '火', //2
          '水', //3
          '木', //4
          '金', //5
          '土', //6
        ];

        //ユーザーID取得
        $userId = $request->session()->get('login_user_id');
        //次回のシフト取得
        $next_shift = $this->shift->nextShift($userId, date("Y-m-d H:i:s"));

        //次回のシフトがない場合は未定
        if (!empty($next_shift)) {
            return [
                'day' => date("Y年m月d日", strtotime($next_shift->start_date)),
                'week' => $week[date('w', strtotime($next_shift->start_date))],
                'start' => date("H:i", strtotime($next_shift->start_date)),
                'end' => date("H:i", strtotime($next_shift->end_date))
            ];
        } else {
            return [
                'day' => '未定',
                'week' => '-',
                'start' => '未定',
                'end' => '未定'
            ];
        }
    }

    /**
     * 個人宛メッセージ取得を取得
     */
    public function personalInfo(Request $request)
    {
        //ユーザーID取得
        $userId = $request->session()->get('login_user_id');

        //メッセージ取得
        $info = PersonalMessage::where([
                ['to_user_id', $userId],
                ['start_date', '<=', date('Y-m-d 00:00:00')],
                ['end_date', '>=', date('Y-m-d 00:00:00')],
                ['deleted_flg', 0]
            ])
            ->orderBy('start_date', 'DESC')
            ->get();

        //メッセージがある場合
        if (!empty($info[0])) {
            $message = $info[0]->message;
            $ymd = date("Y年m月d日", strtotime($info[0]->start_date));
            return ['message' => $message, 'ymd' => $ymd];
        } else {
            return ['message' => ''];
        }
    }
}