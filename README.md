# PhotoGallery
Test task

1. Дамп БД находится в папке /_install/
2. Параметры подключения к БД указываются в файле settings/config.php
    При отсутствии файла необходимо создать этот файл следующей структуры:
        
        /*Example*/
        
            $dbhostname = "127.0.0.1";    // адрес сервера
            $dbusername = "ivan";         // имя пользователя
            $dbpassword = "ivan!1";       // пароль
            $database   = "photogallery"; // название БД
            $sqlname    = "";             // префикс таблиц
