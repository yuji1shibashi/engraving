<!doctype html>
<html lang='ja'>
    <head>
        <meta charset="UTF-8">
        <title>target</title>
        <link rel="stylesheet" href="{{ asset('css/target.css') }}">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <script src="{{ asset('js/jquery-3.4.1.min.js') }}"></script>
    </head>
    <body>
        <div class='screen'>
            @include('menu_list')
            <div class='main'>
                <header>目標一覧</header>
                <form id="target_form" method="POST" action="">
                    <!-- CSRF保護 -->
                    @csrf
                    <table align="center">
                        <thead>
                            <tr>
                                <th class='width_10'>ランク</th>
                                @if (Session::get('login_user_admin') === 1)
                                    <th class='width_64'>目標内容</th>
                                    <th class='width_3'>編集</th>
                                    <th class='width_3'>削除</th>
                                @else
                                    <th class='width_70'>目標内容</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody id='target_body'>
                        @foreach ($targets as $target)
                            <tr>
                                <input type='hidden' id='target_id' value="{{ $target->id }}">
                                <input type='hidden' id='type_id' value="{{ $target->type }}">
                                @if ($target->type === 1)
                                    <td class='width_10'>初級</td>
                                @elseif ($target->type === 2)
                                    <td class='width_10'>中級</td>
                                @else
                                    <td class='width_10'>上級</td>
                                @endif
                                @if (Session::get('login_user_admin') === 1)
                                    <td class='width_64 text_left'>
                                        <span class='target'>{{ $target->direction }}</span>
                                    </td>
                                    <td class='width_3'>
                                        <button type='button' class='edit_btn'>編集</button>
                                    </td>
                                    <td class='width_3'>
                                        <button type='button' class='delete_btn'>削除</button>
                                    </td>
                                @else
                                    <td class='width_70 text_left'>
                                        <span class='target'>{{ $target->direction }}</span>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    @if (Session::get('login_user_admin') === 1)
                        <div id='mode' align='center'>～　新規作成　～</div>
                        <div id='edit_eria'>
                            <input type='hidden' id='mode_str' value="create">
                            <input type='hidden' id='register_id' value="">
                            <label>ランク：</label>
                            <select id='target_select'>
                                <option value=1>初級</option>
                                <option value=2>中級</option>
                                <option value=3>上級</option>
                            </select>
                            <label id='target_title'>目標：</label>
                            <input type='text' id='target_text'>
                            <button type='button' id='register_btn'>追加</button>
                            <button type='button' id='create_btn'>新規作成</button>
                        </div>
                    @endif
                </form>
            </div>
        </div>
        <script>
            $(document).ready(function() {

                //新規作成ボタン押下時の処理
                $('#create_btn').on('click', function() {
                    //目標に入力があるかをチェック
                    if (inputCheck('change')) {
                        return false;
                    }
                    //入力モード変更
                    modeChange('create', $(this));
                });

                //編集ボタン押下時の処理
                $('.edit_btn').on('click', function() {
                    //目標に入力があるかをチェック
                    if (inputCheck('change')) {
                        return false;
                    }
                    //入力モード変更
                    modeChange('edit', $(this));
                });

                //削除ボタン押下時の処理
                $('.delete_btn').on('click', function() {
                    //目標削除行うかを確認する
                    registerCheck('delete', $(this));
                });

                //追加、修正ボタン押下時の処理
                $('#register_btn').on('click', function() {
                    //目標追加、修正行うかを確認する
                    registerCheck();
                });
            });

            /**
             * モードの切り替えを行う
             *
             * @param string type
             */
            function modeChange(type, obj)
            {
                //新規作成モード
                if (type === 'create') {
                    //表示されているデータをリセットする
                    var target_id = '';
                    var type_id = '1';
                    var text = '';
                    var btn = '追加';
                    var mode = '～　新規作成　～';
                    var mode_str = 'create';
                }

                //編集モード
                if (type === 'edit') {
                    //目標情報を取得する
                    var target_id = obj.closest('tr').find('#target_id').val();
                    var type_id = obj.closest('tr').find('#type_id').val();
                    var text = obj.closest('tr').find('.target').text();
                    var btn = '修正';
                    var mode = '～　編集　～';
                    var mode_str = 'update';
                }

                //表示されているデータを切り替える
                $('#register_id').val(target_id);
                $('#target_select').val(type_id);
                $('#target_text').val(text);
                $('#register_btn').text(btn);
                $('#mode').text(mode);
                $('#mode_str').val(mode_str);
            }

            /**
             * 登録、更新、削除処理を行うAjax
             *
             * @param string type
             * @return boolean|void
             */
            function registerAjax(type)
            {
                //二重送信防止
                $('#register_btn').css('pointer-events','none');

                //入力チェックを行う
                if (type !== 'delete' && inputCheck('register')) {
                    return false;
                }

                //必要な情報を取得する
                var register_id = $('#register_id').val();
                var type_id = $('#target_select').val();
                var text = $('#target_text').val();
                var url = '';

                //API先のURLを取得する
                url = getUrl(type);

                //目標作成、編集、削除処理
                $.ajaxSetup({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
                });
                $.ajax({
                    url: url,
                    type: 'post',
                    dataType: 'json',
                    data : {
                        register_id: register_id,
                        type_id: type_id,
                        text: text
                    },
                })
                .done(function(data){
                    //二重送信解除
                    $('#register_btn').css('pointer-events','auto');
                    //メッセージ表示
                    alert(data['message']);
                    //ページをリロードする
                    location.reload();
                })
                .fail (function(){
                    alert('エラーが発生しました。');
                    //二重送信解除
                    $('#register_btn').css('pointer-events','auto');
                });
            }

            /**
             * APIのURLを取得する
             *
             * @param string type
             * @return string
             */
            function getUrl(type)
            {
                // 新規作成
                if (type === 'create') {
                    url = 'target/create';
                }
                // 更新
                if (type === 'update') {
                    url = 'target/update';
                }
                // 削除
                if (type === 'delete') {
                    url = 'target/delete';
                }
                return url;
            }

            /**
             * 入力チェック
             *
             * @param string mode
             * @return boolean
             */
            function inputCheck(mode)
            {
                //目標作成、編集の場合
                if (mode === 'register') {
                    //目標が空の場合
                    if ($('#target_text').val() === '') {
                        //二重送信解除
                        $('#register_btn').css('pointer-events','auto');
                        //エラーメッセージ表示
                        alert('目標が入力されていません。');
                        return true;
                    }
                }
                //目標入力モードを変更する場合
                if (mode === 'change') {
                    //目標が入力されている場合
                    if ($('#target_text').val() !== '') {
                        //確認メッセージ表示
                        if(!confirm('入力中の内容がリセットされますがよろしいですか？')){
                            return true;
                        }
                    }
                }
                return false;
            }

            /**
             * 作成・編集・削除処理を行うかを確認する
             *
             * @param string type
             * @param object obj
             * @return boolean
             */
            function registerCheck(type, obj)
            {
                //削除処理の場合
                if (type === 'delete') {
                    //確認メッセージ表示
                    if(!confirm('目標を削除してよろしいですか？')){
                        return false;
                    }
                    //削除を行う場合は目標IDを格納する
                    var target_id = obj.closest('tr').find('#target_id').val();
                    $('#register_id').val(target_id);
                } else {
                    //削除以外の場合は作成か編集かを取得する
                    var type = $('#mode_str').val();
                    var str = (type === 'create')? '作成' : '修正';

                    //確認メッセージ表示
                    if(!confirm('入力内容で'+str+'してもよろしいですか？')){
                        return false;
                    }
                }
                //AJAXを使って処理を実行する
                registerAjax(type);
            }
        </script>
    </body>
</html>
