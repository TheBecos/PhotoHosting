<?php

$scheme = isset($_SERVER['HTTP_SCHEME']) ? $_SERVER['HTTP_SCHEME'] : (((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || 443 == $_SERVER['SERVER_PORT']) ? 'https://' : 'http://');

$db = $GLOBALS['db'];

if (isset($_REQUEST['action']) && ($_REQUEST['action'] == "user.login"))
    goto a;


if ($_COOKIE['session'] != '') {

    $user = $db->getRow("SELECT * FROM " . $sqlname . "user WHERE id = " .$_COOKIE['session']);

    if ($user['id'] > 0) {

        $UserId = $user['id'] + 0;
        $UserName = $user['name'];
        $UserLogin = $user['login'];

    }

} else {

    header("Location: " . $scheme . $_SERVER['HTTP_HOST'] . "/login.php");

}

a: