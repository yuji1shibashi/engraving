<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AttendanceStatusValiRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'userId' => 'integer|min:1',
            'type' => 'integer|min:1|max:4',
            'date' => 'date_format:"Y-m-d H:i:s"',
            'startDate' => 'date_format:"Y-m-d H:i:s"',
            'endDate' => 'date_format:"Y-m-d H:i:s"',
            'startShiftId' => 'integer',
            'endShiftId' => 'integer',

            // nest
            'work.startDate' => 'date_format:"Y-m-d H:i:s"',
            'work.endDate' => 'date_format:"Y-m-d H:i:s"',
            'rest.*.startDate' => 'date_format:"Y-m-d H:i:s"',
            'rest.*.endDate' => 'date_format:"Y-m-d H:i:s"',
            'start.shiftId' => 'integer|min:1',
            'start.date' => 'date_format:"Y-m-d H:i:s"',
            'end.shiftId' => 'integer|min:1',
            'end.date' => 'date_format:"Y-m-d H:i:s"',
        ];
    }

    /**
     * 項目名
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'userId' => 'ユーザID',
            'type' => '勤怠種別',
            'date' => '勤怠時間',
            'startDate' => '開始日時',
            'endDate' => '終了日時',
            'startShiftId' => '始業ID',
            'endShiftId' => '終業ID',

            // nest
            'work.startDate' => '始業時間',
            'work.endDate' => '終業時間',
            'rest.startDate' => '休憩開始時間',
            'rest.endDate' => '休憩終了時間',
            'start.shiftId' => '開始シフトID',
            'start.date' => '開始日時',
            'end.shiftId' => '終了シフトID',
            'end.date' => '終了日時',
        ];
    }

    /**
     * エラーメッセージ
     *
     * @return array
     */
    public function messages()
    {
        return [
            'userId.integer' => ':attributeの指定が正しくありません',
            'userId.min' => '存在しない:attributeが指定されています',
            'type.integer' => ':attributeの指定が正しくありません',
            'type.min' => ':attributeの指定が正しくありません',
            'type.max' => ':attributeの指定が正しくありません',
            'date' => ':attributeが年月日から時分秒まで指定されていません',
            'startDate.date_format' => ':attributeが年月日から時分秒まで指定されていません',
            'endDate.date_format' => ':attributeが年月日から時分秒まで指定されていません',
            'startShiftId' => ':attributeの指定が正しくありません',
            'endShiftId' => ':attributeの指定が正しくありません',

            // nest
            'work.startDate.date_format' => ':attributeが年月日から時分秒まで指定されていません',
            'work.endDate.date_format' => ':attributeが年月日から時分秒まで指定されていません',
            'rest.startDate.date_format' => ':attributeが年月日から時分秒まで指定されていません',
            'rest.endDate.date_format' => ':attributeが年月日から時分秒まで指定されていません',

            'start.shiftId.integer' => ':attributeの指定が正しくありません',
            'start.shiftId.min' => '存在しない:attributeが指定されています',
            'start.date.date_format' => ':attributeが年月日から時分秒まで指定されていません',
            'end.shiftId.integer' => ':attributeの指定が正しくありません',
            'end.shiftId.min' => '存在しない:attributeが指定されています',
            'end.date.date_format' => ':attributeが年月日から時分秒まで指定されていません',
        ];
    }
}
