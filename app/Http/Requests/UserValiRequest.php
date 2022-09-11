<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserValiRequest extends FormRequest
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
            'name'     => 'required|string|max:255',
            'sex'      => 'required',
            'year'     => 'required',
            'month'    => 'required',
            'day'      => 'required',
            'tel'      => 'required|string|digits_between:8,11',
            'address'  => 'required|string',
            'mail'     => 'required|email|max:255',
            'lang'     => 'required',
            'password' => 'required|string|max:20|confirmed',
            'admin'    => 'required',
            'memo'     => '',
        ];
    }

    /**
     * 項目名
     *
     * @return void
     */
    public function attributes()
    {
        return [
            'name'     => '氏名',
            'sex'      => '性別',
            'year'     => '年',
            'month'    => '月',
            'day'      => '日',
            'tel'      => '電話番号',
            'address'  => '住所',
            'mail'     => 'メールアドレス',
            'lang'     => '言語',
            'password' => 'パスワード',
            'admin'    => '管理者権限',
            'memo'     => '備考',
        ];
    }

    /**
     * エラーメッセージ
     *
     * @return void
     */
    public function messages()
    {
        return [
            'name.required' => ':attributeが入力されていません',
            'name.string'   => ':attirbuteは正しい形式で入力してください',
            'name.max'      => ':attributeは全角255文字以内で入力してください',
            'sex.required'  => ':attributeが選択されていません',
            'year.required' => ':attributeが選択されていません',
            'month.required' => ':attributeが選択されていません',
            'day.required'   => ':attributeが選択されていません',
            'tel.required'   => ':attributeを入力してください',
            'tel.string'     => ':attributeは電話番号形式で入力してください',
            'tel.digits_between' => ':attributeは数字8桁以上12桁以下で入力してください',
            'address.required'   => ':attributeを入力してください',
            'mail.required'      => ':attributeを入力してください',
            'mail.max'           => ':attributeは半角255文字以内で入力してください',
            'mail.email'         => ':attributeの形式で入力してください',
            'lang.required'      => ':attributeを入力してください',
            'password.required'  => ':attributeは必須項目です',
            'password.confirmed' => ':attributeが確認したものと異なります',
            'password.max'       => ':attributeは半角20文字以内で入力してください',
            'admin.required'     => ':attributeは必須項目です',
        ];
    }
}
