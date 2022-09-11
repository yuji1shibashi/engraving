<!doctype html>
<html lang='ja'>
    <head>
        <meta charset="UTF-8">
        <title>user_register</title>
        <link rel="stylesheet" href="{{ asset('css/user_register.css') }}">
        <script src="{{ asset('js/jquery-3.4.1.min.js') }}"></script>
    </head>
    <body>
        <script>
            $(document).ready(function() {
                //更新完了時は画面遷移
                var msg = '{{ session('msg') }}';
                if (msg) {
                    alert(msg);
                    location.href='/user/index';
                    return false;
                }
            });
        </script>
        @if(Session::has('msg'))
            @php
                exit;
            @endphp
        @endif
        <div class='screen'>
            @include('menu_list')
            <div class='main'>
                <header>ユーザー作成</header>
                <form id="user_form" method="POST" action="regist">
                    <!-- CSRF保護 -->
                    @csrf
                    @if($errors->any())
                    <div id="error">
                        @if($errors->has('name'))
                        <li class='msg'>{{ $errors->first('name') }}</li>
                        @endif

                        @if($errors->has('sex'))
                        <li class='msg'>{{ $errors->first('sex') }}</li>
                        @endif

                        @if($errors->has('year'))
                        <li class='msg'>{{ $errors->first('year') }}</li>
                        @endif

                        @if($errors->has('month'))
                        <li class='msg'>{{ $errors->first('month') }}</li>
                        @endif

                        @if($errors->has('day'))
                        <li class='msg'>{{ $errors->first('day') }}</li>
                        @endif

                        @if($errors->has('tel'))
                        <li class='msg'>{{ $errors->first('tel') }}</li>
                        @endif

                        @if($errors->has('address'))
                        <li class='msg'>{{ $errors->first('address') }}</li>
                        @endif

                        @if($errors->has('mail'))
                        <li class='msg'>{{ $errors->first('mail') }}</li>
                        @endif

                        @if($errors->has('lang'))
                        <li class='msg'>{{ $errors->first('lang') }}</li>
                        @endif

                        @if($errors->has('password'))
                        <li class='msg'>{{ $errors->first('password') }}</li>
                        @endif

                        @if($errors->has('admin'))
                        <li class='msg'>{{ $errors->first('admin') }}</li>
                        @endif
                    </div>
                    @endif
                    <table align="center">
                        <tr>
                            <th>名前</th>
                            <td>
                                <input type='text' name='name' class='form' value="{{old('name')}}">
                            </td>
                        </tr>
                        <tr>
                            <th>性別</th>
                            <td>
                                <label>男性</label><input type='radio' name='sex' value=0 @if(old('sex') === '0') checked @endif>
                                <label>女性</label><input type='radio' name='sex' value=1 @if(old('sex') === '1') checked @endif>
                            </td>
                        </tr>
                        <tr>
                            <th>生年月日</th>
                            <td>
                                <select name='year'>
                                    <option value=''>-</option>
                                    <?php for ($i = 1940; $i < 2031; $i++) : ?>
                                    <option value='<?=$i;?>' @if(old('year') == $i) selected @endif><?=$i;?></option>
                                    <?php endfor; ?>
                                </select>
                                年
                                <select name='month'>
                                    <option value=''>-</option>
                                    <?php for ($i = 1; $i <= 12; $i++) : ?>
                                    <option value='<?=$i;?>' @if(old('month') == $i) selected @endif><?=$i;?></option>
                                    <?php endfor; ?>
                                </select>
                                月
                                <select name='day'>
                                    <option value=''>-</option>
                                    <?php for ($i = 1; $i <= 31; $i++) : ?>
                                    <option value='<?=$i;?>' @if(old('day') == $i) selected @endif><?=$i;?></option>
                                    <?php endfor; ?>
                                </select>
                                日
                            </td>
                        </tr>
                        <tr>
                            <th>電話番号</th>
                            <td>
                                <input type='text' name='tel' class='form' value="{{old('tel')}}">
                            </td>
                        </tr>
                        <tr>
                            <th>住所</th>
                            <td>
                                <input type='text' name='address' class='form' value="{{old('address')}}">
                            </td>
                        </tr>
                        <tr>
                            <th>メールアドレス</th>
                            <td>
                                <input type='text' name='mail' class='form' value="{{old('mail')}}">
                            </td>
                        </tr>
                        <tr>
                            <th>言語</th>
                            <td>
                                <select name='lang'>
                                    <option value='japanese' @if(old('lang') === 'japanese') selected @endif>日本語</option>
                                    <option value='english' @if(old('lang') === 'english') selected @endif>英語</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th>パスワード</th>
                            <td>
                                <input type='password' name='password' class='form'>
                            </td>
                        </tr>
                        <tr>
                            <th>パスワード確認</th>
                            <td>
                                <input type='password' name='password_confirmation' class='form'>
                            </td>
                        <tr>
                            <th>管理者権限</th>
                            <td>
                                <label>一般</label><input type='radio' name='admin' value='0' @if(old('admin') === '0') checked @endif>
                                <label>管理者</label><input type='radio' name='admin' value='1' @if(old('admin') === '1') checked @endif>
                            </td>
                        </tr>
                        <tr class='tr_large'>
                            <th>備考</th>
                            <td>
                                <textarea name='memo' class='form'>{{ old('memo') }}</textarea>
                            </td>
                        </tr>
                    </table>
                    <div align='center' class='btns'>
                        <button type='button' id='back_btn'>戻る</button>
                        <button type='submit' id='register_btn'>作成/更新</button>
                    </div>
               </form>
            </div>
        </div>
        <script>
            $(document).ready(function() {

                // 戻るボタン押下時の挙動
                $('#back_btn').on('click', function() {
                    window.location.href = '/user/index';
                });

                // 作成/更新ボタン押下時の挙動
                $('#register_btn').on('click', function() {
                    var name = $('input[name=name]').val();
                    var sex  = $('input[name=sex]:checked').val();
                    var year = $('[name=year] option:selected').val();
                    var month = $('[name=month] option:selected').val();
                    var day   = $('[name=day] option:selected').val();
                    var tel   = $('input[name=tel]').val();
                    var address = $('input[name=address]').val();
                    var mail    = $('input[name=mail]').val();
                    var lang    = $('[name=lang] option:selected').val();
                    var admin   = $('input[name=admin]:checked').val();
                    var memo    = $('textarea[name=memo]').val() ;

                    if (lang == 'japanese') {
                        lang = '日本語';
                    } else {
                        lang = '英語';
                    }

                    if (sex == 0) {
                        sex = '男性';
                    } else {
                        sex = '女性';
                    }

                    if (admin == 0) {
                        admin = '一般';
                    } else {
                        admin = '管理者';
                    }
                    if(confirm("以下の内容で登録しますか？\n氏名: "+name+"\n性別: "+sex+"\n誕生日: "+year+"年"+month+"月"+day+"日\n住所: "+address+"\nメールアドレス: "+mail+"\n言語: "+lang+"\n管理者権限: "+admin+"\n備考: "+memo) == false) {
                        return false;
                    };
                });
            });
        </script>
    </body>
</html>

