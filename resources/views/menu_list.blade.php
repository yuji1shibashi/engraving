<link rel="stylesheet" href="{{ asset('css/menu.css') }}">
<div id='side_menu'>
	<div id='menu_feader'>
		<span id='menu_title'>打刻管理システム</span>
		<span id='login_user'>ログイン：{{ Session::get('login_user_name') }}</span>
	</div>
	<ul id='menu_ul'>
		<li class='menu'>
			<a href='/main'>勤怠登録</a>
        </li>
        @if (Session::get('login_user_admin') === 1)
            <li class='menu'>
                <a href='/shift/admin/month'>シフト一覧</a>
            </li>
        @else
            <li class='menu'>
                <a href='/shift/user/month'>シフト一覧</a>
            </li>
        @endif
		<li class='menu'>
			<a href="/punch_list/{{Session::get('login_user_id')}}/{{date('Y/m', time())}}">打刻一覧<a>
		</li>
		<li class='menu'>
			<a href="/salary/{{Session::get('login_user_id')}}/{{date('Y/m', time())}}">業務状況</a>
		</li>
		<li class='menu'>
			<a href='/target'>目標一覧</a>
		</li>
		@if (Session::get('login_user_admin') === 1)
			<li class='menu'>
				<a href='/information'>info登録</a>
			</li>
			<li class='menu'>
				<a href='/user/index'>ユーザー管理</a>
			</li>
		@endif
		<li class='menu'>
			<a href='/logout'>ログアウト</a>
		</li>
	</ul>
</div>
