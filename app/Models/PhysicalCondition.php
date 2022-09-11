<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class PhysicalCondition extends Model
{
    /**
     * 体調データ作成
     *
     * @access public
     * @param array $data
     */
    public function condition(array $data)
    {
        //トランザクション開始
        DB::transaction(function () use ($data) {
            //新規登録処理
            DB::table('physical_condition')->insert([
                'shift_user_id' => $data['user_id'],
                'shift_id' => $data['shift_id'] ?? 0,
                'symptoms' => $data['condition'],
                'deleted_flg' => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        });
    }

    /**
     * 体調データ取得
     *
     * @access public
     * @param int $user_id
     * @param int $shift_id
     * @return array
     */
    public function getCondition(int $user_id, int $shift_id)
    {
        //打刻データを取得
        return DB::table('physical_condition')
            ->select('physical_condition.symptoms')
            ->where([
                ['shift_user_id', '=', $user_id],
                ['shift_id', '=', $shift_id],
                ['deleted_flg', '=', 0],
            ])
            ->orderBy('id', 'DESC')
            ->first();
    }
}
