<?php

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
    public static function userInfo($id, $login = '')
    {

        $rootpath = realpath(__DIR__ . '/../');

        require_once $rootpath . "/settings/config.php";
        require_once $rootpath . "/settings/dbconnector.php";

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
    public static function userAdd($params = array())
    {

        $rootpath = realpath(__DIR__ . '/../');

        require_once $rootpath . "/settings/config.php";
        require_once $rootpath . "/settings/dbconnector.php";
        require_once $rootpath . "/settings/func.php";

        $db = (isset($db)) ? $db : $GLOBALS['db'];
        $sqlname = (isset($sqlname)) ? $sqlname : $GLOBALS['sqlname'];

        $user['login'] = $params['login'];
        $user['name'] = $params['name'];
        $user['password'] = password_hash($params['password'], PASSWORD_BCRYPT);

        //Проверяем ни наличие пользователя с таким логином
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
     * Удаление фотографии
     * @param mixed $photos
     * @return array
     */
    public static function deletePhoto($photos = array())
    {

        $rootpath = realpath(__DIR__ . '/../');

        require_once $rootpath . "/settings/config.php";
        require_once $rootpath . "/settings/dbconnector.php";

        $db = (isset($db)) ? $db : $GLOBALS['db'];
        $sqlname = (isset($sqlname)) ? $sqlname : $GLOBALS['sqlname'];

        // Проверка: удаление 1 файла или нескольких
        if (is_array($photos)) {

            //Проверяем наличие входных параметров, в случае успеха - удаляем фото
            if (count($photos) > 0) {


                foreach ($photos as $photo) {

                    $db->query("delete from " . $sqlname . "photo_list WHERE id = '$photo'");

                }

                $response['result'] = 'Success';
                $response['text'] = 'Фото удалены';

            } else {

                $response['result'] = 'Error';
                $response['error'] = 'Фотографии не выбраны';

            }

        } else {

            $db->query("delete from " . $sqlname . "photo_list WHERE id = '$photos'");

            $response['result'] = 'Success';
            $response['text'] = 'Фото удалено';

        }

        return $response;

    }


    /**
     * Загрузка файлов
     * @param string $extra - сохранение
     * @param string $rename
     * @return array
     */
    public static function upload($extra = '')
    {

        $rootpath = realpath(__DIR__ . '/../../');

        require_once $rootpath . "/inc/config.php";
        require_once $rootpath . "/inc/dbconnector.php";
        require_once $rootpath . "/inc/func.php";


        $uploaddir = '/upload/';
        $extAllow = ['image/png', 'image/jpeg', 'image/x-icon', 'image/bmp'];
        $message = [];

        //print_r($_FILES);

        $file = [];

        //если загружается несколько файлов
        if (is_array($_FILES['file']['name'])) {

            for ($i = 0; $i < count($_FILES['file']['name']); $i++) {

                if (filesize($_FILES['file']['tmp_name'][$i]) > 0) {

                    $ftitle = $_FILES['file']['name'][$i];
                    $fname = md5($ftitle . filesize($_FILES['files']['tmp_name'][$i]) . time()) . "." . end(explode(".", $ftitle));
                    $ftype = $_FILES['file']['type'][$i];
                    $uploadfile = $uploaddir . $fname;
                    $ext = texttosmall(end(explode(".", $ftitle)));

                    if (in_array($ext, $extAllow)) {


                        if (move_uploaded_file($_FILES['file']['tmp_name'][$i], $uploadfile)) {

                            $message[] = 'Файл ' . $ftitle . ' успешно загружен.';
                            $file[] = [
                                "title" => $ftitle,
                                "name" => $fname,
                                "type" => $ftype,
                                "size" => filesize($_FILES['files']['tmp_name'][$i])
                            ];

                        } else $message[] = 'Ошибка при загрузке файла ' . $ftitle . ' - ' . $_FILES['file']['error'][$i];

                    } else $message[] = 'Ошибка при загрузке файла ' . $ftitle . ' - Файлы такого типа не разрешено загружать.';

                }

            }

        } //если загружается один файл
        else {

            if (filesize($_FILES['file']['tmp_name']) > 0) {

                $ftitle = $_FILES['file']['name'];
                $fname = md5($ftitle . filesize($_FILES['files']['tmp_name']) . time()) . "." . end(explode(".", $ftitle));
                $ftype = $_FILES['file']['type'];
                $uploadfile = $uploaddir . $fname;
                $ext = texttosmall(end(explode(".", $ftitle)));

                if (in_array($ext, $extAllow)) {

                    if ((filesize($_FILES['file']['tmp_name']) / 1000000) > $maxupload) $message[] = 'Ошибка при загрузке файла ' . $ftitle . ' - Превышает допустимые размеры!';
                    else {

                        if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {

                            $message[] = 'Файл ' . $ftitle . ' успешно загружен.';
                            $file[] = [
                                "title" => $ftitle,
                                "name" => $fname,
                                "type" => $ftype,
                                "size" => filesize($_FILES['files']['tmp_name'])
                            ];

                        } else $message[] = 'Ошибка при загрузке файла ' . $ftitle . ' - ' . $_FILES['file']['error'];

                    }

                } else $message[] = 'Ошибка при загрузке файла ' . $ftitle . ' - Файлы такого типа не разрешено загружать.';

            }

        }

        //если загружается несколько файлов в одном поле
        for ($i = 0; $i < count($_FILES['files']['name']); $i++) {

            if (filesize($_FILES['files']['tmp_name'][$i]) > 0) {

                $ftitle = $_FILES['files']['name'][$i];
                $fname = md5($ftitle . filesize($_FILES['files']['tmp_name'][$i]) . time()) . "." . end(explode(".", $ftitle));
                $ftype = $_FILES['files']['type'][$i];
                $uploadfile = $uploaddir . $fname;
                $ext = texttosmall(end(explode(".", $ftitle)));

                if (in_array($ext, $extAllow)) {

                        if (move_uploaded_file($_FILES['files']['tmp_name'][$i], $uploadfile)) {

                            $message[] = 'Файл ' . $ftitle . ' успешно загружен.';
                            $file[] = [
                                "title" => $ftitle,
                                "name" => $fname,
                                "type" => $ftype,
                                "size" => filesize($_FILES['files']['tmp_name'][$i])
                            ];

                        } else $message[] = 'Ошибка при загрузке файла ' . $ftitle . ' - ' . $_FILES['files']['error'][$i];


                } else $message[] = 'Ошибка при загрузке файла ' . $ftitle . ' - Файлы такого типа не разрешено загружать.';

            }

        }

        //print_r($message);

        $response = [
            "data" => $file,
            "message" => $message
        ];

        return $response;

    }

}