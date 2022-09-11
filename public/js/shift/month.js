$(function() {
    // urlからuser,admin判別用regex
    const URL_REGEX = /user/;

    let year = $('meta[name="year"]').attr('content');
    let month = $('meta[name="month"]').attr('content');

    function isUserUrl() {

        if (URL_REGEX.test( $('meta[name="url"]').attr('content') )) {
            return true;
        }else {
            return false;
        }
    }

    // ===================================
    // Ajax送信

    $.ajaxSetup({
    headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    date = { date: year + '-' + month };

    $.ajax({
        method: 'post',
        url: '/shift/load/calendar',
        data: date,
        timeout: 60000,
        beforeSend: function () {
            console.log('============ Request ============');
            console.log(date);
        },
    })
    .then(function (res) {

        console.log('============ Success Response ============');
        console.log(res);

        let usersJson = [];

        for (var i = 0; i < res.days.length; i++) {

            if (res.days[i].users.length == 0) {
                continue;
            }

            var userLength = res.days[i].users[0].length    ;

            for (var j = 0; j < userLength; j++) {

                usersJson.push({
                    // userName
                    title: res.days[i].users[0][j].name,
                    start: res.days[i].date,
                });
            }
        }

        let shiftDayUrl;
        if (isUserUrl()) {

            shiftDayUrl = '/shift/user/';
        }else {
            shiftDayUrl = '/shift/admin/';
        }

        $('#calendar').fullCalendar({

            contentHeight: 500,
            defaultDate: year + '-' + month,
            defaultView: 'month',
            selectable: true,
            eventLimitText: '人',
            eventLimit: true, // イベント増えた時にリンクボタン表示
            events: usersJson,

            // 名前をクリック時day_shiftに遷移する
            eventClick: function(calEvent) {
            },
            dayClick: function(date) {
                location.href = shiftDayUrl + date.format();
            },
        });
    }, function (res) {

        console.log('============ Fail Response ============');
        console.log(res);
    });
});
