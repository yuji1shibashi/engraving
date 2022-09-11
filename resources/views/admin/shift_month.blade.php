<!DOCTYPE html>
<html lang="ja">
<head>
    <link rel="stylesheet" href="{{ asset('css/shift_month.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="year" content="{{ $year }}">
    <meta name="month" content="{{ $month }}">
    <meta name="url" content="{{ url()->current() }}">

    <link href="{{ asset('css/fullcalendar/fullcalendar.css') }}" rel='stylesheet' />
    <link href="{{ asset('css/fullcalendar/fullcalendar.print.min.css') }}" rel='stylesheet'  media="print" />

    <script src="{{ asset('js/fullcalendar/moment.min.js') }}"></script>
    <script src="{{ asset('js/jquery-3.4.1.min.js') }}"></script>
    <script src="{{ asset('js/fullcalendar/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('js/fullcalendar/fullcalendar.min.js') }}"></script>
    <script src="{{ asset('js/fullcalendar/ja.js') }}"></script>

    <script src="{{ asset('js/shift/month.js') }}"></script>
    <style>
        /* #calendar {
            width: 100%;
            margin: 0 auto;
        } */
        /* 日曜日 */
        .fc-sun {
            color: #ff6666;
            background-color: #fff0f0;
        }

        /* 土曜日 */
        .fc-sat {
            color: #5757ff;
            background-color: #f0f0ff;
        }
        .fc-more{
            color: #83b1ed;
        }
    </style>

</head>

<body>
@include('menu_list')
<div id="main">
    <div id="day-box">
        <div class="inline-block">
            <a id="prev-day" href="/shift/admin/month/{{ date('Y-m' ,strtotime( $year.'-'.$month.' -1 month')) }}">
                <i class="day-arrow-icon icon-left-arrow"></i>
            </a>
        </div>
        <div id="day-display">{{ $year . '/' . $month }}</div>
        <div class="inline-block">
            <a id="next-day" href="/shift/admin/month/{{ date('Y-m' ,strtotime( $year.'-'.$month.' +1 month')) }}">
                <i class="day-arrow-icon icon-right-arrow"></i>
            </a>
        </div>
        <div id="add-new-member-box">
            <div id="add-new-member-inbox">
                <!-- <span class="custom-dropdown">
                <select id="member-select">
                </select>
                </span> -->
            </div>
            <div class="add-new-member-btn-box">
                <a id="add-user-btn" class="blue" href="/shift/admin/month/{{ date('Y-m' ,strtotime( $year.'-'.$month)) }}/getcsv">エクセル出力</a>
            </div>
        </div>
    </div>
    <div style="margin-right: 3vw;">
        <div id='calendar'></div>
    </div>
</div>
</body>
</html>
