<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class User extends Model
{
    /**
     * getUsers
     * Usersテーブルデータを取得
     * @access public
     * @return array $users
     */
    public function getUsers($search = null)
    {
        $stmt = DB::table('users')
            ->join('personal_information', 'personal_information.user_id', '=', 'users.id')
            ->join('employee_number', 'employee_number.user_id', '=', 'users.id')
            ->select('users.id', 'users.name', 'personal_information.sex', 'personal_information.birthday',
                'personal_information.address', 'personal_information.telephone', 'personal_information.mail',
                'employee_number.employee_number')
            ->where('deleted_flg', 0);
        if( !empty($search) ){
            $stmt->where('users.name', 'like', '%'.$search.'%');
        }
        $users = $stmt->get();

        return $users;
    }

    /**
     * getUser
     * ユーザー情報を取得する
     *
     * @param string $id ユーザーID
     * @return array $data ユーザー情報
     */
    public function getUser($id)
    {
        $data = DB::table('users')
            ->join('personal_information', 'personal_information.user_id', '=', 'users.id')
            ->join('system_settings', 'system_settings.user_id', '=', 'users.id')
            ->join('employee_number', 'employee_number.user_id', '=', 'users.id')
            ->select('users.name', 'personal_information.sex', 'personal_information.birthday',
                'personal_information.address', 'personal_information.telephone', 'personal_information.mail',
                'personal_information.memo', 'system_settings.language', 'system_settings.admin',
                'employee_number.employee_number')
            ->where('users.id', $id)
            ->get();

        return $data;
    }

    /**
     * getUsersSimpleData
     * Usersの簡易データを取得
     * @access public
     * @return Collection $users
     */
    public function getUsersSimpleData()
    {
        $users = DB::table('users')
            ->select('users.id', 'users.name')
            ->where('deleted_flg', 0)
            ->get();

        return $users;
    }

    /**
     * getUserSimpleData
     * Usersの簡易個人データを取得
     * @access public
     * @param $id
     * @return Collection $users
     */
    public function getUserSimpleData($id)
    {
        $user = DB::table('users')
            ->select('users.id', 'users.name')
            ->where([
                ['id', $id],
                ['deleted_flg', 0],
            ])
            ->first();

        return $user;
    }

    /**
     * registUser
     * usersテーブル登録
     * @access public
     * @param array $data フォームデータ
     * @return string $id 登録したユーザーのID
     */
    public function registUser(array $data)
    {
        $id = DB::table('users')->insertGetId([
            'name' => $data['name'],
            'password' => Hash::make($data['password']),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('personal_information')->insert([
            'user_id' => $id,
            'sex' => $data['sex'],
            'birthday' => $data['year'] . '-' . $data['month'] . '-' . $data['day'],
            'address' => $data['address'],
            'telephone' => $data['tel'],
            'mail' => $data['mail'],
            'memo' => $data['memo'],
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('system_settings')->insert([
            'user_id' => $id,
            'language' => $data['lang'],
            'admin' => $data['admin'],
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // 最新の従業員番号を取得
        $res = DB::table('employee_number')
            ->select('employee_number')
            ->latest()
            ->first();

        DB::table('employee_number')->insert([
            'user_id' => $id,
            'employee_number' => $res->employee_number + 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        return;
    }

    /**
     * updateUser
     * ユーザー更新処理
     *
     * @param array $data フォームデータ
     * @param string $id ユーザーID
     * @return void
     */
    public function updateUser(array $data, string $id)
    {
        DB::table('users')
            ->where('id', $id)
            ->update([
                'name' => $data['name'],
                'password' => Hash::make($data['password']),
                'updated_at' => Carbon::now(),
            ]);

        DB::table('personal_information')
            ->where('user_id', $id)
            ->update([
                'sex' => $data['sex'],
                'birthday' => $data['year'] . '-' . $data['month'] . '-' . $data['day'],
                'address' => $data['address'],
                'telephone' => $data['tel'],
                'mail' => $data['mail'],
                'memo' => $data['memo'],
                'updated_at' => Carbon::now(),
            ]);

        DB::table('system_settings')
            ->where('user_id', $id)
            ->update([
                'language' => $data['lang'],
                'admin' => $data['admin'],
                'updated_at' => Carbon::now(),
            ]);

        return;
    }

    /**
     * deleteUser
     * 削除フラグを立てる
     *
     * @param string $id ユーザーID
     * @return void
     */
    public function deleteUser(string $id)
    {
        DB::table('users')
            ->where('id', $id)
            ->update([
                'deleted_flg' => 1
            ]);

        return;
    }

    /**
     * getCheckData
     * リクエストデータを元に、認証に必要なデータを返す
     *
     * @param array $post
     * @return object $res
     */
    public function getCheckData(array $post)
    {
        $res = DB::table('users')
            ->select('users.id', 'users.name', 'users.password', 'employee_number.employee_number', 'system_settings.admin')
            ->join('employee_number', 'users.id', '=', 'employee_number.user_id')
            ->join('system_settings', 'users.id', '=', 'system_settings.user_id')
            ->where('employee_number.employee_number', $post['employee_number'])
            ->where('users.deleted_flg', '0')
            ->first();

        return $res;
    }
}
