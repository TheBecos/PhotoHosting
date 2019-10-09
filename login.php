<?php

include "settings/config.php";
include "settings/dbconnector.php";

error_reporting(E_ERROR);

$result = '';
$message = '';


?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <title>Фотогалерея</title>

    <meta content="text/html; charset=utf-8" http-equiv="content-type">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="user-scalable=no, initial-scale=1.0, maximum-scale=1.0"/>
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <meta name="apple-mobile-web-app-status-bar-style" content="default">

    <!--Sweet Alert-->
    <link rel="stylesheet" type="text/css" href="./css/sweet-alert.css">
    <script type="text/javascript" src="./js/sweet-alert.min.js"></script>

    <link rel="shortcut icon" href="./images/logo1.ico" type="image/x-icon">

    <script type="text/javascript" src="./js/jquery/jquery-3.1.1.min.js"></script>
    <script type="text/javascript" src="./js/jquery/jquery-migrate-3.0.0.min.js"></script>
    <script type="text/javascript" src="./js/jquery/jquery-ui.min.js?v=2018.9"></script>

    <script type="text/javascript" src="./js/jquery/jquery.meio.mask.min.js"></script>
    <script type="text/javascript" src="./js/jquery/jquery.autocomplete.js"></script>

    <script type="text/javascript" src="./js/jquery/jquery.actual.min.js"></script>
    <script type="text/javascript" src="./js/jquery.form/jquery.form.min.js"></script>

    <script type="text/javascript" src="./js/mustache/mustache.js"></script>
    <script type="text/javascript" src="./js/mustache/jquery.mustache.js"></script>
    <script type="text/javascript" src="./js/moment.min.js"></script>

    <!--App-->
    <link type="text/css" rel="stylesheet" href="./css/app.css">
    <link type="text/css" rel="stylesheet" href="./css/app.login.css">

    <link type="text/css" rel="stylesheet" href="./css/fontello.css">

    <script type="text/javascript" src="./js/app.core.js"></script>

</head>
<body>

<DIV class="login--container">

    <div class="login--block">

        <div class="logo"><img src="images/logo.png" height="40"></div>

        <div id="loginBlock" class="login--form">

            <form action="backend/core/user.php" method="post" id="loginform" name="loginform"
                  enctype="multipart/form-data">
                <input type="hidden" id="action" name="action" value="login">

                <div class="div-center blue mb20">
                    <h2 class="gray2">Авторизация</h2>
                </div>

                <div class="flex-container p10">

                    <div class="flex-string wp100 relative div-center material">
                        <input name="login" type="text" id="login" placeholder=" " width="100%" autocomplete="on"
                               value="" data-id="login">
                        <label for="login">Логин / Email</label>
                    </div>

                    <div class="flex-string wp100 relative mt10 material">
                        <input name="password" type="password" id="password" placeholder=" " width="100%" value=""
                               data-id="password">
                        <label for="password">Пароль</label>
                        <div class="showpass">
                            <i class="icon-eye-off hand" title="Показать" id="showpass"></i>
                        </div>
                    </div>

                </div>

                <div class="result warning div-center mt5 hidden" data-result="error">
                    <i class="icon-attention icon-2x red"></i>&nbsp;Неверный Логин / Пароль
                </div>
                <div class="result success div-center mt5 hidden" data-result="login">
                    <i class="icon-ok icon-2x green"></i>&nbsp;Вы авторизованы. Приложение сейчас будет загружено
                </div>
                <div class="result attention div-center mt5 hidden" data-result="logout">
                    <i class="icon-ok-circled icon-2x green"></i>&nbsp;Вы вышли из аккаунта!
                </div>

                <div class="row margtop10 pt10">

                    <div class="column grid-5">

                        <div class="pt15">
                            <a href="register.php" class="blue" style="font-size: 12pt">
                                <i class="icon-doc-text"></i>Регистрация
                            </a>
                        </div>

                    </div>
                    <div class="column grid-5">
                        <a href="javascript:void(0)" onClick="$('#loginform').submit()" class="loginbutton">Войти</a>
                    </div>

                </div>

                <div class="hidden"><input name="smit" type="submit"></div>

            </form>

        </div>

    </div>

</DIV>
<script>

    var $hash = '';
    var $message = '<?=$message?>';

    $(document).ready(function () {

        locationHashChanged();

    });

    window.onhashchange = locationHashChanged;

    function locationHashChanged() {

        $hash = window.location.hash.substring(1);

        $('.result').addClass('hidden');

        $('#' + $hash + 'Block').removeClass('hidden');

        if ($hash === 'logout') {

            $('.result[data-result="logout"]').removeClass('hidden');
            $('#loginBlock').removeClass('hidden');

        } else
            $('#loginBlock').find('input[data-id="login"]').focus();

    }

    /*
      * Обработчик формы
    */
    $('#loginform').ajaxForm({
        dataType: 'json',
        beforeSubmit: function () {

            $('.result').addClass('hidden');

            var e = 0;

            if ($('#login').val() == '') {

                $('#login').addClass('bad');
                e++;

            } else
                $('#login').removeClass('bad');

            if ($('#password').val() == '') {

                $('#password').addClass('bad');
                e++;

            } else
                $('#password').removeClass('bad');

            if (e > 0)
                return false;
            else
                return true;

        },
        success: function (data) {

            if (data.result === 'Success') {

                $('#loginBlock').find('div[data-result="login"]').removeClass('hidden');

                setCookie('session', data.user, {expires: 1000000});
                setCookie('password', data.password, {expires: 1000000});

                setTimeout(function () {

                    document.location = '/index.php';

                }, 2000);


            } else if (data.result === 'Error') {

                $('#loginBlock').find('div[data-result="error"]').removeClass('hidden').html(data.message);

            }

        }
    });

</script>
</body>
</html>