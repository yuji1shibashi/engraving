<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DirectionManagement as DirectionManagement;

/**
 * [API]目標コントローラークラス
 *
 * 目標設定に関するAPIをまとめたコントローラークラス。
 *
 * @access public
 * @category Target
 * @package Controller
 */
class TargetController extends Controller
{
    /**
     * 目標テーブルオブジェクト
     *
     * @access protected
     * @var object
     */
    protected $directionManagement;

    /**
     * インスタンス生成
     *
     * @access public
     */
    public function __construct()
    {
        $this->directionManagement = new DirectionManagement();
    }

    /**
	 * 表示する目標一覧を取得する
	 *
	 * @access public
	 * @param  Request $request
	 * @return mixed
	 */
    public function list(Request $request)
    {
        //ログインしているかをチェックする
        if (is_null($request->session()->get('login_user_id'))) {
            return redirect('login');
        }

    	//目標レコード全件取得
        $targets = $this->directionManagement->getTargets();

        //現在のURLを取得
        $url = explode('/', url()->current());
        $url = $url[count($url) - 1];

        //存在する場合はメイン画面に遷移
        return ($url === 'target')? view('target', compact('targets')) : redirect('target', compact('targets'));
    }

    /**
     * 目標を作成する
     *
     * @access public
     * @param  Request $request
     * @return array
     */
    public function create(Request $request)
    {
        //変数の初期値を設定
        $insert_id = '';

        //ログインフォーム取得
        $post = $request->all();

        //登録処理
        $insert_id = $this->directionManagement->registTarget($post);

        //レスポンス結果メッセージ格納
        $message = '目標作成が完了しました。';

        //JSONで返す内容を配列に格納
        $json = array(
            'insert_id' => $insert_id,
            'message' => $message
        );
        //レスポンスを返す
        return $json;
    }

    /**
     * 目標を編集する
     *
     * @access public
     * @param  Request $request
     * @return array
     */
    public function update(Request $request)
    {
        //変数の初期値を設定
        $insert_id = '';

        //ログインフォーム取得
        $post = $request->all();

        //更新処理
        $this->directionManagement->updateTarget($post);

        //レスポンス結果メッセージ格納
        $message = '目標修正が完了しました。';

        //JSONで返す内容を配列に格納
        $json = array(
            'message' => $message
        );
        //レスポンスを返す
        return $json;
    }

    /**
     * 目標を削除する
     *
     * @access public
     * @param  Request $request
     * @return array
     */
    public function delete(Request $request)
    {
       //ログインフォーム取得
        $post = $request->all();

        //削除処理
        $this->directionManagement->deleteTarget($post);

        //レスポンス結果メッセージ格納
        $message = '目標削除が完了しました。';

        //JSONで返す内容を配列に格納
        $json = array(
            'message' => $message
        );
        //レスポンスを返す
        return $json;
    }
}
