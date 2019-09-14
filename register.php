<?php

include "settings/config.php";
include "settings/dbconnector.php";
//include "settings/settings.php";
include "settings/func.php";


require_once "settings/Control.php";
?>
<!DOCTYPE html>
<html lang="ru">
<head>

    <title>Фотогалерея.Регистрация</title>

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

    <script type="text/javascript" src="./js/jquery.form/jquery.form.min.js"></script>
    <script type="text/javascript" src="./js/jquery/jquery.meio.mask.min.js"></script>
    <script type="text/javascript" src="./js/jquery/jquery.actual.min.js"></script>

    <script type="text/javascript" src="./js/mustache/mustache.js"></script>
    <script type="text/javascript" src="./js/mustache/jquery.mustache.js"></script>
    <script type="text/javascript" src="./js/moment.min.js"></script>

    <!--App-->
    <link type="text/css" rel="stylesheet" href="./css/app.css">
    <link type="text/css" rel="stylesheet" href="./css/app.login.css">

    <link type="text/css" rel="stylesheet" href="./css/fontello.css">

    <script type="text/javascript" src="./js/app.core.js"></script>

    <style>
        html {
            height: auto;
            min-height: 100vh;
            overflow: auto;
        }

        .login--container {
            width: 100vw;
            height: 100vh;
            min-height: 100vh;
            padding-bottom: 30px;
        }

        .login--block input {
            text-align: left;
        }

        #result {
            display: inline-block;
        }
    </style>

</head>
<body>

<DIV class="register login--container">

    <div class="login--block">

        <div class="logo"><img src="images/logo.png" height="50"></div>

        <form action="backend/core/core.user.php" method="post" id="regform" name="regform"
              enctype="multipart/form-data">
            <input type="hidden" id="action" name="action" value="user.edit">

            <a href="login.php" class="pt5" style="font-size: 11pt">
                <i class="icon-left"></i>Авторизация
            </a>

            <div class="div-center blue mb20">
                <h2 class="blue">Регистрация</h2>
            </div>

            <div class="flex-container p10" id="anketa">

                <div class="flex-string wp100 header hidden">Имя</div>
                <div class="flex-string wp100 material">
                    <input name="name" type="text" placeholder=" " id="name" width="100%" autocomplete="on"
                           class="required">
                    <label for="name">Имя</label>
                </div>

                <div class="flex-string wp100 mt10 header hidden">Логин / Email</div>
                <div class="flex-string wp100 relative material">
                    <input name="login" placeholder=" " id="login" width="100%">
                    <label for="login">Логин / Email</label>
                    <div id="loginvalidate" class="f14 hidden"></div>
                    <div class="pl10 gray" style="font-size: 10pt">Ваш email может использоваться в качестве логина</div>
                </div>

                <div class="flex-string wp100 relative mt10 material">
                    <input name="password" type="password" id="password" placeholder=" " width="100%" value=""
                           data-id="password">
                    <label for="password">Пароль</label>
                    <div class="showpass">
                        <i class="icon-eye-off hand" title="Показать" id="showpass"></i>
                    </div>
                    <div id="passstrength" class="f14 hidden"></div>
                </div>


                <div class="flex-string wp100 mt10">

                    <div class="row pt10">

                        <div class="column grid-5 hidden"></div>
                        <div class="column grid-10 text-center">
                            <a href="javascript:void(0)" onClick="$('#regform').submit()" class="loginbutton"
                               id="regbutton">Регистрация</a>
                        </div>

                    </div>

                </div>

            </div>

            <div id="wait" class="text-center hidden mt10 mb10">

                <img src="images/loading.svg" width="16" height="16">&nbsp;Загрузка

            </div>

            <div id="result" class="div-center mt15 wp100 pb20 hidden" style="font-size: 12pt">

                <img src="images/regok.png">

                <div class="text">

                    <h2 class="green">Добро пожаловать, <span data-id="name"></span>!</h2>


                </div>

                <a href="login.php" class="loginbutton mt10 mb10">Авторизация</a>

            </div>

            <div class="hidden"><input name="smit" type="submit"></div>

        </form>


    </div>

</DIV>
<script>

    $(document).ready(function () {

        $('#password').bind('change', function (e) {
            checkuserpass();
        });
        $('#password').bind('keyup', function (e) {
            checkuserpass();
        });
        $('#login').bind('focusout', function (e) {
            checkuser();
        });
        $('#name').bind('focusout', function (e) {
            checkname();
        });

    });

    $('#regform').ajaxForm({
        dataType: 'json',
        beforeSubmit: function () {

            if (checkname() && checkuser() && checkuserpass()){

                $('#result').addClass('hidden');
                $('#wait').removeClass('hidden');

                return true;

            }
            else
                return false;

        },
        success: function (data) {

            var name = $('#name').val();

            if (data.result === 'Success') {

                $('span[data-id="name"]').html(name);

                $('#anketa').addClass('hidden');
                $('#result').removeClass('hidden');
                $('#regform').resetForm();

            } else if (data.result === 'Error') {

                $('#result').html(data.message).removeClass('hidden');

            }

            $('#wait').addClass('hidden');

        }

    });

    // Проверка ввода имени
    function checkname() {

        var name = $('#name').val();

        if (name != '') {

            $('#name').removeClass().addClass('good');
            return true;

        } else {

            $('#name').removeClass().addClass('bad');
            return false;

        }

    }

    // Проверка Логина на корректность
    function checkuser() {

        var user = $('#login').val();

        var noCorrLogin = new RegExp("^(?=.[А-я])|(?=[А-я])", "g");

        if (noCorrLogin.test(user) || user.length < 3) {

            $('#login').removeClass().addClass('bad');
            $('#loginvalidate').removeClass().html('Некорректный логин').addClass('red');

            return false;

        } else {

            $('#login').removeClass().addClass('good');
            $('#loginvalidate').removeClass().html('Отлично').addClass('green');

            return true;

        }

    }

    // Проверка пароля на корректность
    function checkuserpass() {

        var strongRegex = new RegExp("^(?=.{8,})(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*\\W).*$", "g");
        var mediumRegex = new RegExp("^(?=.{7,})(((?=.*[A-Z])(?=.*[a-z]))|((?=.*[A-Z])(?=.*[0-9]))|((?=.*[a-z])(?=.*[0-9]))).*$", "g");
        var enoughRegex = new RegExp("(?=.{6,}).*", "g");
        var userpass = $('#password').val();
        var cot = 0;


        if (false == enoughRegex.test(userpass)) {

            $('#passstrength').html('Пароль должен содержать более 6 символов').removeClass('hidden');
            $('#password').removeClass('good').addClass('bad');

            return false;

        } else if (strongRegex.test(userpass)) {

            $('#passstrength').removeClass().addClass('green f10').html('Сложный пароль');
            $('#password').removeClass('bad').addClass('good');

            return true;

        } else if (mediumRegex.test(userpass)) {

            $('#passstrength').removeClass().addClass('blue f10').html('Средняя сложность');
            $('#password').removeClass('bad').addClass('good');

            return true;

        } else {

            $('#passstrength').removeClass().addClass('red f10').html('Проверьте раскладку клавиатуры');
            $('#password').removeClass('good').addClass('bad');

            return false;

        }

    }


</script>
</body>
</html>