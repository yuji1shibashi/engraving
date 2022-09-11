<?php

namespace App\Http\Controllers;

use App\Models\UserAuth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

/**
 * [API]占いコントローラークラス
 *
 * 占い結果を作成するAPIをまとめたコントローラークラス。
 *
 * @access public
 * @category Fortune
 * @package Controller
 */
class FortuneController extends Controller
{
	/**
	 * おみくじ定数
	 */
	const FORTUNE = array(
		1 => '大吉',
		2 => '中吉',
		3 => '吉',
		4 => '小吉',
		5 => '凶',
	);

	/**
	 * ユーザー名
	 *
	 * @var string
	 */
	private $name = '';

	/**
	 * おみくじインデックス
	 *
	 * @var integer
	 */
	private $fortune_index = 0;

	/**
	 * おみくじ部分メッセージ
	 *
	 * @var string
	 */
	private $fortune = '';

	/**
	 * 占いアドバイス
	 *
	 * @var string
	 */
	private $message = '';

	/**
	 * 占い結果取得機能
	 *
	 * @access public
	 * @param Request $request
	 * @return array
	 */
	public function index(Request $request)
	{
		//占いアドバイスに登場するユーザーセット
		self::setStaffName($request);

		//おみくじ結果をセット
		self::setFortuneIndex();

		//メッセージ内容をセット
		self::setMessage($request);

		//JSONで返す内容を配列に格納
		$json = array(
			'fortune' => $this->fortune,
			'message' => $this->message
		);
		//レスポンスを返す
	    return $json;
	}

	/**
	 * 占いアドバイスに登場するユーザーセット
	 *
	 * @access public
	 * @param Request $request
	 */
	public function setStaffName(Request $request)
	{
		//ログインユーザーID取得
		$id = $request->session()->get('login_user_id');

		//ログインユーザー以外のユーザーを全件取得
		$users = DB::table('users')
			->select('id', 'name')
			->where('id', '<>', $id)
			->where('deleted_flg', '=', 0)
			->get();

		//取得したユーザーの中からランダムで1人ユーザー取得しプロパティにセット
		$index = rand(0, (count($users) - 1));
		$this->name = $users[$index]->name;
	}

	/**
	 * おみくじ結果をセット
	 *
	 * @access public
	 */
	public function setFortuneIndex()
	{
		//ランダムで取得したおみくじ結果をプロパティにセット
		$this->fortune_index = rand(1, count(self::FORTUNE));
	}

	/**
	 * メッセージ内容をセット
	 *
	 * @access public
	 * @param Request $request
	 * @return void
	 */
	public function setMessage(Request $request)
	{
		//ログインユーザー名を取得
		$user_name = $request->session()->get('login_user_name');

		//おみくじの結果をセット
		$this->fortune = "今日の".$user_name."さんの運勢は".self::FORTUNE[$this->fortune_index]."です。";

		//大吉の場合のメッセージ
		if ($this->fortune_index === 1) {
			$this->message = "ハッピーな1日になるでしょう。〇".$this->name."さんになにか奢ってもらえるかも！";
			return;
		}

		//中吉の場合のメッセージ
		if ($this->fortune_index === 2) {
			$this->message = "なんだか良いことがありそうです。〇".$this->name."さんに褒めてもらえる1日になりそうです。";
			return;
		}

		//吉の場合のメッセージ
		if ($this->fortune_index === 3) {
			$this->message = "平凡な1日になりそう。〇".$this->name."さんとおしゃべりしすぎないように気を付けて！";
			return;
		}

		//小吉の場合のメッセージ
		if ($this->fortune_index === 4) {
			$this->message = "忙しい1日になるでしょう。〇".$this->name."さんと頑張ればなんとか乗り切れそうです。";
			return;
		}

		//凶の場合のメッセージ
		if ($this->fortune_index === 5) {
			$this->message = "失敗続きな1日になるでしょう。〇".$this->name."さんに怒られるかも。";
			return;
		}
	}
}
