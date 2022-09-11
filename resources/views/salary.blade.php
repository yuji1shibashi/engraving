<!doctype html>
<html lang='ja'>
	<head>
		<meta charset="UTF-8">
		<title>work_info</title>
		<link rel="stylesheet" href="{{ asset('css/salary.css') }}">
		<script src="{{ asset('js/jquery-3.4.1.min.js') }}"></script>
	</head>
	<body>
		<div class='screen'>
            @include('menu_list')
            <div class='main'>
                <header>業務状況</header>
                <input type='hidden' id='user_id' value='{{ $list["user_id"] }}'>
                <input type='hidden' id='ym_id' value='{{ $list["ym_id"]}}'>
                <div class='search_eria'>
                    <span>
                    	<label>検索年月：</label>
                        <select id='ym' class='select_box'>
                            <option value=''>未選択</option>
                            @foreach ($list['ym'] as $ym)
                        		<option value='{{ $ym->time }}'>{{ $ym->time }}</option>
                            @endforeach
                    	</select>
                   </span>
                   @if (Session::get('login_user_admin') === 1)
                       <span class='m_left'>
                            <label>ユーザー検索：</label>
                            <select id='user' style='' class='select_box'>
                                @foreach ($list['users'] as $user)
                                    <option value='{{ $user->id }}'>{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </span>
                    @endif
                </div>
                <table align="center">
                	<tr><th colspan='2'>シフト勤務時間</th></tr>
                	<tr>
	                	<td>シフト勤務時間累計</td>
	                	<td>{{ $list['shift_total'] }}時間</td>
	                </tr>
	                <tr>
	                	<td>シフト勤務時間予想給料</td>
	                	<td>{{ $list['shift_salary'] }}円</td>
	                </tr>
	                <tr><th colspan='2'>実働勤務時間</th></tr>
	                <tr>
	                	<td>実働勤務時間累計</td>
	                	<td>{{ $list['actual_total'] }}時間</td>
	                </tr>
                    <tr>
                        <td >実働給与累計</td>
                        <td>{{ $list['actual_salary'] }}円</td>
                    </tr>
	                <tr><th colspan='2'>勤務状況詳細</th></tr>
                    <tr>
                        <td>シフト時間と実働時間比較</td>
                        <td>{{ $list['total_compare'] }}時間</td>
                    </tr>
                    <tr>
                        <td>予想給料と実働給比較</td>
                        <td>{{ $list['salary_compare'] }}円</td>
                    </tr>
                    <tr>
                        <td>総残業時間</td>
                        <td>{{ $list['overtime'] }}時間</td>
                    </tr>
                    <tr>
                        <td>遅刻回数</td>
                        <td>{{ $list['tardy'] }}回</td>
                    </tr>
                    <tr>
                        <td>欠勤回数</td>
                        <td>{{ $list['absence'] }}回</td>
                    </tr>
                </table>
				<script>
					$(document).ready(function() {

                        //初期設定
                        ini();

                        //ユーザー選択時の挙動
                        $('#user').on('change', function() {
                            //必要な情報を取得
                            var ym = $('#ym').val();
                            var user_id = $('#user').val();

                            //年月が空の場合はURLを変える
                            if (ym === '') {
                                //画面リロード
                                window.location.href = '/salary/'+user_id+'/00/00';
                            } else {
                                //画面リロード
                                window.location.href = '/salary/'+user_id+'/'+ym;
                            }
                        });

                        //年月選択時の挙動
                        $('#ym').on('change', function() {
                            //必要な情報を取得
                            var ym = $('#ym').val();
                            var user_id = $('#user_id').val();

                            //年月が空の場合は処理を実行しない
                            if(ym === ''){
                                return false;
                            }
                            //画面リロード
                            window.location.href = '/salary/'+user_id+'/'+ym;
                        });
					});

                    /**
                     * 初期設定
                     */
                    function ini()
                    {
                        //必要な情報を取得
                        var user_id = $('#user_id').val();
                        var ym = $('#ym_id').val();
                        //セレクトボックスの初期値を設定
                        $('#user').val(user_id);
                        $('#ym').val(ym);

                        //初期値がない場合は未選択
                        if($('#ym').val() === null){
                             $('#ym').val('');
                        }
                    }
				</script>
			</div>
		</div>
	</body>
</html>