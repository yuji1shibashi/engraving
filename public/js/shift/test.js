$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

// ===================================
// 読み込み

// 管理者用シフト一覧
function testLoadAdmin() {

    let ajaxAction = {
        method: 'post',
        url: '/shift/admin/load',
        data: {
            date: '2019-10-25',
        },
    };

    sendAjax(ajaxAction);
}

// 非管理者用シフト一覧
function testLoadUser() {

    let ajaxAction = {
        method: 'post',
        url: '/shift/user/load',
        data: {
            date: '2019-10-25',
        },
    };

    sendAjax(ajaxAction);
}

function testLoadCalendar() {

    let ajaxAction = {
        method: 'post',
        url: '/shift/load/calendar',
        data: {
            date: '2019-10',
        },
    };
    sendAjax(ajaxAction);
}

// ===================================
// シフト希望

// 作成
function testCreateHopeShift() {

    let ajaxAction = {
        method: 'post',
        url: '/shift/hope/create',
        data: {
            userId: 3,
            startDate: '2019-11-01 10:30:00',
            endDate: '2019-11-01 16:00:00',
            memo: '',
        },
    };

    sendAjax(ajaxAction);

}

// 更新
function testUpdateHopeShift() {

    let ajaxAction = {
        method: 'put',
        url: '/shift/hope/update',
        data: {
            userId: 3,
            shiftId: 11,
            startDate: '2019-11-01 15:00:00',
            endDate: '2019-11-01 23:00:00',
            memo: '夜のみ可能です。',
        },
    };

    sendAjax(ajaxAction);

}

// 削除
function testDeleteHopeShift() {

    let ajaxAction = {
        method: 'delete',
        url: '/shift/hope/delete',
        data: {
            userId: 3,
            shiftId: 11,
        },
    };

    sendAjax(ajaxAction);
}

// ===================================
// 予定シフト

// 作成
function testCreateShift() {

    let ajaxAction = {
        method: 'post',
        url: '/shift/admin/plan/create',
        data: {
            userId: 3,
            startDate: '2019-11-01 10:30:00',
            endDate: '2019-11-01 16:00:00',
        },
    };

    sendAjax(ajaxAction);
}

// 更新
function testUpdateShift() {

    let ajaxAction = {
        method: 'put',
        url: '/shift/admin/plan/update',
        data: {
            userId: 3,
            shiftId: 11,
            startDate: '2019-11-01 17:00:00',
            endDate: '2019-11-01 23:00:00',
        },
    };

    sendAjax(ajaxAction);

}

// 削除
function testDeleteShift() {

    let ajaxAction = {
        method: 'delete',
        url: '/shift/admin/plan/delete',
        data: {
            userId: 3,
            shiftId: 11,
        },
    };

    sendAjax(ajaxAction);
}

// ===================================
// 稼働実績

// 作成 その1
function testCreateAttendanceStatuses() {

    let ajaxAction = {
        method: 'post',
        url: '/shift/admin/actual/creates',
        data: {
            userId: 3,
            work: {
                startDate: '2019-11-01 10:30:00',
                endDate: '2019-11-01 17:00:00',
            },
            rest: [
                {
                    startDate: '2019-11-01 14:30:00',
                    endDate: '2019-11-01 15:00:00',
                },
                {
                    startDate: '2019-11-01 16:00:00',
                    endDate: '2019-11-01 16:30:00',
                }
            ]
        },
    };

    sendAjax(ajaxAction);
}

// 作成 その2
function testCreateAttendanceStatus() {

    let ajaxAction = {
        method: 'post',
        url: '/shift/admin/actual/create',
        data: {
            // 作成種別 1:勤務時間 2:休憩時間
            type: 1,
            userId: 3,

            // 打刻を1回行う毎に1レコード作成される為、1塊の稼働実績を削除するのに複数IDが必要
            startDate: '2019-11-01 12:00:00',
            endDate: '2019-11-01 15:00:00',
        },
    };

    sendAjax(ajaxAction);
}


// 更新
function testUpdateAttendanceStatus() {

    let ajaxAction = {
        method: 'put',
        url: '/shift/admin/actual/update',
        data: {

            // 編集種別 1:勤務時間 2:休憩時間
            type: 1,
            userId: 3,

            // 打刻を1回行う毎に1レコード作成される為、1塊の稼働実績を削除するのに複数IDが必要
            start: {
                shiftId: 32,
                date: '2019-11-01 16:00:00'
            },
            end: {
                shiftId: 33,
                date: '2019-11-01 19:00:00'
            },
        }
    };

    sendAjax(ajaxAction);
}

// 削除
function testDeleteAttendanceStatus() {

    let ajaxAction = {
        method: 'delete',
        url: '/shift/admin/actual/delete',
        data: {

            // 編集種別 1:勤務時間 2:休憩時間
            type: 1,
            userId: 3,

            // 打刻を1回行う毎に1レコード作成される為、1塊の稼働実績を削除するのに複数IDが必要
            startShiftId: 32,
            endShiftId: 33,
        },
    };

    sendAjax(ajaxAction);
}

// ===================================
// Ajax送信

function sendAjax(ajaxAction) {

    $.ajax({
        method: ajaxAction.method,
        url: ajaxAction.url,
        data: ajaxAction.data,
        timeout: 60000,
        beforeSend: function () {
            console.log('============ Request ============');
            console.log(ajaxAction);
        },
    })
        .then(function (res) {

            console.log('============ Success Response ============');
            console.log(res);

        }, function (res) {

            console.log('============ Fail Response ============');
            console.log(res);
        });
}
