<!doctype html>
<html lang='ja'>
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Ajaxテスト</title>
    <link rel="stylesheet" href="{{ asset('css/shift/test.css') }}">
    <script src="{{ asset('js/jquery-3.4.1.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/shift/test.js') }}"></script>
</head>
<body>
<div class='screen'>
    @include('menu_list')
    <div class='main'>
        <header>Ajaxテスト</header>
        <div class="ActionList">
            <label>シフト読み込み</label>
            <div class="ActionBtn">
                <input type="button" id="loadAdmin" value="管理者用" onclick="testLoadAdmin()">
                <input type="button" id="loadUser" value="非管理者用" onclick="testLoadUser()">
                <input type="button" id="loadCalendar" value="月カレンダー用" onclick="testLoadCalendar()">
            </div>
        </div>

        <div class="ActionList">
            <label>シフト希望操作</label>
            <div class="ActionBtn">
                <input type="button" id="createHope" value="作成" onclick="testCreateHopeShift()">
                <input type="button" id="updateHope" value="編集" onclick="testUpdateHopeShift()">
                <input type="button" id="deleteHope" value="削除" onclick="testDeleteHopeShift()">
            </div>
        </div>

        <div class="ActionList">
            <label>予定シフト操作</label>
            <div class="ActionBtn">
                <input type="button" id="createShift" value="作成" onclick="testCreateShift()">
                <input type="button" id="updateShift" value="編集" onclick="testUpdateShift()">
                <input type="button" id="deleteShift" value="削除" onclick="testDeleteShift()">
            </div>
        </div>

        <div class="ActionList">
            <label>稼働実績操作</label>
            <div class="ActionBtn">
                <input type="button" id="createAttendanceShifts" value="作成(勤務、休憩複合)" onclick="testCreateAttendanceStatuses()">
                <input type="button" id="createAttendanceShift" value="作成(個別)" onclick="testCreateAttendanceStatus()">
                <input type="button" id="updateAttendanceShift" value="編集" onclick="testUpdateAttendanceStatus()">
                <input type="button" id="deleteAttendanceShift" value="削除" onclick="testDeleteAttendanceStatus()">
            </div>
        </div>
    </div>
</div>
</body>
</html>
