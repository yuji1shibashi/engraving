<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


/**
 * ログイン認証チェック機能
 *
 * ログイン未済の場合：ログインページ
 * ログイン済の場合　：メインページ
 */
Route::get('/', 'LoginController@auth');
Route::get('/login', 'LoginController@auth');
Route::get('/login/check', 'LoginController@auth');
Route::get('/main', 'LoginController@auth');

/**
 * ログイン認証機能
 */
Route::post('/login/check', 'LoginController@check');

/**
 * ログインアウト機能
 */
Route::get('/logout', 'LoginController@logout');

// 登録ユーザー一覧
Route::get('/user/index', 'UserController@index');
Route::post('/user/index', 'UserController@index');
// ユーザー登録
Route::get('/user/register', 'UserController@register');
Route::post('/user/regist', 'UserController@regist');
// ユーザー更新
Route::get('/user/edit/{id}', 'UserController@edit');
Route::post('/user/update/{id}', 'UserController@update');

// ユーザー削除
Route::get('/user/delete/{id}', 'UserController@delete');

/**
 * 占い結果取得機能
 */
Route::get('/fortune', 'FortuneController@index');

/**
 * 当日シフト表示機能
 */
Route::get('/main', 'TodaysShiftController@list');
/**
 * 勤怠状況色再設定
 */
Route::post('/main/reset', 'TodaysShiftController@reset');
/**
 * 勤怠状況体調
 */
Route::get('/main/condition', 'TodaysShiftController@getCondition');
/**
 * 次回のシフト取得
 */
Route::get('/main/nextShift', 'TodaysShiftController@nextShift');
/**
 * 個人メッセージ取得
 */
Route::get('/main/info', 'TodaysShiftController@personalInfo');

/**
 * 目標一覧表示機能
 */
Route::get('/target', 'TargetController@list');
Route::get('/target/create', 'TargetController@list');
Route::get('/target/update', 'TargetController@list');
Route::get('/target/delete', 'TargetController@list');

/**
 * 目標登録機能機能
 */
Route::post('/target/create', 'TargetController@create');

/**
 * 目標編集機能機能
 */
Route::post('/target/update', 'TargetController@update');

/**
 * 目標削除機能
 */
Route::post('/target/delete', 'TargetController@delete');


/**
 * インフォーメーション画面表示
 */
Route::middleware('admin')->prefix('information')->group(function(){

    Route::get('', "InformationController@index");
    Route::post('/create', "InformationController@addInformation");
    Route::post('/update', 'InformationController@editInformation');
    Route::post('/delete', 'InformationController@delInformation');
});

/**
 * 打刻一覧画面遷移機能
 */
Route::get('punch_list/{user_id}/{year}/{month}', 'PunchListController@list');

/**
 * 業務状況表示機能
 */
Route::get('/salary/{user_id}/{year}/{month}', 'SalaryController@list');

/**
 * 出退勤、休憩状況取得機能
 */
Route::get('/main/check', 'PunchController@check');

/**
 * 出退勤、休憩登録機能
 */
Route::get('/main/punch', 'PunchController@create');

// シフト管理
Route::middleware('login')->prefix('shift')->group(function () {

    // 管理者
    Route::middleware('admin')->prefix('admin')->group(function () {

        // 月シフト一覧表示
        Route::get('/month/{year?}-{month?}', 'ShiftController@indexAdminShiftMonth')
            ->where(['year' => '^\d{4}', 'month' => '^\d{2}',])
            ->name('adminShiftMonth');

        // 日シフト一覧表示
        Route::get('/{year?}-{month?}-{day?}', 'ShiftController@indexAdminShiftDay')
            ->where(['year' => '^\d{4}', 'month' => '^\d{2}', 'day' => '^\d{2}', ])
            ->name('adminShiftDay');

        Route::get('/month/{year?}-{month?}/getcsv', 'ShiftController@getShiftMonthCsv')
            ->where(['year' => '^\d{4}', 'month' => '^\d{2}',])
            ->name('shiftMonthCsv');

        // 1日の出勤者情報取得
        Route::post('/load', 'ShiftController@loadAdmin');

        // 予定シフト
        Route::prefix('plan')->group(function () {
            Route::post('/create', 'ShiftController@create');
            Route::put('/update', 'ShiftController@update');
            Route::delete('/delete', 'ShiftController@delete');
        });

        // 稼働実績
        Route::prefix('actual')->group(function () {
            Route::post('/creates', 'AttendanceStatusController@creates');
            Route::post('/create', 'AttendanceStatusController@create');
            Route::put('/update', 'AttendanceStatusController@update');
            Route::delete('/delete', 'AttendanceStatusController@delete');
        });
    });

    // 非管理者
    Route::prefix('user')->group(function () {

        // 月シフト一覧表示
        Route::get('/month/{year?}-{month?}', 'ShiftController@indexUserShiftMonth')
            ->where(['year' => '^\d{4}', 'month' => '^\d{2}',])
            ->name('userShiftMonth');;

        // 日シフト一覧表示
        Route::get('/{year?}-{month?}-{day?}', 'ShiftController@indexUserShiftDay')
            ->where(['year' => '^\d{4}', 'month' => '^\d{2}', 'day' => '^\d{2}', ])
            ->name('userShiftDay');

        // 一週間の出勤情報取得
        Route::post('/load', 'ShiftController@loadUser');

    });

    // 共通
    // 月カレンダー
    Route::post('/load/calendar', 'ShiftController@loadCalendar');

    // シフト希望
    Route::prefix('hope')->group(function () {
        Route::post('/create', 'HopeShiftController@create');
        Route::put('/update', 'HopeShiftController@update');
        Route::delete('/delete', 'HopeShiftController@delete');
    });
});
