<!doctype html>
<html lang='ja'>
	<head>
		<meta charset="UTF-8">
		<title>login</title>
		<link rel="stylesheet" href="{{ asset('css/punch_list.css') }}">
		<script src="{{ asset('js/jquery-3.4.1.min.js') }}"></script>
	</head>
	<body>
		<input type='hidden' id='set_id' value='{{ $set_id }}'>
		<input type='hidden' id='year' value='{{ $year }}'>
		<input type='hidden' id='month' value='{{ $month }}'>
		<div class='screen'>
		    @include('menu_list')
		    <div class='main'>
		    	<header>打刻一覧画面</header>
				<form id="punch_form" method="POST" action="punch_list">
					<!-- CSRF保護 -->
					@csrf
					<div class='height_10'>
						<div class='search'>
						<button class="monthdown" type='button'><</button>
							<span class='month' name='month'></span>
							<input class='getmonth' type="hidden" name="month">
							<button class="monthup" type='button'>>
						</button>
							@if (Session::get('login_user_admin') === 1)
								<label class='margin-l_30'>対象ユーザー名：</label>
								<select class='select' name='user'>
									@foreach ($userList as $key => $list)
										<option class="option" value={{$list->id}}>{{$list->name}}</option>
									@endforeach
								</select>
								<button type='button' id='serch'>検索</button>
							@else
    							<span class='margin-l_30'>対象ユーザー名：{{ Session::get('login_user_name') }}</span>
								<input type='hidden' name='user' value={{$userList->id}}>
							@endif
						</div>
					</div>
			    	<table align='center'>
			    		<thead>
			    			<tr class='thead'>
				    			<th class='width_7'>日付</th>
				    			<th class='width_12'>開始時間</th>
				    			<th class='width_12'>終了時間</th>
				    			<th class='width_12'>休憩開始</th>
				    			<th class='width_12'>休憩終了</th>
				    			<th class='width_12'>勤務時間</th>
				    			<th class='width_12'>時間外</th>
				    		</tr>
			    		</thead>
			    		<tbody>
							@foreach ($result as $data)
							    <tr>
									<td class='width_7'>{{ $data["created_at"] }}</td>
									@if (!empty($data["start_date"]))
										<td class='width_12'>{{ $data["start_date"] }}</td>
									@else
										<td class='width_12'>-</td>
									@endif
									@if (!empty($data["end_date"]))
										<td class='width_12'>{{ $data["end_date"] }}</td>
									@else
										<td class='width_12'>-</td>
									@endif
									@if (!empty($data["break_start_date"]))
										<td class='width_12'>{{ $data["break_start_date"] }}</td>
									@else
										<td class='width_12'>-</td>
									@endif
									@if (!empty($data["break_end_date"]))
										<td class='width_12'>{{ $data["break_end_date"] }}</td>
									@else
										<td class='width_12'>-</td>
									@endif
									@if (!empty($data["time_diff"]))
										<td class='width_12'>{{ $data["time_diff"] }}</td>
									@else
										<td class='width_12'>-</td>
									@endif
									@if (!empty($data["over_time_diff"]))
										<td class='width_12'>{{ $data["over_time_diff"] }}</td>
									@else
										<td class='width_12'>-</td>
									@endif
								</tr>
							@endforeach
			    		</tbody>
			    	</table>
				</form>
		    </div>
		</div>
		<script>
			$(document).ready(function() {

			});
		</script>
		<script src="{{ asset('js/attendanceStatus.js') }}"></script>
	</body>
</html>
