<!doctype html>
<html lang='ja'>
    <head>
        <meta charset="UTF-8">
        <title>information</title>
        <link rel="stylesheet" href="{{ asset('css/information.css') }}">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <script src="{{ asset('js/jquery-3.4.1.min.js') }}"></script>
    </head>
    <body>
        <div class='screen'>
            @include('menu_list')
            <div class='main'>
                <header>インフォメーション</header>
                @if( !empty($errorMsg) )
                <div class="error_msg"></div>
                @endif
                @if( !empty($msg) )
                <div class="info_msg"></div>
                @endif
                <form id="info_form" method="POST" action="">
                    <!-- CSRF保護 -->
                    @csrf
                    <table align="center">
                        <thead>
                            <tr>
                                <th class='width_10'>送信先</th>
                                <th class='width_47'>インフォメーション内容</th>
                                <th class='width_8'>掲載開始</th>
                                <th class='width_8'>掲載終了</th>
                                <th class='width_3'>編集</th>
                                <th class='width_3'>削除</th>
                            </tr>
                        </thead>
                        <tbody id='info_body'>
                        @if($msgList)
                        @foreach ($msgList as $key => $val)
                            <tr>
                                <input type='hidden' id="target_id" value="{{$val->id}}">
                                <input type='hidden' id="user_id" value="{{$val->to_user_id}}">
                                @if(empty($val->name))
                                <td class='width_10'>全体</td>
                                @else
                                <td class='width_10'>{{$val->name}}</td>
                                @endif
                                <td class='width_47 text_left'>
                                    <input type='hidden' class='info' value='{{ $val->message }}'>
                                    <span>{{ mb_strimwidth($val->message, 0, 80, "...", "UTF-8") }}</span>
                                </td>
                                <td class='width_8 start_date'>{{ $val->start_date }}</td>
                                <td class='width_8 end_date'>{{ $val->end_date }}</td>
                                <td class='width_3'>
                                    <button type='button' class='edit_btn'>編集</button>
                                </td>
                                <td class='width_3'>
                                    <button type='button' class='delete_btn'>削除</button>
                                </td>
                            </tr>
                        @endforeach
                        @endif
                        </tbody>
                    </table>
                    <div id='mode' align='center'>～　新規作成　～</div>
                    <div id='edit_eria'>
                        <input type='hidden' id='mode_str' value="create">
                        <input type='hidden' id='register_id' value="">
                        <input type='hidden' id='original_user_id' value="">
                        <input type='hidden' id='to_user_id' value="">
                        <div id='form_eria'>
                            <span class='block'>
                                <label>掲載開始：</label><input type='date' id='start_date'>
                            </span>
                            <span class='block space'>
                                <label>掲載終了：</label><input type='date' id='end_date'>
                            </span>
                            <label id='title'>送信先　：</label>
                            <select id='info_select'>
                                <option value="">全体</option>
                                @foreach ($user as $val)
                                <option value="{{$val->id}}">{{$val->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <label id='info_title'>内容：</label>
                        <textarea id='info_text'></textarea>
                    </div>
                    <div id='btns' align="center">
                        <button type='button' id='register_btn'>追加</button>
                        <button type='button' id='create_btn'>新規作成</button>
                    </div>
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
        var user_id = '';
        var type_id = '1';
        var text = '';
        var btn = '追加';
        var mode = '～　新規作成　～';
        var mode_str = 'create';
    }

    //編集モード
    if (type === 'edit') {
        //目標情報を取得する
        var user_id = obj.closest('tr').find('#user_id').val();
        var target_id = obj.closest('tr').find('#target_id').val();
        var start_date = createDate(obj.closest('tr').find('.start_date').text());
        var start_array = start_date.split("-");
        var month = ( '00'  + start_array[1] ).slice( -2 );
        var day = ( '00'  + start_array[2] ).slice( -2 );
        start_date = start_array[0]+'-'+month+'-'+day;
        var end_date = createDate(obj.closest('tr').find('.end_date').text());
        var end_array = end_date.split("-");
        var month = ( '00'  + end_array[1] ).slice( -2 );
        var day = ( '00'  + end_array[2] ).slice( -2 );
        end_date = end_array[0]+'-'+month+'-'+day;
        var text = obj.closest('tr').find('.info').val();
        var btn = '修正';
        var mode = '～　編集　～';
        var mode_str = 'update';
    }

    //表示されているデータを切り替える
    $('#register_id').val(target_id);
    if(user_id == ""){
        $('#original_user_id').val("");
    }else{
        $('#original_user_id').val(user_id);
    }
    $('#info_select').val(user_id);
    $('#start_date').val(start_date);
    $('#end_date').val(end_date);
    $('#info_text').val(text);
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
    var original_user_id = $('#original_user_id').val();
    var user_id = $('#info_select').val();
    var text = $('#info_text').val();
    var start_date = $('#start_date').val();
    var end_date = $('#end_date').val();
    var url = '';
    if(type === 'delete'){
        user_id = $('#to_user_id').val();
    }

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
            message_id: register_id,
            to_send: user_id,
            message: text,
            start_date:start_date,
            end_date:end_date,
            original_user_id:original_user_id
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
        url = 'information/create';
    }
    // 更新
    if (type === 'update') {
        url = 'information/update';
    }
    // 削除
    if (type === 'delete') {
        url = 'information/delete';
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
        if ($('#info_text').val() === '') {
            //二重送信解除
            $('#register_btn').css('pointer-events','auto');
            //エラーメッセージ表示
            alert('メッセージが入力されていません');
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
        var user_id = obj.closest('tr').find('#user_id').val();
        $('#to_user_id').val(user_id);
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

function createDate(date){
    var date = new Date(date);
    var year = date.getFullYear();
    var month = date.getMonth() + 1;
    month = ('00'+month).slice(-2);
    var day = date.getDate();
    return year + '-' + month + '-' + day;
}
        </script>
    </body>
</html>
