<?php
error_reporting(E_ERROR);
header("Pragma: no-cache");

include "../../settings/config.php";
include "../../settings/dbconnector.php";
include "../../settings/auth.php";

$action = $_REQUEST['action'];
$user = $_REQUEST['user'];
$word = $_REQUEST['search'];

$search = "";

/**
 * Список фотографий пользователя
 */
if ($word != '')  $search .= " AND ((" . $sqlname . "photo_list.name LIKE '%" . $word . "%') OR (" . $sqlname . "photo_list.des LIKE '%" . $word . "%'))";

if ($user > 0)
    $search .= " AND " . $sqlname . "photo_list.iduser = " . $user;

$q = "SELECT 
				" . $sqlname . "photo_list.id,
				" . $sqlname . "photo_list.datum as photo_date,
				" . $sqlname . "photo_list.name as name,
				" . $sqlname . "photo_list.des as des,
				" . $sqlname . "photo_list.fid as fid,
				" . $sqlname . "photo_list.iduser as iduser,
				" . $sqlname . "files.datum as file_date,
				" . $sqlname . "files.file as filename,
				" . $sqlname . "files.format as format
			FROM " . $sqlname . "photo_list
			    LEFT JOIN " . $sqlname . "files ON " . $sqlname . "photo_list.fid = " . $sqlname . "files.id
			WHERE 
				" . $sqlname . "photo_list.id > 0
				$search
			ORDER BY photo_date DESC";

$list = $db->getAll($q);

?>

<form action="backend/core/core.photos.php" method="post" id="photoForm" name="photoForm" enctype="multipart/form-data">

    <div class="grid">

        <INPUT type="hidden" name="action" id="action" value="delete">
        <?php

        foreach ($list as $photo) {

            ?>

            <div class="grid-item">

                <div class="checkbox">
                    <label>
                        <input name="select[]" type="checkbox" id="select[]" class="selPhoto" value="<?= $photo['id'] ?>">
                        <span class="custom-checkbox"><i class="icon-ok"></i></span>
                    </label>
                </div>

                <img src="upload/<?= $photo['filename'] ?>" class="photo hand" data-id="<?= $photo['id'] ?>"/>

                <span class="visor">

                    <span id="title" style="padding-left: 1.7vw" class="ellipsis"><?= $photo['name'] ?></span>
                    <span class="actions" style="float: right">
                        <a href="javascript:void(0)" data-id="<?= $photo['id'] ?>" title="Изменить"
                           class="gray editPhoto"
                           onclick="doLoad('backend/forms/form.edit.php?id=<?= $photo['id'] ?>')"><i
                                    class="icon-pencil green"></i></a>
                        <a href="javascript:void(0)" data-id="<?= $photo['id'] ?>" title="Удалить"
                           class="gray deletePhoto"><i class="icon-cancel-circled red"></i></a>
                    </span>
                </span>

            </div>

        <?php } ?>

        <label class="grid-item" style="width: 97vw; height: 10vh"></label>

    </div>
</form>

<script>

    /*ОбрабоФтит формы*/
    $('#photoForm').ajaxForm({
        dataType: 'json',
        beforeSubmit: function () {

        },
        success: function (data) {

            var mes = '';

            if (data.result === 'Success') {

                mes += 'Результат: ' + data.text;

                setTimeout(function () {
                    $('#message').fadeTo(1000, 0);
                }, 20000);

                configpage();

            } else if (data.result === 'Error') {

                mes += 'Ошибка: ' + data.text;

            }

            $('#message').fadeTo(1, 1).css('display', 'block').html(mes);

            setTimeout(function () {
                $('#message').fadeTo(1000, 0);
            }, 20000);

            configpage();

        }
    });

</script>
