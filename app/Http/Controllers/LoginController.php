<?php

namespace App\Http\Controllers;

use App\Models\UserAuth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\User;

/**
 * [API]ログインコントローラークラス
 *
 * ログイン情報に関するAPIをまとめたコントローラークラス。
 *
 * @access public
 * @category Login
 * @package Controller
 */
class LoginController extends Controller
{
    /**
	 * ログインが済んでいるかをチェック
	 *
	 * @access public
	 * @param  Request $request
	 * @return mixed
	 */
    public function auth(Request $request)
    {
    	//現在のURLを取得
	    $url = explode('/', url()->current());
	    $url = $url[count($url) - 1];

    	//セッション情報が存在するかをチェック
    	if ($request->session()->get('login_user_id')) {
    		//存在する場合はメイン画面に遷移
    		return ($url === 'main')? view('main') : redirect('main');
    	}
    	//存在しない場合はログイン画面に遷移
    	return ($url === 'login')? view('login') : redirect('login');
    }

	/**
	 * ログインフォームとユーザー情報が一致するかをチェックする
	 *
	 * @access public
	 * @param  Request $request
	 * @return array
	 */
    public function check(Request $request)
    {
        $userModel = new User();
    	//ログインフォーム取得
        $post = $request->only('employee_number', 'password');

        // チェック用データ取得
        $res = $userModel->getCheckData($post);

        if (!is_null($res)) {
            //パスワードが一致するかチェック
            if (password_verify($post['password'], $res->password)) {
                //ログインしたユーザーIDを保持
                session(
                    [
                        'login_user_id' => $res->id,
                        'login_user_name' => $res->name,
                        'login_user_admin' => $res->admin
                    ]
                );
                //メイン画面に遷移する
                return redirect('main');
            }
        }
        //パスワードが一致しない場合はエラーメッセージを表示する
        $text = '従業員番号または、パスワードが一致しません';
        return view('login', compact('text'));
    }

    /**
	 * ログアウト処理を行う
	 *
	 * @access public
	 * @param  Request $request
	 * @return mixed
	 */
    public function logout(Request $request)
    {
    	//セッション情報を破棄
    	$request->session()->flush();

    	//ログイン画面に遷移する
    	return redirect('login');
    }
}
