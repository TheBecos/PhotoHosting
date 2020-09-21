<?php


require_once "config.php";
require_once "dbconnector.php";

/**
 * Class Control
 */
class Control
{

    /**
     * Возвращение информации о пользователе по ID / Логину
     * @param int $id
     * @param string $login
     * @return array
     */
    public static function infoUser($id, $login = '')
    {

        $db = (isset($db)) ? $db : $GLOBALS['db'];
        $sqlname = (isset($sqlname)) ? $sqlname : $GLOBALS['sqlname'];

        $q = '';

        if ($id > 0)
            $q = $sqlname . "user.id = '$id'";
        else
            $q = $sqlname . "user.login = '$login'";

        $user = $db->getRow("
				SELECT 
					*
				FROM " . $sqlname . "user
				WHERE
					" . $q . "
			");

        return $user;

    }

    /**
     * Добавление пользователя
     * @param array $params
     * @return array
     */
    public static function addUser($params = [])
    {

        $db = (isset($db)) ? $db : $GLOBALS['db'];
        $sqlname = (isset($sqlname)) ? $sqlname : $GLOBALS['sqlname'];

        $user['login'] = $params['login'];
        $user['name'] = $params['name'];
        $user['password'] = password_hash($params['password'], PASSWORD_BCRYPT);

        //Проверяем на наличие пользователя с таким логином
        $userOld = $db->getRow("SELECT id,name FROM " . $sqlname . "user WHERE login = '" . $user["login"] . "'");

        if ($userOld['id'] > 0) {

            $response['result'] = 'Error';
            $response['id'] = $userOld['id'];
            $response['message'] = "Найден существующий пользователь - \"" . $userOld['name'] . "\".<br>Запрос на регистрацию отклонен.";

        } else {

            // Добавляем пользователя
            $db->query("INSERT INTO " . $sqlname . "user SET ?u", $user);
            $id = $db->insertId();

            $response['result'] = 'Success';
            $response['message'] = 'Пользователь зарегистрирован';
            $response['id'] = $id;

        }

        return $response;

    }

    /**
     * Возвращение информации о фотографии
     * @param int $id
     * @return array
     */
    public static function infoPhoto($id)
    {

        $db = (isset($db)) ? $db : $GLOBALS['db'];
        $sqlname = (isset($sqlname)) ? $sqlname : $GLOBALS['sqlname'];

        $photo = $db->getRow("
				SELECT 
					*
				FROM " . $sqlname . "photo_list
				WHERE
					id=" . $id . "
			");

        return $photo;

    }

    /**
     * Редактирование описания фотографии
     * @param int $id
     * @param array $params
     * @return array
     */
    public static function editPhoto($id, $params = [])
    {

        $db = (isset($db)) ? $db : $GLOBALS['db'];
        $sqlname = (isset($sqlname)) ? $sqlname : $GLOBALS['sqlname'];

        if ($id > 0) {

            $db->query("UPDATE " . $sqlname . "photo_list SET ?u where id = '$id'", $params);

            $response['result'] = 'Success';
            $response['text'] = 'Данные изменены';

        } else {

            $response['result'] = 'Error';
            $response['text'] = 'Фото не выбрано';

        }

        return $response;

    }

    /**
     * Удаление фотографии
     * @param mixed $photos
     * @return array
     */
    public static function deletePhoto($photos = [])
    {

        $db = (isset($db)) ? $db : $GLOBALS['db'];
        $sqlname = (isset($sqlname)) ? $sqlname : $GLOBALS['sqlname'];

        // Проверка: удаление 1 файла или нескольких
        if (is_array($photos)) {

            //Проверяем наличие входных параметров, в случае успеха - удаляем фото
            if (count($photos) > 0) {

                $photos = "'" . implode("','", $photos) . "'";

                $response = $db -> query("delete from " . $sqlname . "photo_list WHERE id IN (" . $photos . ")");

                $response['result'] = 'Success';
                $response['text'] = 'Фото удалены';

            } else {

                $response['result'] = 'Error';
                $response['text'] = 'Фотографии не выбраны';

            }

        } else {

            $db->query("delete from " . $sqlname . "photo_list WHERE id = '$photos'");

            $response['result'] = 'Success';
            $response['text'] = 'Фото удалено';

        }

        return $response;

    }


}