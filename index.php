<?php

error_reporting(E_ALL);

include "settings/config.php";
include "settings/dbconnector.php";
include "settings/auth.php";

require_once "settings/Control.php";

$user = \Control::infoUser($_COOKIE['session']);

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

    <link rel="shortcut icon" href="./images/logo1.ico" type="image/x-icon">

    <script type="text/javascript" src="./js/jquery/jquery-3.1.1.min.js"></script>
    <script type="text/javascript" src="./js/jquery/jquery-migrate-3.0.0.min.js"></script>
    <script type="text/javascript" src="./js/jquery/jquery-ui.min.js?v=2018.9"></script>
    <script type="text/javascript" src="./js/jquery/jquery.meio.mask.min.js"></script>
    <script type="text/javascript" src="./js/jquery/jquery.autocomplete.js"></script>

    <script type="text/javascript" src="./js/jquery.form/jquery.form.min.js"></script>

    <script type="text/javascript" src="./js/jquery/jquery.actual.min.js"></script>
    <script type="text/javascript" src="./js/moment.min.js"></script>

    <link type="text/css" rel="stylesheet" href="./css/app.css">
    <link type="text/css" rel="stylesheet" href="css/app.view.css">

    <link type="text/css" rel="stylesheet" href="./css/fontello.css">

    <script type="text/javascript" src="./js/app.core.js"></script>

</head>
<body>

<div id="message" class="message" style="display: none"></div>

<div id="dialog_container" class="dialog_container">

    <div class="dialog-preloader">
        <img src="/images/rings.svg" border="0" width="128">
    </div>
    <div class="dialog" id="dialog" align="left">
        <div class="close" title="Закрыть или нажмите ^ESC" onclick="DClose()"><i class="icon-cancel"></i></div>
        <div id="resultdiv"></div>
    </div>

</div>

<div class="p0 m0 page">

    <div class="header">

        <div class="navigation">

            <span class="logo">
                <img src="images/logo.png" style="height:6vh">
            </span>

            <span class="search ml20 wp50">
              <input name="search" type="text" id="search" placeholder="Поиск" width="100%" value="" class="mt10 wp30"
                     onkeydown="if(event.keyCode==13){ configpage(); return false }">
                <label for="search" class="red" title="Очистить" onclick="clrSearch();"></label>
              <a href="javascript:void(0)" class="button" type="submit" onclick="configpage()">Поиск</a>
            </span>

            <span class="exit-btn">
                <A href="javascript:void(0)"><span class="icn"><i class="icon-off red"></i></span>
                    <span class="text red">Выход</span>
                </A>
            </span>

            <span id="avatar" class="avatar">

                <span class="avatar--image">
                    <span class="avatar--img"></span>
                </span>
                <span class="avatar--txt pt5">
                    <div class="avatar--welcome nowrap" style="font-size: 11pt">Приветствую,</div>
                    <div class="avatar--name Bold" style="font-size: 12pt"><?= $user['name'] ?></div>
                </span>

            </span>

            <span class="actions--photos hidden">
                 <a href="javascript:void(0)" class="redbtn button" type="submit"
                    onclick="$('#photoForm').submit()">Удалить выбранные</a>
                 <a href="javascript:void(0)" class="graybtn button unselectPhoto" type="submit"">Отменить выделение</a>
            </span>


        </div>

    </div>
    <div class="container telo">

        <div class="photos"></div>

    </div>

</div>

<FORM action="backend/core/photos.php" method="post" enctype="multipart/form-data" name="uploadForm" class="hidden"
      id="uploadForm">
    <INPUT name="action" type="hidden" id="action" value="upload">
    <INPUT name="user" type="hidden" id="user" value="<?= $user['id'] ?>">
    <input name="file[]" type="file" class="files wp97" id="file[]" multiple onchange="$('#uploadForm').submit()">
</FORM>

<div class="setButton">
    <a href="javascript:void(0)" type="submit" onclick="/*doLoad('backend/forms/form.upload.php')*/$('.files').click()"
       title="Добавить фото">
        <i class="icon-plus-circled"></i>
    </a>
    <a href="javascript:void(0)" onclick="configpage()" title="Обновить"><i class="icon-arrows-cw"></i></a>
</div>

<script>

    $('#uploadForm').ajaxForm({
        beforeSubmit: function () {

            var $out = $('#message');

            $('#dialog').css('display', 'none');
            $('#dialog_container').css('display', 'none');

            $out.empty().css('display', 'block').append('<div id=loader><img src=images/loader.gif> Загрузка файлов...</div>');

            return true;

        },
        success: function (data) {

            $('#message').fadeTo(1, 1).css('display', 'block').html(data);
            setTimeout(function () {
                $('#message').fadeTo(1000, 0);
            }, 10000);

            configpage();

        }

    });

</script>


</body>
</html>