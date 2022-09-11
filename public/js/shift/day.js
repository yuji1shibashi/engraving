window.onload = function(){

    // 生成時のボックスの大きさ
    const MAKE_BOX_HEIGHT = 60;
    const MAKE_BOX_WIDTH = 28;
    // サイズ調整クリック範囲
    const CHANGE_SIZE_HEIGHT = 10;
    // 削除クリック範囲
    const DELETE_BOX_SIDE = 10;
    // ボックス最大最小サイズ制限
    const MAX_BOX_HEIGHT = 700;
    const MIN_BOX_HEIGHT = 15;
    // 30分のpx
    const THIRTY_MINITES_PX = 15;
    // urlからuser,admin判別用regex
    const URL_REGEX = /user/;
    // ボックス移動、変更時のエラー文言
    const NOT_ALLOW_ABOUT_BOX_MESSAGE = 'スケジュールを重ねることや24時を過ぎることはできません。';

    // 動かす対象
    let moveBoxTarget;
    let changeBoxSizeTarget;
    // ページ左上から選択したボックスの座標
    let onClickPageX;
    let onClickPageY;
    // 選択したボックスの縦の長さ
    let currentBoxHeight;
    // 生成エリア左上から選択したボックスの座標
    let clickTargetInAreaX;
    let clickTargetInAreaY;
    // ボックスを生成できるエリア
    let canMakeBoxArea;
    // シフト追加ボタン
    let addShiftBtn = document.getElementById("shift-add-btn");
    let addUserBtn = document.getElementById("add-user-btn");
    // ajax用
    let year = $('meta[name="year"]').attr('content');
    let month = $('meta[name="month"]').attr('content');
    let day = $('meta[name="day"]').attr('content');
    let url = $('meta[name="url"]').attr('content');
    let date = year + '-' + month + '-' + day;
    let rests =
    [
        // {
        //     startShiftId: 1,
        //     endShiftId: 2,
        //     startDate: '2019-10-25 00:00:00',
        //     endDate: '2019-10-25 01:00:00',
        // },
        // {
        //     startShiftId: 3,
        //     endShiftId: 4,
        //     startDate: '2019-10-25 10:00:00',
        //     endDate: '2019-10-25 12:00:00',
        // },
    ];

    if (addUserBtn) {
        addUserBtn.addEventListener('mousedown', function(){

            // プルダウンで選ばれたuserのスケジュールボックスを表示させる
            let select = document.getElementById('member-select');
            if (select.value == '') {
                return;
            }
            let shiftBox = document.getElementById('shift-schedule-box');
            // 隠しているスケジュールボックスを表示
            shiftBox.getElementsByClassName(select.value)[0].classList.remove('hide');
            // 選択されたoptionを削除
            select.getElementsByClassName(select.value)[0].remove();
        });
    }

    if (addShiftBtn) {
        // // Userでのシフト追加ボタン
        addShiftBtn.addEventListener('mousedown', function(){

            // プルダウンで選ばれたuserのスケジュールボックスを表示させる
            let dateSelect = document.getElementById('date-select');
            let startTimeSelect = document.getElementById('start-time-select');
            let endTimeSelect = document.getElementById('end-time-select');

            // ボックス生成先
            let targetBox = document.getElementsByClassName('date-' + dateSelect.value)[0];
            let targetInbox = targetBox.getElementsByClassName('hope')[0];

            // 生成時間分割
            var startTime = startTimeSelect.value.split(':');
            var endTime = endTimeSelect.value.split(':');

            // ボックス用に時間を変換
            var startMinutes = (startTime[0] * THIRTY_MINITES_PX * 2) + (parseInt(startTime[1] / 30 * THIRTY_MINITES_PX));
            var endMinutes = (endTime[0] * THIRTY_MINITES_PX * 2) + (parseInt(endTime[1] / 30 * THIRTY_MINITES_PX));
            var workMinutes = endMinutes - startMinutes;

            //
            if (workMinutes <= 0) {
                alert('正しい時間を選択してください。');
                return;
            }

            // ここで分を渡すと生成される
            var result = makeBox(startMinutes, workMinutes, targetInbox, false, {preShiftId: '',});

            // ボックスが表示された場合、指定の日付を表示させる
            if (result == true) {
                targetBox.classList.remove('hide');
            }
        });
    }

    // ボックスを生成できるエリアをクリック時動作
    window.addEventListener('mousedown', function(e){

        // 生成エリアない且つボックスではない場合ボックスを作成
        if (e.target.classList.contains("schedule-box")) {
            canMakeBoxArea = e.target.parentNode;
            checkMode(e);
        } else if(e.target.classList.contains("modal-inbox")) {

            // 30分単位で整形する
            targetStartTime = e.offsetY - (e.offsetY % MIN_BOX_HEIGHT);

            // ボックスが被らないかチェックする
            if (!checkRangeBoxTime(targetStartTime, parseInt(targetStartTime + MAKE_BOX_HEIGHT), e.target.children)) {
                alert(NOT_ALLOW_ABOUT_BOX_MESSAGE);
                return;
            }

            // 生成エリアによってボックスを作り分ける
            if (e.target.classList.contains('plan')) {

                makeBox(e.offsetY, MAKE_BOX_HEIGHT, e.target, false, { preShiftId: '', });
            }else if (e.target.classList.contains('work')) {

                var hiddens = {
                    preWorkStartId: '',
                    preWorkEndId: '',
                }
                makeBox(e.offsetY, MAKE_BOX_HEIGHT, e.target, false, hiddens);
            }else if (e.target.classList.contains('hope')) {

                makeBox(e.offsetY, MAKE_BOX_HEIGHT, e.target, false, { preShiftId: '', });
            }

        }
    });

    // マウスクリック終了時
    window.addEventListener("mouseup", function(e){
        let targetBox;

        if (moveBoxTarget != null) {
            targetBox = moveBoxTarget;
        }else if (changeBoxSizeTarget != null) {
            targetBox = changeBoxSizeTarget;
        }else {
            return;
        }

        // 30分単位で整形する
        targetStartTime = targetBox.offsetTop - (targetBox.offsetTop % MIN_BOX_HEIGHT);
        targetEndTime = targetStartTime + (targetBox.offsetHeight - (targetBox.offsetHeight % MIN_BOX_HEIGHT));

        // // ボックスが被らないかチェックする
        if (!checkRangeBoxTime(targetStartTime, targetEndTime, targetBox.parentNode.children)) {
            alert(NOT_ALLOW_ABOUT_BOX_MESSAGE);

            // 悔しいがとりあえず、本当はキャンセル時元に戻したい。
            location.reload();
            return;
        }

        if (targetBox.parentNode.classList.contains('plan')) {
            uppdatePlanShift(targetBox);
        }else if (targetBox.parentNode.classList.contains('work')){
            updateWorkAttendanceStatus(targetBox);
        }else if (targetBox.parentNode.classList.contains('hope')){
            uppdateHopeShift(targetBox);
        }

        // BOXサイズ変更、移動初期化
        moveBoxTarget = null;
        changeBoxSizeTarget = null;
    });

    // カーソル移動時
    window.addEventListener("mousemove", function(e){
        // ドラッグ移動時、BOX移動
        if (moveBoxTarget != null) {
            moveBox(e);
            return;
        }

        // ドラッグ移動時、BOX高さ変化
        if (changeBoxSizeTarget != null) {
            changeBoxSize(e);
            return;
        }

        // 動かせるボックスか判別する
        if (e.target.classList.contains('freeze-schedule-box')) {
            return;
        }

        // BOX上でのホバー時のカーソルアイコンの切り替え
        if (e.target.classList.contains("schedule-box")) {
            // @memo checkMode()と同じ処理があるので合体できる？
            currentBoxHeight = parseInt(e.target.style.height.replace('px', ''));
            if (MAKE_BOX_WIDTH - e.offsetX <= DELETE_BOX_SIDE && e.offsetY <= DELETE_BOX_SIDE) {
                e.target.style.cursor = 'pointer';
            }else if (currentBoxHeight - e.offsetY <= CHANGE_SIZE_HEIGHT) {
                e.target.style.cursor = 'ns-resize';
            }else {
                e.target.style.cursor = 'move';
            }
        }
    });

    // スケジュールボックスの長さを変化させる
    function changeBoxSize(e) {

        // @TODO 23:30を突き抜けないように
        let boxHeight = currentBoxHeight + e.pageY - onClickPageY;
        // @TODO 時々15の倍数にならない時があるので、15の倍数を再度確認するロジックを追加する
        boxHeight = boxHeight - (boxHeight % MIN_BOX_HEIGHT);

        if (boxHeight < MIN_BOX_HEIGHT) {

            boxHeight = MIN_BOX_HEIGHT;
        }else if(boxHeight > MAX_BOX_HEIGHT) {

            boxHeight = MAX_BOX_HEIGHT;
        }

        changeBoxSizeTarget.style.height = boxHeight + 'px';
    }

    // スケジュールボックスを移動させる
    function moveBox(e) {

        // @TODO areaから外れ場合に移動させない処理を追加する
        let height = (e.pageY - onClickPageY) + clickTargetInAreaY;

        if (height >= 0) {

            moveBoxTarget.style.top = height - (height % MIN_BOX_HEIGHT) + 'px';
        }else {

            moveBoxTarget.style.top = "0px";
        }
    }

    // @TODO ボックスが被って生成されないようにする
    // スケジュールboxを作成する
    function makeBox(startPosY, makeBoxHeight, targetBox, isInit, hiddens = [], isFreezeBox = false) {

        if (makeBoxHeight < 0) {
            return false;
        }

        if (isInit == false && targetBox.classList.contains('freeze')) {
            return false;
        }

        let box = document.createElement('div');

        // ボックス位置調整
        box.style.width = MAKE_BOX_WIDTH + 'px';
        box.style.left = '0px';

        // 生成高さがMIN_BOX_HEIGHT以下か判別
        if (makeBoxHeight <= MIN_BOX_HEIGHT) {

            // 高さをMIN_BOX_HEIGHTにする
            box.style.height = MIN_BOX_HEIGHT + 'px';
        }else {

            // 指定された生成高さにする
            box.style.height = makeBoxHeight + 'px';
        }

        // 開始時間が30分単位でboxが生成されるように調整
        startPosY = startPosY - (startPosY % MIN_BOX_HEIGHT);
        box.style.top = startPosY + 'px';

        // 更新、削除用にhiddenを仕込む
        Object.keys(hiddens).forEach(function(key) {

            var inputHidden = document.createElement('input');

            inputHidden.type = 'hidden';
            inputHidden.value = hiddens[key];
            inputHidden.classList.add(key);

            box.appendChild(inputHidden);
        });

        // 操作できないボックスかを判定する
        if (targetBox.classList.contains('freeze') || isFreezeBox) {

            box.classList.add('freeze-schedule-box');
        }else {

            box.classList.add('normal-schedule-box');
        };

        box.classList.add('schedule-box');

        targetBox.appendChild(box);


        if (isInit == true) {
            return true;
        }

        if (targetBox.classList.contains('plan')) {

            createPlanShift(targetBox, startPosY, makeBoxHeight);
        }else if (targetBox.classList.contains('work')) {

            createAttendanceStatuse(targetBox, startPosY, makeBoxHeight);
        }else if (targetBox.classList.contains('hope')) {

            createHopeShift(targetBox, startPosY, makeBoxHeight);
        }

        return true;
    }

    function checkMode(e) {

        // 動かせるボックスか判別する
        if (e.target.classList.contains('freeze-schedule-box')) {
            return;
        }

        onClickPageX = e.pageX; // 左上からのx座標
        onClickPageY = e.pageY; // 左上からのy座標

        // 画面内での生成エリアの座標を取得
        let makeBoxClient = canMakeBoxArea.getBoundingClientRect();

        // 生成エリアからの選択したボックス座標を代入
        clickTargetInAreaX = onClickPageX - makeBoxClient.left;
        clickTargetInAreaY = onClickPageY - makeBoxClient.top;

        // クリックしたboxのマウス座標を加味する
        clickTargetInAreaX -= e.offsetX;
        clickTargetInAreaY -= e.offsetY;

        // クリックした場所によってモードを変える
        if (MAKE_BOX_WIDTH - e.offsetX <= DELETE_BOX_SIDE && e.offsetY <= DELETE_BOX_SIDE) {
            console.log("Mode: deleteBox");

            // @TODO サーバー側がこけた時どうするか、redirectする？
            if (e.target.parentNode.classList.contains('plan')) {

                deletePlanShift(e.target);
            }else if (e.target.parentNode.classList.contains('work')) {

                deleteWorkAttendanceStatus(e.target);
                deleteRestAttendanceStatus(e.target);
            }else if (e.target.parentNode.classList.contains('hope')) {

                deleteHopeShift(e.target);
            }

            // let result = deleteHopeShift(e.target);
            e.target.remove();

        }else if (currentBoxHeight - e.offsetY <= CHANGE_SIZE_HEIGHT) {
            console.log("Mode: chengeBoxHeight");
            changeBoxSizeTarget = e.target;
        }else {
            console.log("Mode: moveBoxPosition");
            moveBoxTarget = e.target;
        }
    }

    function calculateSchduleBox(startPosY, makeBoxHeight, targetBox = null) {

        // 勤務時間の計算 30掛けて分に直す
        let startTime = (startPosY / THIRTY_MINITES_PX) * 30;
        let workTime = (makeBoxHeight / THIRTY_MINITES_PX) * 30;
        let endTime = startTime + workTime;
        let startHour = Math.floor(startTime / 60);
        let workHour = Math.floor(workTime / 60);
        let endHour = Math.floor(endTime / 60);
        let startMinute = startTime % 60;
        let workMinute = workTime % 60;
        let endMinute = endTime % 60;

        // 2桁に0埋め
        startHour = ("00" + startHour).slice( -2 );
        workHour = ("00" + workHour).slice( -2 );
        endHour = ("00" + endHour).slice( -2 );
        startMinute = ("00" + startMinute).slice( -2 );
        workMinute = ("00" + workMinute).slice( -2 );
        endMinute = ("00" + endMinute).slice( -2 );

        // 日付終わりの0:00は23:59にする
        if (targetBox != null) {
            if (targetBox.offsetTop + targetBox.clientHeight >= (THIRTY_MINITES_PX * 48)) {
                endHour = '23';
                endMinute = '59';
            }
        }

        let result = {
            startDate: startHour + ':' + startMinute+ ':00',
            endDate: endHour + ':' + endMinute + ':00',
        }

        return result;
    }

    function openAdminJson(res) {
        console.dir(res);

        for (var i = 0; i < res.users.length; i++) {
            var userName = res.users[i].name;
            var userId = res.users[i].userId;

            // 新しくスケジュールボックスを生成する
            // シフトのテンプレートを取得する
            var template = document.getElementById("shift-template");
            // シフトのテンプレート複製する
            var newShiftBox = template.cloneNode(true);
            // テンプレート対象のidを削除
            newShiftBox.removeAttribute("id");
            // userIdを紐付ける
            newShiftBox.classList.add('userId-' + userId);
            // 名前を代入
            var nameBox = newShiftBox.getElementsByClassName('user-name')[0];
            nameBox.textContent = userName;
            // userIdHiddenを作成
            // @TODO input関連つどつど適当に作ってるので、あとでリファクタリングする
            let inputUserIdHidden = document.createElement('input');
            inputUserIdHidden.type = 'hidden';
            inputUserIdHidden.value = userId;
            inputUserIdHidden.classList.add('userId');
            // 紐付け
            document.getElementById('shift-schedule-box').appendChild(newShiftBox);
            newShiftBox.appendChild(inputUserIdHidden);

            console.log('userName :' + userName);
            var hopeArr = res.users[i].shift.hope;
            var workArr = res.users[i].shift.actual.work;
            var restArr = res.users[i].shift.actual.rest;
            var planArr = res.users[i].shift.plan;

            // とりあえず
            // メンバー追加用プルダウン作成
            if (hopeArr.length == 0
                && workArr.length == 0
                && planArr.length == 0
            ){
                let userOption = document.createElement('option');
                userOption.value = 'userId-' + userId;
                userOption.classList.add('userId-' + userId);
                userOption.textContent = userName;
                document.getElementById('member-select').appendChild(userOption);
            }

            // hope入れ込み
            for (var j = 0; j < hopeArr.length; j++) {

                var startTime = hopeArr[j].start.split(':');
                var endTime = hopeArr[j].end.split(':');

                var startMinutes = (startTime[0] * THIRTY_MINITES_PX * 2) + (parseInt(startTime[1] / 30 * THIRTY_MINITES_PX));
                var endMinutes = (endTime[0] * THIRTY_MINITES_PX * 2) + (parseInt(endTime[1] / 30 * THIRTY_MINITES_PX));
                var workMinutes = endMinutes - startMinutes;

                makeBox(startMinutes, workMinutes, newShiftBox.getElementsByClassName('hope')[0], true);
                newShiftBox.classList.remove('hide');
            }

            // work入れ込み
            for (var j = 0; j < workArr.length; j++) {

                var startTime = workArr[j].start.date.split(':');
                var endTime = workArr[j].end.date.split(':');

                // 出勤中か判別し、出勤中の場合ボックスは変更できないように
                var isFreezeBox = workArr[j].end.id < 0 ? true : false;

                var startMinutes = (startTime[0] * THIRTY_MINITES_PX * 2) + (parseInt(startTime[1] / 30 * THIRTY_MINITES_PX));
                var endMinutes = (endTime[0] * THIRTY_MINITES_PX * 2) + (parseInt(endTime[1] / 30 * THIRTY_MINITES_PX));
                var workMinutes = endMinutes - startMinutes;

                var restIds = '';

                for (var k = 0; k < restArr.length; k++) {
                    if (restArr[k].start > workArr[j].end.date) {

                        var restStartTime = restArr[k].start.date;
                        var restEndTime = restArr[k].end.date;

                        var addSet = {
                            startShiftId: restArr[k].start.id,
                            endShiftId: restArr[k].end.id,
                            startDate: date + ' ' + restStartTime,
                            endDate: date + ' ' + restEndTime,
                        }

                        restIds += rests.length + ',';
                        rests.push(addSet);
                    }
                }

                var ids = {
                    workStartId: workArr[j].start.id,
                    workEndId: workArr[j].end.id,
                    restIds: restIds,
                }

                makeBox(startMinutes, workMinutes, newShiftBox.getElementsByClassName('work')[0], true, ids, isFreezeBox);
                newShiftBox.classList.remove('hide');
            }

            // plan入れ込み
            for (var j = 0; j < planArr.length; j++) {

                var startTime = planArr[j].start.split(':');
                var endTime = planArr[j].end.split(':');

                var startMinutes = (startTime[0] * THIRTY_MINITES_PX * 2) + (parseInt(startTime[1] / 30 * THIRTY_MINITES_PX));
                var endMinutes = (endTime[0] * THIRTY_MINITES_PX * 2) + (parseInt(endTime[1] / 30 * THIRTY_MINITES_PX));
                var workMinutes = endMinutes - startMinutes;

                var ids = {
                    shiftId: planArr[j].id,
                }
                makeBox(startMinutes, workMinutes, newShiftBox.getElementsByClassName('plan')[0], true, ids);
                newShiftBox.classList.remove('hide');
            }
        }
    }


    function openUserJson(res) {
        console.dir(res);
        // とりあえず
        var year = $('meta[name="year"]').attr('content');
        var month = $('meta[name="month"]').attr('content');
        var day = $('meta[name="day"]').attr('content');

        // userIdを入れる
        document.getElementById('userId').value = res.userId;

        // スケジュールボックスを紐付ける先を取得
        let shiftScheduleBox = document.getElementById('shift-schedule-box');

        for (var i = 0; i < res.shift.length; i++){
            // console.dir(res.shift[i]);
            let date = res.shift[i].date;
            let userName = res.shift[i].name;
            let userId = res.shift[i].userId;

            // 新しくスケジュールボックスを生成する
            // シフトのテンプレートを取得する
            var template = document.getElementById("shift-template");
            // シフトのテンプレート複製する
            var newShiftBox = template.cloneNode(true);
            // テンプレート対象のidを削除
            newShiftBox.removeAttribute("id");
            // dateを紐付ける
            newShiftBox.classList.add('date-' + date);

            // 名前を代入
            var nameBox = newShiftBox.getElementsByClassName('date')[0];
            // @TODO 年は消したい
            nameBox.textContent = date;
            // 紐付け
            shiftScheduleBox.appendChild(newShiftBox);

            // hidden代入
            newShiftBox.getElementsByClassName('hidden-date')[0].value = date;

            var hopeArr = res.shift[i].hope;
            var workArr = res.shift[i].actual.work;
            var planArr = res.shift[i].plan;

            // シフト追加用プルダウン作成
            let dateOption = document.createElement('option');
            dateOption.value = date;
            dateOption.textContent = date;
            document.getElementById('date-select').appendChild(dateOption);

            // hope入れ込み
            for (var j = 0; j < hopeArr.length; j++) {

                var startTime = hopeArr[j].start.split(':');
                var endTime = hopeArr[j].end.split(':');
                var id = hopeArr[j].id;

                var startMinutes = (startTime[0] * THIRTY_MINITES_PX * 2) + (parseInt(startTime[1] / 30 * THIRTY_MINITES_PX));
                var endMinutes = (endTime[0] * THIRTY_MINITES_PX * 2) + (parseInt(endTime[1] / 30 * THIRTY_MINITES_PX));
                var workMinutes = endMinutes - startMinutes;

                var ids = {
                    shiftId: id,
                }

                makeBox(startMinutes, workMinutes, newShiftBox.getElementsByClassName('hope')[0], true, ids);
                newShiftBox.classList.remove('hide');
            }

            // work入れ込み
            for (var j = 0; j < workArr.length; j++) {

                var startTime = workArr[j].start.date.split(':');

                // 出勤中か判別する
                if (typeof workArr[j].end == "object") {
                    var endTime = workArr[j].end.date.split(':');
                }else {
                    // 出勤中の場合ボックスを生成しない
                    continue;
                }

                var startMinutes = (startTime[0] * THIRTY_MINITES_PX * 2) + (parseInt(startTime[1] / 30 * THIRTY_MINITES_PX));
                var endMinutes = (endTime[0] * THIRTY_MINITES_PX * 2) + (parseInt(endTime[1] / 30 * THIRTY_MINITES_PX));
                var workMinutes = endMinutes - startMinutes;

                makeBox(startMinutes, workMinutes, newShiftBox.getElementsByClassName('work')[0], true);
                newShiftBox.classList.remove('hide');
            }

            // plan入れ込み
            for (var j = 0; j < planArr.length; j++) {

                var startTime = planArr[j].start.split(':');
                var endTime = planArr[j].end.split(':');

                var startMinutes = (startTime[0] * THIRTY_MINITES_PX * 2) + (parseInt(startTime[1] / 30 * THIRTY_MINITES_PX));
                var endMinutes = (endTime[0] * THIRTY_MINITES_PX * 2) + (parseInt(endTime[1] / 30 * THIRTY_MINITES_PX));
                var workMinutes = endMinutes - startMinutes;

                makeBox(startMinutes, workMinutes, newShiftBox.getElementsByClassName('plan')[0], true);
                newShiftBox.classList.remove('hide');
            }
        }
    }

    function setShiftId(shiftId) {
        let hiddenShiftId = document.getElementsByClassName('preShiftId')[0];
        hiddenShiftId.classList.remove('preShiftId');
        hiddenShiftId.classList.add('shiftId');
        hiddenShiftId.value = shiftId;
    }

    function setWorkIds(res) {

        let hiddenWorkStartId = document.getElementsByClassName('preWorkStartId')[0];
        let hiddenWorkEndId = document.getElementsByClassName('preWorkEndId')[0];

        hiddenWorkStartId.classList.remove('preWorkStartId');
        hiddenWorkEndId.classList.remove('preWorkEndId');
        hiddenWorkStartId.classList.add('workStartId');
        hiddenWorkEndId.classList.add('workEndId');

        let startShiftId = res[0].date < res[1].date ? 0 : 1;

        if (startShiftId == 0) {
            hiddenWorkStartId.value = res[0].shiftId;
            hiddenWorkEndId.value = res[1].shiftId;
        }else {
            hiddenWorkStartId.value = res[1].shiftId;
            hiddenWorkEndId.value = res[0].shiftId;
        }

    }

    function isUserUrl() {

        if (URL_REGEX.test(url)) {
            return true;
        }else {
            return false;
        }
    }

    function checkRangeBoxTime(targetStartTime, targetEndTime, boxesArr) {

        console.log('新規で作成する開始時間' + targetStartTime);
        console.log('新規で作成する終了時間' + targetEndTime);

        if (targetEndTime > THIRTY_MINITES_PX * 48) {
            return false;
        }

        let sameTimeCount = 0;
        for (var i = 0; i < boxesArr.length; i++) {

            console.dir('開始時間' + boxesArr[i].offsetTop);
            console.dir('終了時間' + parseInt(boxesArr[i].offsetTop + boxesArr[i].clientHeight));

            let startTime = boxesArr[i].offsetTop;
            let endTime = parseInt(boxesArr[i].offsetTop + boxesArr[i].clientHeight);

            // 開始時間が対象ボックスに挟まれる形の場合
            if (startTime > targetStartTime && startTime < targetEndTime) {
                return false;
            }

            // 終了時間が対象ボックスに挟まれる形の場合
            if (endTime > targetStartTime && endTime < targetEndTime) {
                return false;
            }

            // 対象ボックスがすでにあるボックスにぴったりはまる
            // or 中に含まれる形の場合
            if (startTime <= targetStartTime && endTime >= targetEndTime) {
                sameTimeCount++;
                if (sameTimeCount >= 2) {
                    return false;
                }
            }


            // if (targetStartTime > endTime) {
            //     if (rangeBoxMinTime == undefined) {
            //         rangeBoxMinTime = endTime;
            //     }else {
            //         if (endTime > rangeBoxMinTime) {
            //             rangeBoxMinTime = endTime;
            //         }
            //     }
            // }

            // if (targetEndTime < startTime) {
            //     if (rangeBoxMaxTime == undefined) {
            //         rangeBoxMaxTime = startTime;
            //     }else {
            //         if (startTime < rangeBoxMaxTime) {
            //             rangeBoxMaxTime = startTime;
            //         }
            //     }
            // }
        }

        return true;
    }

    // ===================================
    // Ajax関連

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // シフト取得
    if (isUserUrl()) {
        readUserShift();
    }else {
        readAdminShift();
    }

    function readUserShift() {
        let ajaxAction = {
            method: 'post',
            url: '/shift/user/load',
            data: {
                date: date,
            },
        };
        sendAjax(ajaxAction, true);
    }

    function readAdminShift() {
        let ajaxAction = {
            method: 'post',
            url: '/shift/admin/load',
            data: {
                date: date,
            },
        };

        sendAjax(ajaxAction, true);
    }

    // Admin予定作成
    function createPlanShift(targetBox, startPosY, makeBoxHeight) {
        let result = calculateSchduleBox(startPosY, makeBoxHeight);

        let ajaxAction = {
            method: 'post',
            url: '/shift/admin/plan/create',
            data: {
                userId: targetBox.parentNode.getElementsByClassName('userId')[0].value,
                startDate: date + ' ' + result.startDate,
                endDate: date + ' ' + result.endDate,
            },
        };

        sendAjax(ajaxAction, false, 'plan');
    }

    // Admin稼働実績作成
    function createAttendanceStatuse(targetBox, startPosY, makeBoxHeight) {

        let workShift = calculateSchduleBox(startPosY, makeBoxHeight);
        let ajaxAction = {
            method: 'post',
            url: '/shift/admin/actual/create',
            data: {
                // 作成種別 1:勤務時間 2:休憩時間
                type: 1,
                userId: targetBox.parentNode.getElementsByClassName('userId')[0].value,

                // 打刻を1回行う毎に1レコード作成される為、1塊の稼働実績を削除するのに複数IDが必要
                startDate: date + ' ' + workShift.startDate,
                endDate: date + ' ' + workShift.endDate,
            },
        };

        sendAjax(ajaxAction, false, 'work');
    }

    // Userシフト希望作成
    function createHopeShift(targetBox, startPosY, makeBoxHeight) {

        let date = targetBox.parentNode.getElementsByClassName('hidden-date')[0].value;
        let result = calculateSchduleBox(startPosY, makeBoxHeight);

        data = {
            userId: document.getElementById('userId').value,
            startDate: date + ' ' + result.startDate,
            endDate: date + ' ' + result.endDate,
            memo: "",
        }

        let ajaxAction = {
            method: 'post',
            url: '/shift/hope/create',
            data: data,
        };

        sendAjax(ajaxAction, false, 'hope');

    }

    // Admin予定シフト更新
    function uppdatePlanShift(targetBox) {
        let result = calculateSchduleBox(targetBox.offsetTop, targetBox.clientHeight, targetBox);

        let ajaxAction = {
            method: 'put',
            url: '/shift/admin/plan/update',
            data: {
                userId: targetBox.parentNode.parentNode.getElementsByClassName('userId')[0].value,
                shiftId: targetBox.getElementsByClassName('shiftId')[0].value,
                startDate: date + ' ' + result.startDate,
                endDate: date + ' ' + result.endDate,
            },
        };

        sendAjax(ajaxAction);

    }

    // Admin稼働実績勤務時間更新
    function updateWorkAttendanceStatus(targetBox) {

        let result = calculateSchduleBox(targetBox.offsetTop, targetBox.clientHeight, targetBox);

        let ajaxAction = {
            method: 'put',
            url: '/shift/admin/actual/update',
            data: {

                // 編集種別 1:勤務時間 2:休憩時間
                type: 1,
                userId: targetBox.parentNode.parentNode.getElementsByClassName('userId')[0].value,

                // 打刻を1回行う毎に1レコード作成される為、1塊の稼働実績を削除するのに複数IDが必要
                start: {
                    shiftId: targetBox.getElementsByClassName('workStartId')[0].value,
                    date: date + ' ' + result.startDate,
                },
                end: {
                    shiftId: targetBox.getElementsByClassName('workEndId')[0].value,
                    date: date + ' ' + result.endDate,
                },
            }
        };

        sendAjax(ajaxAction);
    }

    // User希望シフト更新
    function uppdateHopeShift(targetBox) {
        let result = calculateSchduleBox(targetBox.offsetTop, targetBox.clientHeight);
        let date = targetBox.parentNode.parentNode.getElementsByClassName('hidden-date')[0].value;

        let data = {
            userId: document.getElementById('userId').value,
            shiftId: targetBox.getElementsByClassName('shiftId')[0].value,
            startDate: date + ' ' + result.startDate,
            endDate: date + ' ' + result.endDate,
            memo: '夜のみ可能です。',
        }

        let ajaxAction = {
            method: 'put',
            url: '/shift/hope/update',
            data: data,
        };

        sendAjax(ajaxAction);

    }

    // Admin予定シフト削除
    function deletePlanShift(targetBox) {

        let ajaxAction = {
            method: 'delete',
            url: '/shift/admin/plan/delete',
            data: {
                userId: targetBox.parentNode.parentNode.getElementsByClassName('userId')[0].value,
                shiftId: targetBox.getElementsByClassName('shiftId')[0].value,
            },
        };

        sendAjax(ajaxAction);
    }

    // Admin稼働実績勤務時間削除
    function deleteWorkAttendanceStatus(targetBox) {

        let ajaxAction = {
            method: 'delete',
            url: '/shift/admin/actual/delete',
            data: {

                // 編集種別 1:勤務時間 2:休憩時間
                type: 1,
                userId: targetBox.parentNode.parentNode.getElementsByClassName('userId')[0].value,

                // 打刻を1回行う毎に1レコード作成される為、1塊の稼働実績を削除するのに複数IDが必要
                startShiftId: targetBox.getElementsByClassName('workStartId')[0].value,
                endShiftId: targetBox.getElementsByClassName('workEndId')[0].value,
            },
        };

        sendAjax(ajaxAction);
    }

    // Admin稼働実績休憩時間削除
    function deleteRestAttendanceStatus(targetBox) {

        console.dir(targetBox);

        if (targetBox.getElementsByClassName('restIds')[0] == undefined) {
            return;
        }

        // 休憩まとまりのidを取得する
        let restArr = targetBox.getElementsByClassName('restIds')[0].value.split(',');

        let userId = targetBox.parentNode.parentNode.getElementsByClassName('userId')[0].value;

        // 休憩まとまりのidを元にid分休憩時間を削除する
        for (var i = 0; i < restArr.length - 1; i++) {

            let ajaxAction = {
                method: 'delete',
                url: '/shift/admin/actual/delete',
                data: {

                    // 編集種別 1:勤務時間 2:休憩時間
                    type: 2,
                    userId: userId,

                    // 打刻を1回行う毎に1レコード作成される為、1塊の稼働実績を削除するのに複数IDが必要
                    startShiftId: rests[i].startShiftId,
                    endShiftId: rests[i].endShiftId,
                },
            };

            sendAjax(ajaxAction);
        }

    }

    // User削除
    function deleteHopeShift(targetBox) {

        let data = {
            userId: document.getElementById('userId').value,
            shiftId: targetBox.getElementsByClassName('shiftId')[0].value,
        }

        let ajaxAction = {
            method: 'delete',
            url: '/shift/hope/delete',
            data: data,
        };

        return sendAjax(ajaxAction);
    }

    // ===================================
    // Ajax送信
    function sendAjax(ajaxAction, isInit = false, createShiftType = '') {

        $.ajax({
            method: ajaxAction.method,
            url: ajaxAction.url,
            data: ajaxAction.data,
            timeout: 60000,
            beforeSend: function(){
                console.log('============ Request ============');
                console.log(ajaxAction);
            },
        })
        .then(function (res) {

            console.log('============ Success Response ============');

            if (isInit) {
                if (isUserUrl()) {
                    openUserJson(res);
                }else {
                    openAdminJson(res);
                }
            }

            if (createShiftType == 'plan' || createShiftType == 'hope') {
                setShiftId(res.shiftId);
            }else if (createShiftType == 'work') {
                setWorkIds(res);
            }

            return true;

        }, function (res) {

            console.log('============ Fail Response ============');
            console.log(res);

            // @TODO エラーメッセージ出して、リダイレクト処理をする？
            alert('通信エラーが発生しました。再更新します');
            location.reload();

        });
    }

}
