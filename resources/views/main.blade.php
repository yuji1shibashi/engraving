<!doctype html>
<html lang='ja'>
	<head>
		<meta charset="UTF-8">
		<title>main</title>
		<link rel="stylesheet" href="{{ asset('css/main.css') }}">
		<meta name="csrf-token" content="{{ csrf_token() }}">
		<script src="{{ asset('js/jquery-3.4.1.min.js') }}"></script>
	</head>
	<body>
		<div id="main_modalwin" class="modalwin hide">
            <a herf="#" class="modal-close"></a>
            <div>
            <h1 id='modal_text'>モーダル<a id="modal_cancel">×</a></h1>
            </div>
            <div class="modalwin-contents" align='center'>
                <span id='modal_mode'>
                    <textarea id="win_note" readonly disabled></textarea>
                </span>
            </div>
        </div>
		<div class='screen'>
			@include('menu_list')
			<div class="main">
				<div class="item header">
					<header>勤怠登録</header>
				</div>

				<div class="item date">
					<span id="date_display"></span>
				</div>
				<!-- date -->

				<div class="item time">
					<span id="time_display"></span>
				</div>
				<!-- time -->

				<div class="item info">
					<div class="info_display">
						@foreach($general_messages as $general_message)
							<span class="cp_hr01">【<?php echo date("Y年m月d日", strtotime($general_message->start_date)); ?>】</span>
							<p><?php echo $general_message->message; ?></p>
							<hr class="cp_hr02">
						@endforeach
					</div>
				</div>
				<div class='item info_btn'>
					<button type='button' id='info_btn' class='btn'>✉</button>
				</div>
				<!-- info -->

				<div class="item attendance_btn"><button id="attendance_btn" class="btn" data-work='1' disabled='disabled'>出勤</button></div>
				<div class="item symptons_select">
					<select id='condition'>
						<option value='0'>元気</option>
						<option value='1'>病気</option>
					</select>
				</div>
				<div class="item break_btn"><button id="break_btn" class="btn" data-rest='3' disabled='disabled'>休憩開始</button></div>
				<div class="item shift">
					<span class="shift_display">
						<table class="shift_table" border="1">
							<thead>
								<tr align="center">
									<th class="name">名前</th>
									<th class="symptons">体調</th>
									@for($hour=0; $hour<=24; $hour++)
										<th class="time_table">{{ $hour }}</th>
									@endfor
								</tr>
							</thead>

							<tbody>
								@foreach($shifts as $shift)
									<tr align="center" data-shift_id='{{ $shift->id }}'>
										<td class="name {{ $shift->attendance }}" id="user_{{ $shift->userId }}">{{ $shift->name }}</td>
										@if ($shift->symptoms === 0)
											<td class="symptons">元気</td>
										@elseif ($shift->symptoms === 1)
											<td class="symptons" style='background-color:gray;'>病気</td>
										@else
											<td class="symptons"></td>
										@endif
										<?php
											$start = date("H", strtotime($shift->start));
											$end = ($shift->end !== "23:59:59") ? date("H", strtotime($shift->end)) : 24;
										?>
										@for($hour=0; $hour<=24; $hour++)
											<td class="time_table <?= ($start<=$hour && $hour<=$end) ? 'estimated': ''; ?>
												<?= ($hour == $start) ? "estimated_start": ""; ?>
												<?= ($hour == $end) ? "estimated_end": ""; ?>
											">
											</td>
										@endfor
									</tr>
								@endforeach
							</tbody>
						</table>
						<p align="center">【白：未出勤 　緑：出勤済み 　黄：遅刻 　赤：欠勤 　青：休憩 　灰：退勤済み】</p>
						<button type='button' id='modal'></button>
					</span>
				</div>
				<!-- shift -->
			</div>
			<!-- main -->
		</div>
		<!-- screen -->
		<script>
			$(document).ready(function() {
                $('#info_btn').prop('disabled', false);
				$.ajaxSetup({
        			headers: {
            			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        			}
				});

				//ログインユーザーの出勤状況を取得
				punch_check();

				//メッセージボタン
				$('#info_btn').on('click', function()
				{
					info();
				});

				//出退勤ボタン
				$('#attendance_btn').on('click', function()
				{
					//必要データ取得
					var work_text = $(this).text();
					var work = $(this).data('work');
					var condition = $('#condition').val();
					var shift_id = $('#user_{{Session::get('login_user_id')}}').closest('tr').data('shift_id');
					var message = work_text + 'してもよろしいですか？';

					if (work_text === '出勤') {
						var cond = (condition === '0')? '元気' : '病気';
						message = '体調は' + cond + 'で' + work_text + 'してもよろしいですか？';
					}

					//確認メッセージ
					if (!confirm(message)) {
						return false;
					}

					//二重送信防止
	                if ($(this).is('disabled')) {
	                	return false;
	                }
	                $(this).prop('disabled',true);

					$.ajax({
						url: "/main/punch",
						type: "get",
						data : {
	                        punch_type: work,
	                        condition: condition,
	                        shift_id: shift_id
	                    },
						dataType: 'json',
					}).done(function(data){
						//出勤
						if (work_text === '出勤') {
							//メッセージ表示
							alert('出勤が完了しました');
							//ボタン表記を変更する
							$('#attendance_btn').text('退勤');
							$('#attendance_btn').data('work', '2');
							//休憩ボタンを活性する
							$('#break_btn').prop('disabled',false);
							//体調セレクトボックス非活性
							$('#condition').prop('disabled',true);
							//出勤時に占いを行う
							fortune();
						}
						//退勤
						if (work_text === '退勤') {
							//メッセージ表示
							alert('退勤が完了しました');
							//ボタン表記を変更する
							$('#attendance_btn').text('出勤');
							$('#attendance_btn').data('work', '1');
							//休憩ボタンを非活性する
							$('#break_btn').prop('disabled',true);
							//休憩ボタンの表記
							$('#break_btn').text('休憩開始');
							$('#break_btn').data('rest', '3');
							//体調セレクトボックス活性
							$('#condition').prop('disabled',false);
							//次回のシフト表示
							getNextShift()
						}
						// 勤怠状況の色を再設定
						resetColor();
					}).fail(function () {
                        alert('勤務情報の登録に失敗しました');
                    });
					//二重送信解除
					$(this).prop('disabled',false);
				});

				//休憩ボタン
				$('#break_btn').on('click', function()
				{
					//必要データ取得
					var rest_text = $(this).text();
					var rest = $(this).data('rest');
					var condition = $('#condition').val();

					//確認メッセージ
					if (!confirm(rest_text + 'してもよろしいですか？')) {
						return false;
					}

					//二重送信防止
	                if ($(this).is('disabled')) {
	                	return false;
	                }
	                $(this).prop('disabled',true);


					$.ajax({
						url: "/main/punch",
						type: "get",
						data : {
	                        punch_type: rest,
	                    },
						dataType: 'json',
					}).done(function(data){

						//休憩開始
						if (rest_text === '休憩開始') {
							//メッセージ表示
							alert('休憩を開始しました');
							$('#break_btn').text('休憩終了');
							$('#break_btn').data('rest', '4');
							//出退勤ボタンを非活性する
							$('#attendance_btn').prop('disabled',true);
							//体調セレクトボックス非活性
							$('#condition').prop('disabled',true);
						}
						//休憩終了
						if (rest_text === '休憩終了') {
							//メッセージ表示
							alert('休憩を終了しました');
							$('#break_btn').text('休憩開始');
							$('#break_btn').data('rest', '3');
							//出退勤ボタンを非活性する
							$('#attendance_btn').prop('disabled',false);
							//体調セレクトボックス非活性
							$('#condition').prop('disabled',true);
						}
						// 勤怠状況の色を再設定
						resetColor();
					});
					//二重送信解除
					$(this).prop('disabled',false);
				});

				//モーダル表示
			    $('#modal').on('click', showModal);
			    //モーダルを閉じる
			    $(document).on('click','#modal_cancel', hideModal);

				// 日付・時間表示
				setInterval(displayDate, 1000);
                setInterval(displayTime, 1000);
			});

			function displayDate() {
			  const date = new Date();
			  $("#date_display").text(getDateText(date));
			}

			function displayTime() {
			  const date = new Date();
			  $("#time_display").text(getTimeText(date));
			}

			function getDateText(date) {
			  const y = date.getFullYear();
			  const m = date.getMonth() + 1;// 1月は0が返ってくる
			  const d = date.getDate();
			  const day = '日月火水木金土'.charAt(date.getDay());

			  return y + "年"　+ m + "月" + d + "日" + "(" + day +")";
			}

			function getTimeText(date) {
			  const h = toDoubleDigits(date.getHours());
			  const m = toDoubleDigits(date.getMinutes());
			  const s = toDoubleDigits(date.getSeconds());

			  return h + ":" + m + ":" + s;
			}

			function toDoubleDigits(num) {
			  num += "";
			  if (num.length === 1) {
				num = "0" + num;
			  }
			 return num;
			}

			/**
			 * 占い機能
			 */
			function fortune()
			{
				$.ajax({
					url: "fortune",
					type: "get",
					dataType: 'json',
				}).done(function(data){
					//HTML作成
					var box = '<p class="fortune_box">';
					var fortune = box+data['fortune']+'</p>'
						+'<p>'+box+data['message'].replace(/〇/g, '</p>'+box)
						+'<p class="thank">本日もよろしくお願いします。</p>';
					//モーダルの中身を変更
					$('#modal_text').html('本日の占い<a id="modal_cancel">×</a>');
					$('#modal_mode').empty();
					$('#modal_mode').append(fortune);
					$('#modal').trigger('click', showModal);
				});
			}

			/**
			 * 個人メッセージ機能
			 */
			function info()
			{
                $('#info_btn').prop('disabled', true);
				$.ajax({
					url: "/main/info",
					type: "get",
                    dataType: 'json',
                    timeout: 10000,
				}).done(function(data){
                    console.log(data);
                    $('#info_btn').prop('disabled', false);
					//表示できるメッセージがない場合
					if (data['message'] === '') {
						alert('表示できるメッセージはありません。');
						return false;
					}
					//HTML作成
					var info = '<p class="info_box">'+data['ymd']+'</p>'
						+'<p class="info_text">'+data['message']+'</p>';
					//モーダルの中身を変更
					$('#modal_text').html('メッセージ<a id="modal_cancel">×</a>');
					$('#modal_mode').empty();
					$('#modal_mode').append(info);
					$('#modal').trigger('click', showModal);
				}).fail(function(e) {
                    $('#info_btn').prop('disabled', false);
                    console.log(e);
                });
			}

			/**
			 * 次回のシフトを取得機能
			 */
			function getNextShift()
			{
				$.ajax({
					url: "/main/nextShift",
					type: "get",
					dataType: 'json',
				}).done(function(data){
					//HTML作成
					var box = '<p class="shift_box">';
					var shift = box+data["day"]+'('+data["week"]+')</p><p>'+box
						+data["start"]+'　～　'+data["end"]+'</span>'+'</p>'
						+'<p class="thank">本日もお疲れ様でした。次回もよろしくお願いします。</p>';
					//モーダルの中身を変更
					$('#modal_text').html('次回のシフト<a id="modal_cancel">×</a>');
					$('#modal_mode').empty();
					$('#modal_mode').append(shift);
					$('#modal').trigger('click', showModal);
				});
			}

			/**
			 * 勤務状況チェック機能
			 */
			function punch_check()
			{
				$.ajax({
					url: "/main/check",
					type: "get",
					dataType: 'json',
				}).done(function(data){
					//ボタンの表記
					$('#attendance_btn').text(data['work']);
					$('#attendance_btn').data('work', data['work_type']);
					$('#break_btn').text(data['rest']);
					$('#break_btn').data('rest', data['rest_type']);

					//出退勤ボタンを活性・非活性
					var bool = (data['rest_type'] === '4')? true : false
					$('#attendance_btn').prop('disabled', bool);

					//休憩ボタンを活性・非活性
					var bool = (data['work_type'] === '1')? true : false
                    $('#break_btn').prop('disabled', bool);

                    $('#attendance_btn').prop('disabled', false);
				}).fail(function(e) {
                    alert('通信状況を確認してください');
                });
			}

			/**
			 * 勤務状況色変更
			 */
			function resetColor()
			{
				$.ajax({
					url: "/main/reset",
					type: "post",
					dataType: 'json',
				}).done(function(data){
					const userId = data.user_id;
					//一旦クラスを全削除して再設定
					$("#user_" + userId).removeClass();
					$("#user_" + userId).addClass("name " + data.status);
					//体調リロード
					if (data.condition === 0) {
						$("#user_" + userId).next().text('元気');
						$("#user_" + userId).next().css('background-color', '');
					} else if (data.condition === 1) {
						$("#user_" + userId).next().text('病気');
						$("#user_" + userId).next().css('background-color', 'gray');
					}
				});
			}

			/**
			 * モーダルモーダルウィンドウを開く
			 * @param object obj
			 */
			function showModal(obj)
			{
			    $('html').css('overflow','hidden');
			    obj.preventDefault();
			    //モーダルのサイズを設定する
			    var $shade = $('<div></div>');
			    $shade.attr('id', 'shade');
			    var $main_modalWin = $('#main_modalwin');
			    var $window = $(window);
			    var posX = ($window.width() - $main_modalWin.outerWidth()) / 2;
			    //表示するモーダルの内容をセットする
			    $main_modalWin
			        .before($shade)
			        .css({left: posX, top: 220})
			        .removeClass('hide')
			        .addClass('show');
			}

			/**
			 * モーダルモーダルを閉じる
			 */
			function hideModal()
			{
			    $('html').css('overflow','auto');
			    $('#shade').remove();
			    $('#main_modalwin').removeClass('show').addClass('hide');
			}

			/**
			 * 体調を取得する
			 */
			function getCondition()
			{
				var user_id = {{Session::get('login_user_id')}};
				var shift_id = $('#user_'+user_id).closest('tr').data('shift_id');
				$.ajax({
					url: "/main/condition",
					type: "get",
					data : {
                        user_id: user_id,
                        shift_id: shift_id
                    },
					dataType: 'json',
				}).done(function(data){
					alert(data);
				});
			}
		</script>
	</body>
</html>
