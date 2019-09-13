<?php

error_reporting(E_ALL);

require_once "./../settings/config.php";
require_once "./../settings/dbconnector.php";


/**
 * Добавляем таблицу Пользователей
 */
$da = $db->getCol("SELECT COUNT(*) as count FROM INFORMATION_SCHEMA.STATISTICS WHERE table_schema = '$database' and TABLE_NAME = '{$sqlname}user'");
if ($da[0] == 0) {

    $db->query("
	CREATE TABLE {$sqlname}user (
		  `id` int(10) NOT NULL AUTO_INCREMENT,
          `login` varchar(250) NOT NULL COMMENT 'Логин',
          `password` varchar(250) NOT NULL COMMENT 'Пароль',
          `name` varchar(250) DEFAULT NULL COMMENT 'Имя пользователя',
        PRIMARY KEY (`id`),
        KEY `title` (`title`)
	) 
	COMMENT='База пользователей' 
	COLLATE='utf8_general_ci'
	ENGINE=MyISAM");

}


/**
 * Добавляем таблицу для хранения файлов
 */
$da = $db->getCol("SELECT COUNT(*) as count FROM INFORMATION_SCHEMA.STATISTICS WHERE table_schema = '$database' and TABLE_NAME = '{$sqlname}files'");
if ($da[0] == 0) {

    $db->query("
        CREATE TABLE IF NOT EXISTS `app_files` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `datum` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'дата добавления',
              `file` varchar(255) DEFAULT NULL COMMENT 'имя файла на диске',
              `format` varchar(255) DEFAULT NULL COMMENT 'Формат файла',
         PRIMARY KEY (`id`)
        ) 
     COMMENT='База фотографий' 
	 COLLATE='utf8_general_ci'
	 ENGINE=MyISAM");

}

/**
 * Добавляем таблицу Фотографии пользователей
 */
$da = $db->getCol("SELECT COUNT(*) as count FROM INFORMATION_SCHEMA.STATISTICS WHERE table_schema = '$database' and TABLE_NAME = '{$sqlname}photo_list'");
if ($da[0] == 0) {

    $db->query("
        CREATE TABLE IF NOT EXISTS `app_photo_list` (
              `id` int(30) NOT NULL AUTO_INCREMENT,
              `datum` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'дата добавления',
              `name` varchar(255) DEFAULT NULL COMMENT 'заголовок к фото',
              `des` TEXT DEFAULT NULL COMMENT 'описание',
              `fid` int(10) DEFAULT NULL COMMENT 'id файла в таблице files',
              `iduser` int(10) DEFAULT NULL COMMENT 'привязка к пользователю',
          PRIMARY KEY (`id`)
        ) 
        COMMENT='База фотографий' 
        COLLATE='utf8_general_ci'
	    ENGINE=MyISAM");


}

print "Установка завершена";