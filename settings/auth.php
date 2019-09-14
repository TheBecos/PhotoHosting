<?php

$scheme = isset($_SERVER['HTTP_SCHEME']) ? $_SERVER['HTTP_SCHEME'] : (((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || 443 == $_SERVER['SERVER_PORT']) ? 'https://' : 'http://');

$db = $GLOBALS['db'];

$UserId=0;

if (!isset($_REQUEST['action']) || ($_REQUEST['action'] != "user.login")) {

    if ($_COOKIE['session'] != '') {

        $user = $db->getRow("SELECT * FROM " . $sqlname . "user WHERE id = " . $_COOKIE['session']);

        if (($user['id'] > 0) && ($_COOKIE['password'] == $user['password'])) {

            $UserId = $user['id'] + 0;
            $UserName = $user['name'];
            $UserLogin = $user['login'];

        }

    }

    if($UserId == 0)
        header("Location: " . $scheme . $_SERVER['HTTP_HOST'] . "/login.php");

}