<?php

error_reporting(E_ALL);

$rpath = realpath(__DIR__ . '/../..');

include $rpath . "/settings/config.php";
include $rpath . "/settings/dbconnector.php";
include $rpath . "/settings/auth.php";

require_once $rpath . "/settings/Control.php";

$action = $_REQUEST['action'];
$user = $_REQUEST['user'];
$id = $_REQUEST['id'];

/*Получение сведений о фотогбифии*/
if ($action == 'info') {

    $res = \Control::photoInfo($id);

    print json_encode($res);

    exit();

}

/*Редактирование описания*/
if ($action == 'edit') {

    $id = $_REQUEST['id'];

    $params = [
        "name" => $_REQUEST['name'],
        "des" => $_REQUEST['des']
    ];

    $res = \Control::photoEdit($id, $params);

    $response = ($res['result'] == 'Success') ? $res['text'] : "Ошибка: " . $res['text'];

    print $response;

    exit();

}

/*Удаление фотографий*/
if ($action == 'delete') {

    $photos = $_REQUEST['select'];

    $res = \Control::photoDelete($photos);

    print json_encode([
        "result" => $res['result'],
        "text" => $res['text'],
    ]);

    exit();

}

/*Загрузка фотографий*/
if ($action == 'upload') {

    $file = [];
    $response = "";

    // Директория куда будут загружаться файлы.
    $dir = '../../upload/';

    // Разрешенные форматы файлов
    $allow = ['image/jpeg', 'image/png', 'image/x-icon', 'image/bmp', 'image/gif'];

    if (isset($_FILES['file'])) {

        // Проверим директорию для загрузки.
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        // Преобразуем массив $_FILES в удобный вид
        $files = [];

        $diff = count($_FILES['file']) - count($_FILES['file'], COUNT_RECURSIVE);

        if ($diff == 0) {

            // Загружен 1 файл
            $files = [$_FILES['file']];

        } else {

            foreach ($_FILES['file'] as $k => $l) {
                foreach ($l as $i => $v) {
                    $files[$i][$k] = $v;
                }
            }

        }

        $countFiles = count($files);
        $currFiles = 0;

        // Обходим массив загруженных файлов
        foreach ($files as $file) {

            $error = $success = '';
            $id = 0;

            // Проверим на ошибки загрузки
            if (!empty($file['error']) || empty($file['tmp_name'])) {

                switch (@$file['error']) {
                    case 1:
                    case 2:
                        $error = 'Превышен размер загружаемого файла.';
                        break;
                    case 3:
                        $error = 'Файл был получен только частично.';
                        break;
                    case 4:
                        $error = 'Файл не был загружен.';
                        break;
                    case 6:
                        $error = 'Файл не загружен - отсутствует временная директория.';
                        break;
                    case 7:
                        $error = 'Не удалось записать файл на диск.';
                        break;
                    case 8:
                        $error = 'PHP-расширение остановило загрузку файла.';
                        break;
                    case 9:
                        $error = 'Файл не был загружен - директория не существует.';
                        break;
                    case 10:
                        $error = 'Превышен максимально допустимый размер файла.';
                        break;
                    case 11:
                        $error = 'Данный тип файла запрещен.';
                        break;
                    case 12:
                        $error = 'Ошибка при копировании файла.';
                        break;
                    default:
                        $error = 'Файл не был загружен - неизвестная ошибка.';
                        break;

                }
            } elseif ($file['tmp_name'] == 'none' || !is_uploaded_file($file['tmp_name'])) {
                $error = 'Не удалось загрузить файл.';
            } else {

                // Оставляем в имени файла только буквы, цифры и некоторые символы.
                $pattern = "[^a-zа-яё0-9,~!@#%^-_\$\?\(\)\{\}\[\]\.]";
                $name = mb_eregi_replace($pattern, '-', $file['name']);
                $name = mb_ereg_replace('[-]+', '-', $name);

                if (!in_array($file['type'], $allow)) {
                    $error = 'Недопустимый тип файла';
                } else {

                    // Проверка ни существование файла на сервере

                    if (!is_file($dir . $name)) {

                        // Перемещаем файл в директорию.
                        if (move_uploaded_file($file['tmp_name'], $dir . $name)) {

                            $success = 'Файл «' . $name . '» успешно загружен.';
                            ++$currFiles;

                        } else {

                            $error = 'Не удалось загрузить файл.';

                        }
                    } else {

                        // Если файл уже имеется на сервере
                        $success = 'Файл «' . $name . '» успешно загружен.';
                        ++$currFiles;

                        // Получим id файла из таблицы с файлами
                        $id = $db->getOne("SELECT id FROM " . $sqlname . "files WHERE file = '" . $name . "'");

                    }

                    // Сохраняем данные о файле в БД
                    if ($id == 0) {

                        $db->query("INSERT INTO " . $sqlname . "files SET ?u", ['file' => $name, 'format' => $file['type']]);
                        $id = $db->insertId();

                    }
                }
            }

            if ($id > 0)
                $db->query("INSERT INTO " . $sqlname . "photo_list SET ?u", ['fid' => $id, 'iduser' => $user]);

            $response = (!empty($success)) ? $success : $error;

        }
    }

    $response = ($currFiles > 1) ? 'Успешно загружено файлов: ' . $currFiles . ' из ' . $countFiles : $response;

    print $response;

    exit();

}
