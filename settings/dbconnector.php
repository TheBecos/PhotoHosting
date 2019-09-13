<?php
$rootpath = realpath(__DIR__.'/../');

require_once $rootpath."/vendor/class/safemysql.class.php";

$opts = array(
	'host'    => $dbhostname,
	'user'    => $dbusername,
	'pass'    => $dbpassword,
	'db'      => $database,
	'errmode' => 'exception',
	'charset' => 'UTF8'
);

try {

	$db = new SafeMySQL($opts);

	$db -> query("SET NAMES 'utf8', collation_connection='utf8_general_ci', character_set_client='utf8', character_set_database='utf8', character_set_server='utf8', character_set_results='utf8'");

}
catch (Exception $e) {

	print $err[] = 'Ошибка подключения к БД: '.$e -> getMessage().'. Рекомендуем проверить параметры подключения к БД в файле "settings/config.php".';

	exit();

}

/**
 * Запись логов ошибок в файл
 */
if (!file_exists($rootpath."/cash/error.log")) {

	$file = fopen($rootpath."/cash/error.log", "w");
	fclose($file);

}
ini_set('log_errors', 'On');
ini_set('error_log', $rootpath."/cash/error.log");
