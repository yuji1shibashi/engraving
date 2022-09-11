<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * [DB]目標テーブルクラス
 *
 * 目標設定に関するDBをまとめたモデルクラス。
 *
 * @access public
 * @category Target
 * @package Model
 */
class DirectionManagement extends Model
{
	/**
	 * 目標レコード全件取得
	 *
	 * @access public
	 * @return array
	 */
	public function getTargets()
	{
		$targets = DB::table('direction_management')
			->select('id', 'type', 'direction')
			->where('deleted_flg', '=', 0)
			->orderBy('type', 'asc')
			->get();

		return $targets;
	}

	/**
	 * 目標作成
	 *
	 * @access public
	 * @param array $data
	 * @return int
	 */
	public function registTarget(array $data)
	{
		//トランザクション開始
        DB::transaction(function () use ($data) {
            //新規登録処理でID取得
            $insert_id = DB::table('direction_management')
                ->insertGetId(
                [
                    'direction' => $data['text'],
                    'type' => $data['type_id'],
                    'deleted_flg' => 0
                ]
            );
			return $insert_id;
        });
	}

	/**
	 * 目標編集
	 *
	 * @access public
	 * @param array $data
	 */
	public function updateTarget(array $data)
	{
		//トランザクション開始
        DB::transaction(function () use ($data) {
            //更新処理
            DB::table('direction_management')
                ->where('id', $data['register_id'])
                ->update(
                [
                    'direction' => $data['text'],
                    'type' => $data['type_id']
                ]
            );
        });
	}

	/**
	 * 目標削除
	 *
	 * @access public
	 * @param array $data
	 */
	public function deleteTarget(array $data)
	{
		//トランザクション開始
        DB::transaction(function () use ($data) {
            //削除処理
            DB::table('direction_management')
                ->where('id', $data['register_id'])
                ->update(['deleted_flg' => 1]
            );
        });
	}
}