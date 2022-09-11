<?php

namespace App\Http\Controllers;

use App\Models\UserAuth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User as User;
use App\Http\Requests\UserValiRequest as UserValiRequest;


class UserController extends Controller
{
    protected $user;
    protected $request;

    public function __construct(Request $request)
    {
        $this->user = new User();
        $this->request = $request;
    }

    public function check_auth()
    {
        //ログインしているかをチェックする
        if (is_null($this->request->session()->get('login_user_id'))) {
            return true;
        }
        if (!$this->request->session()->get('login_user_admin')) {
            return true;
        }
        return false;
    }

    /**
     * index
     * ユーザー一覧表示
     */
    public function index(Request $request)
    {
        if (self::check_auth()) {
            return redirect('login');
        }
        $search = $request->input('user_search', '');

        $data = $this->user->getUsers($search);
        return view('user/index', compact('data', 'search'));
    }

    public function register()
    {
        if (self::check_auth()) {
            return redirect('login');
        }
        return view('user/register');
    }

    /**
     * ユーザー登録処理
     *
     * @param UserValiRequest $request
     * @return string $session['msg'] 登録メッセージ
     */
    public function regist(UserValiRequest $request)
    {
        if (self::check_auth()) {
            return redirect('login');
        }
        $post = $request->validated();
        $this->user->registUser($post);

        return redirect('/user/register')->with('msg', '登録しました');
    }

    /**
     * ユーザー編集
     *
     * @param string $userId ユーザーID
     * @return array $userData ユーザーデータ
     */
    public function edit($userId)
    {
        if (self::check_auth()) {
            return redirect('login');
        }
        $userData = $this->user->getUser($userId);
        foreach($userData as $data) {
            $data->birthday = explode('-', $data->birthday);
        }

        return view('user/edit', compact('userData', 'userId'));
    }

    /**
     * ユーザー更新
     *
     * @param UserValiRequest $request フォームデータ
     * @param string $id ユーザーID
     * @return void
     */
    public function update(UserValiRequest $request, $id)
    {
        if (self::check_auth()) {
            return redirect('login');
        }
        $post = $request->validated();
        $this->user->updateUser($post, $id);

        return redirect('user/edit/' . $id)->with('msg', '更新しました');
    }

    /**
     * ユーザー削除
     *
     * @param string $id ユーザーID
     * @return void
     */
    public function delete($id)
    {
        if (self::check_auth()) {
            return redirect('login');
        }
        $this->user->deleteUser($id);

        return redirect('user/index');
    }
}
