<!DOCTYPE html>
<html lang="ja">
<head>
    <link rel="stylesheet" href="{{ asset('css/shift_day.css') }}">
    <script src="{{ asset('js/jquery-3.4.1.min.js') }}"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="url" content="{{ url()->current() }}">
    <meta name="year" content="{{ $year }}">
    <meta name="month" content="{{ $month }}">
    <meta name="day" content="{{ $day }}">
</head>

<body>
    @include('menu_list')
    <div id="main">
        <div id="day-box">
            <div class="inline-block">
                <!-- @TODO とりあえず動かすために無理矢理！ -->
                <a id="prev-day" href="/shift/user/{{ date('Y-m-d' ,strtotime( $year.'-'.$month.'-'.$day.' -1 day')) }}">
                    <i class="day-arrow-icon icon-left-arrow"></i>
                </a>
            </div>
        <div id="day-display">{{ $year . '/' . $month . '/' . $day }}</div>
            <div class="inline-block">
                <!-- @TODO とりあえず動かすために無理矢理！ -->
                <?php $date = $year.'-'.$month.'-'.$day; ?>
                <a id="next-day" href="/shift/user/{{ date('Y-m-d' ,strtotime( $year.'-'.$month.'-'.$day.' +1 day')) }}">
                    <i class="day-arrow-icon icon-right-arrow"></i>
                </a>
            </div>
        <div id="add-new-shift-box">
            <div class="add-new-shift-inbox">
                <span class="custom-dropdown">
                    <select id='date-select'>
                    </select>
                </span>
            </div>
        <div class="add-new-shift-inbox">
          <span class="custom-dropdown">
          <select id="start-time-select">
                <!-- @TODO どうにかする Controllerで生成しておく？ -->
                <?php
                    for ($i = 0; $i < 47; $i++) {
                        $optionTime = '';
                        if ($i === 0) {
                            $optionTime = '0:00';
                        }elseif ($i % 2 === 0) {
                            $optionTime = floor($i / 2) . ':00';
                        }else {
                            $optionTime = floor($i / 2) . ':30';
                        }
                        echo '<option>' . $optionTime . '</option>';
                    }
                ?>
            </select>
          </span>
          <span>~</span>
          <span class="custom-dropdown">
          <select id="end-time-select">
                <!-- @TODO どうにかする Controllerで生成しておく？-->
                <?php
                    for ($i = 0; $i < 47; $i++) {
                        $optionTime = '';
                        if ($i === 0) {
                            $optionTime = '0:00';
                        }elseif ($i % 2 === 0) {
                            $optionTime = floor($i / 2) . ':00';
                        }else {
                            $optionTime = floor($i / 2) . ':30';
                        }
                        echo '<option>' . $optionTime . '</option>';
                    }
                ?>
            </select>
          </span>
        </div>
        <div class="add-new-shift-btn-box">
          <a id="shift-add-btn" class="add-btn blue" href="#">シフト追加</a>
        </div>
      </div>
    </div>

    <div id="shift-schedule-display">
      <div id="shift-schedule-box">
        <input type="hidden" id="userId">
        <div id="shift-schedule-time-box">
          <ul>
            <!-- 仮php -->
            <!-- jsで作った方がいい？サイズ合わせるために -->
            <?php
              for ($i = 0; $i < 24; $i++) {
                echo "<li>";
                echo $i . ":00";
                echo "</li>";
                echo "<li style='color: rgba(128, 128, 128, 0.495); font-size: 11px;'>";
                echo "---";
                echo "</li>";
              }
            ?>
          </ul>
        </div>
        <div class="modal-box hide" id="shift-template">
            <input type="hidden" class="hidden-date" value="">
            <div class="shift-day-inbox date"></div>
            <div class="description">
                <span>確定 </span><span>予定</span><span> 希望</span>
            </div>
            <div class="modal-inbox work freeze" style="background: #67D5B5;"></div>
            <div class="modal-inbox plan freeze" style="background: #a4e6d3;"></div>
            <div class="modal-inbox hope" style="background: #d6f4eb;"></div>
        </div>
      </div>
    </div>
    <div id="footer-btns-box">
      <a id="back-page-btn" class="footer-btn gray" href="/shift/user/month/{{ $year.'-'.$month }}">シフト一覧に戻る</a>
      <a id="done-btn" class="footer-btn pink" href="/shift/user/{{ $year.'-'.$month.'-'.$day }}">ページを更新する</a>
    </div>
  </div>
  <script src="{{ asset('/js/shift/day.js') }}"></script>
</body>
</html>
