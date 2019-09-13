-- --------------------------------------------------------
-- Хост:                         127.0.0.1
-- Версия сервера:               5.6.41 - MySQL Community Server (GPL)
-- Операционная система:         Win32
-- HeidiSQL Версия:              10.1.0.5484
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Дамп структуры базы данных PhotoGallery
CREATE DATABASE IF NOT EXISTS `PhotoGallery` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `PhotoGallery`;

-- Дамп структуры для таблицы PhotoGallery.app_photo_list
DROP TABLE IF EXISTS `app_photo_list`;
CREATE TABLE IF NOT EXISTS `app_photo_list` (
  `id` int(30) NOT NULL AUTO_INCREMENT,
  `datum` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'дата добавления',
  `name` varchar(255) DEFAULT NULL COMMENT 'заголовок к фото',
  `des` TEXT DEFAULT NULL COMMENT 'описание',
  `fid` int(10) DEFAULT NULL COMMENT 'id файла в таблице files',
  `iduser` int(10) DEFAULT NULL COMMENT 'привязка к пользователю',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Фотографии пользователя';


-- Дамп структуры для таблицы PhotoGallery.app_files
DROP TABLE IF EXISTS `app_files`;
CREATE TABLE IF NOT EXISTS `app_files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `datum` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'дата добавления',
  `file` varchar(255) DEFAULT NULL COMMENT 'имя файла на диске',
  `format` varchar(255) DEFAULT NULL COMMENT 'Формат файла',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='База фотографий';


-- Дамп структуры для таблицы PhotoGallery.app_user
DROP TABLE IF EXISTS `app_user`;
CREATE TABLE IF NOT EXISTS `app_user` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `login` varchar(250) NOT NULL COMMENT 'Логин',
  `password` varchar(250) NOT NULL COMMENT 'Пароль',
  `name` varchar(250) DEFAULT NULL COMMENT 'Имя',
  `email` TEXT NOT NULL COMMENT 'Email',
  `avatar` varchar(100) NOT NULL COMMENT 'аватар',
  PRIMARY KEY (`id`),
  KEY `title` (`title`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='База пользователей';



/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
