<!doctype html>
<html lang='ja'>
	<head>
		<meta charset="UTF-8">
		<title>login</title>
		<link rel="stylesheet" href="{{ asset('css/login.css') }}">
		<script src="{{ asset('js/jquery-3.4.1.min.js') }}"></script>
	</head>
	<body>
		<header>ログイン画面</header>
		<form id="login_form" method="POST" action="/login/check">
			<!-- CSRF保護 -->
			@csrf
			<div id='main'>
				<p id='error_msg'>
					@if (!empty($text))
						{{ $text }}
					@endif
				</p>
				<div id='login_box'>
					<div class='login_eria top-10'>
						<label>従業員番号： <input type='text' name='employee_number' id='employee_number' class='text' value=''></label>
					</div>
					<div class='login_eria top-5'>
						<label>パスワード： <input type='password' name='password' id='password' class='text'></label>
					</div>
					<div class='login_eria top-5'>
						<input type='submit' name='login_btn' id='login_btn' value='ログイン'>
					</div>
				</div>
			</div>
		</form>
		<script>
			$(document).ready(function() {

				//ログインボタン押下時の処理
				$('#login_btn').on('click', function() {
					$('#login_form').submit();
				});
			});
		</script>
	</body>
</html>
