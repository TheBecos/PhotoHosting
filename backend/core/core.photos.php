<?php

error_reporting(E_ALL);

$rpath = realpath(__DIR__ . '/../..');

include $rpath . "/settings/config.php";
include $rpath . "/settings/dbconnector.php";
include $rpath . "/settings/auth.php";

require_once $rpath . "/settings/Control.php";

$action = $_REQUEST['action'];
//$id = $_REQUEST['id'];

/*Редактирование описания*/
if ($action == 'edit') {

    $params = $_REQUEST;

    //найдем разрешенный формат файлов
    $type = \Document::typesDocument();
    $params['ext_allow'] = $type[$params['type']]['format'];

    $upload = \Document::fileUpload($params);

    $params['files'] = $upload['files'];
    $params['uploader'] = 'castomer';

    $mes = yimplode("<br>", $upload['message']);

    if ($id == 0) {

        $res = \Document::editDocument($id, $params);

        \CastomerLogs::logit('903', [
            $type[$params['type']]['title'],
            $params['title'],
            \Control::castomerName($CustomerID)
        ]);

    } else {

        $res = \Document::editDocument($id, $params);

        \CastomerLogs::logit('904', [
            $type[$params['type']]['title'],
            $params['title'],
            \Control::castomerName($CustomerID)
        ]);

    }

    //актуализация статуса
    $progress = \Document::progressDocument($CustomerID);
    if ($progress['check'] == 0)
        $status = 0;
    elseif ($progress['check'] < $progress['total'])
        $status = 1;
    elseif ($progress['check'] == $progress['total'])
        $status = 2;

    //устанавливаем новый статус
    $change = \Document::statusDocumentChange($CustomerID, ["status" => $status]);

    $error = (count($upload['error']) > 0) ? yimplode("<br>", $upload['error']) : null;

    print json_encode_cyr(array(
        "result" => ($res['result']) ? $res['result'] : null,
        "error" => $error,
        "message" => $mes
    ));

    exit();

}

/*Удаление фотографий*/
if ($action == 'delete') {

    $photos = $_REQUEST['select'];

    $res = \Control::deletePhoto($photos);

    print json_encode(array(
        "result" => ($res['result']) ? $res['result'] : null,
        "text" => $res['text']
    ));

    exit();

}

/*Удаление фотографий*/
if ($action == 'upload') {

    $file = [];
    $response = "";

    // Директория куда будут загружаться файлы.
    $dir = '../../upload/';

    // Разрешенные форматы файлов
    $allow = ['image/jpeg', 'image/png', 'image/x-icon', 'image/bmp'];

    if (isset($_FILES['file'])) {
        // Проверим директорию для загрузки.
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        // Преобразуем массив $_FILES в удобный вид для перебора в foreach.
        $files = [];
        $diff = count($_FILES['file']) - count($_FILES['file'], COUNT_RECURSIVE);
        if ($diff == 0) {
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

        foreach ($files as $file) {

            $error = $success = '';

            // Проверим на ошибки загрузки.
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

                            // Далее можно сохранить название файла в БД и т.п.
                            $success = 'Файл «' . $name . '» успешно загружен.';
                            ++$currFiles;

                        } else {

                            $error = 'Не удалось загрузить файл.';

                        }
                    } else {

                        $success = 'Файл уже существует';
                        ++$currFiles;

                    }

                }
            }

            $response = (!empty($success)) ? $success : $error;

        }
    }

    $response = ($currFiles > 1) ? 'Успешно загружено файлов: ' . $currFiles . ' из ' . $countFiles : $response;

    print $response;

    exit();

}
