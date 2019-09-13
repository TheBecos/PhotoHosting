<?php

error_reporting(E_ERROR);

$rpath = realpath(__DIR__ . '/../..');

include "../../settings/config.php";
include "../../settings/dbconnector.php";
//include $rpath."/settings/auth.php";

require_once "../../settings/Control.php";

$action = $_REQUEST['action'];
$id = $_REQUEST['id'];
$login = $_REQUEST['login'];

if ($action == 'user.edit') {

    $res = \Control::userAdd($_REQUEST);

    print json_encode($res);

    exit();

}

if ($action == 'user.login') {

    $user = \Control::userInfo(0, $login);

    $result = '';

    if ($login == '') {

        $result = 'Error';
        $message = 'Не указан Логин';

    } elseif (password_verify($_REQUEST['password'], $user['password'])) {

        $result = 'Success';
        $message = 'Вы авторизованы';

    } else {

        $result = 'Error';
        $message = 'Неверный Логин / Пароль';

    }

    print json_encode([
        "result" => $result,
        "message" => $message,
        "user" => $user['id']
    ]);

    exit();

}

