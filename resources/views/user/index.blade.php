<!doctype html>
<html lang='ja'>
    <head>
        <meta charset="UTF-8">
        <title>ユーザー一覧</title>
        <link rel="stylesheet" href="{{ asset('css/user_list.css') }}">
        <script src="{{ asset('js/jquery-3.4.1.min.js') }}"></script>
    </head>
    <body>
        <div class='screen'>
            @include('menu_list')
            <div class='main'>
                <header>ユーザー一覧</header>
                <form id="user_form" method="POST" action="/user/index">
                    <!-- CSRF保護 -->
                    @csrf
                    <div class='height_10'>
                        <div class='search'>
                            <label>名前検索：</label>
                            <input type='text' name='user_search' id='user_search' maxlength="100">
                            <button type='button' id='search_btn'>検索</button>
                            <button type='button' id='creat_btn'>新規登録</button>
                            <button type='button' id='clear_btn'>クリア</button>
                            @if(!empty($search))
                            <div>
                                <label>検索条件：{{ $search }}</label>
                            </div>
                            @endif
                        </div>
                    </div>
                    <table align='center'>
                        <thead>
                            <tr>
                                <th class='width_3'>従業員<br>番号</th>
                                <th class='width_7'>名前</th>
                                <th class='width_2'>性別</th>
                                <th class='width_7'>誕生日</th>
                                <th class='width_27'>住所</th>
                                <th class='width_8'>電話番号</th>
                                <th class='width_17'>メールアドレス</th>
                                <th class='width_3'>変更</th>
                                <th class='width_3'>削除</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $user)
                                <tr>
                                    <td class='width_3'>{{$user->employee_number}}</td>
                                    <td class='width_7'>{{$user->name}}</td>
                                    @if ($user->sex === 0)
                                        <td class='width_2'>男</td>
                                    @else
                                        <td class='width_2'>女</td>
                                    @endif
                                    <td class='width_7'>{{$user->birthday}}</td>
                                    <td class='width_27 t_left'>
                                        <span class='m_left'>{{$user->address}}</span>
                                    </td>
                                    <td class='width_8'>{{$user->telephone}}</td>
                                    <td class='width_17 t_left'>
                                        <span class='m_left'>{{$user->mail}}</span>
                                    </td>
                                    <td class='width_3'>
                                        <a href="/user/edit/{{$user->id}}"><button type='button' class='edit_btn'>変更</button></a>
                                    </td>
                                    <td class='width_3'>
                                        <a href="/user/delete/{{$user->id}}"><button type='button' class='delete_btn'>削除</button></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </form>
            </div>
        </div>
        <script>
            $(document).ready(function() {

                // 検索ボタン押下時の挙動
                $('#search_btn').on('click', function() {
                    $('#user_form').submit();
                });

                // 新規作成ボタン押下時の挙動
                $('#creat_btn').on('click', function() {
                    window.location.href = '/user/register';
                });

                $('#clear_btn').on('click', function(){
                    window.location.href = '{{url('user/index')}}';
                });

                // 編集ボタン押下時の挙動
                // $(document).on('click', '.edit_btn', function() {
                //     alert('編集ページに飛びます。');
                // });

                // 削除ボタン押下時の挙動
                $(document).on('click', '.delete_btn', function() {
                    if(confirm('削除しますか？') == false) {
                        return false;
                    };
                });
            });
        </script>
    </body>
</html>
