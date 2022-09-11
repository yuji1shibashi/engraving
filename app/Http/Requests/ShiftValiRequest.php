<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShiftValiRequest extends FormRequest
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
            'shiftId' => 'integer|min:1',
            'startDate' => 'required|date_format:"Y-m-d H:i:s"',
            'endDate' => 'required|date_format:"Y-m-d H:i:s"',
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
            'shiftId' => 'シフトID',
            'startDate' => '始業日時',
            'endDate' => '就業日時',
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
            'startDate.required' => ':attributeが指定されていません',
            'startDate.date_format' => ':attributeが年月日から時分秒まで指定されていません',
            'endDate.required' => ':attributeが指定されていません',
            'endDate.date_format' => ':attributeが年月日から時分秒まで指定されていません',
        ];
    }
}
