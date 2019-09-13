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
if ($action == 'list') {

    if ($word != '')
        $search .= " AND ((" . $sqlname . "photo_list.name LIKE '%" . $word . "%') OR (" . $sqlname . "photo_list.name LIKE '%" . des . "%'))";

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

}

?>

<div class="grid">


    <form action="backend/core/core.photos.php" method="post" enctype='multipart/form-data' id="upload" name="upload">
        <INPUT type="hidden" name="action" id="action" value="upload">
        <input name="file[]" type="file" class="file hidden" id="file[]" multiple onchange="Upload()">

    </form>

    <form action="backend/core/core.photos.php" method="post" id="photoForm" name="photoForm"
          enctype="multipart/form-data">
        <INPUT type="hidden" name="action" id="action" value="delete">
        <?php

        foreach ($list as $photo) {

            ?>

            <label class="grid-item">
                <input name="select[]" type="checkbox" id="select[]" class="selPhoto" value="<?= $photo['id'] ?>">
                <img src="images/<?= $photo['filename'] ?>"/>
                <span class="visor">
                    <span id="title"><?= $photo['name'] ?></span>
                    <span class="actions" style="float: right">
                        <a href="javascript:void(0)" data-id="<?= $photo['id'] ?>" title="Изменить"
                           class="gray editPhoto"><i class="icon-pencil green"></i></a>
                        <a href="javascript:void(0)" data-id="<?= $photo['id'] ?>" title="Удалить"
                           class="gray deletePhoto"><i class="icon-cancel-circled red"></i></a>
                    </span>
                </span>
            </label>

        <?php } ?>

        <label class="grid-item">
            <input name="select[]" type="checkbox" id="select[]" class="selPhoto" value="100">
            <img src="//unsplash.it/800/600?image=1"/>
            <span class="visor">
                    <span> Тест</span>
                    <span class="actions" style="float: right">
                        <a href="javascript:void(0)" title="Изменить"
                           class="gray editPhoto"><i class="icon-pencil green"></i></a>
                        <a href="javascript:void(0)" data - tip="course" data - id="{{id}}" title="Удалить"
                           class="gray deletePhoto"><i class="icon-cancel-circled red"></i></a>
                    </span>
                </span>
        </label>

        <!--Для тестирования-->

        <label for="pic-1" class="grid-item"><img src="//unsplash.it/400/300?image=1"/><span class="visor">{
                {
                    name}
            }
                <a
                        href="#" style="float:right"> Изменить</a></span></label>
        <label for="pic-2" class="grid-item"><img src="//unsplash.it/400/300?image=20"/><span class="visor">{
                {
                    name}
            }
                <a
                        href="#" style="float:right"> Изменить</a></span></label>
        <label for="pic-4" class="grid-item"><img src="//unsplash.it/400/300?image=42"/><span class="visor">{
                {
                    name}
            }
                <a
                        href="#" style="float:right"> Изменить</a></span></label>
        <label for="pic-5" class="grid-item"><img src="//unsplash.it/400/300?image=20"/><span class="visor">{
                {
                    name}
            }
                <a
                        href="#" style="float:right"> Изменить</a></span></label>
        <label for="pic-6" class="grid-item"><img src="//unsplash.it/400/300?image=42"/><span class="visor">{
                {
                    name}
            }
                <a
                        href="#" style="float:right"> Изменить</a></span></label>
        <label for="pic-7" class="grid-item"><img src="//unsplash.it/400/300?image=42"/><span class="visor">{
                {
                    name}
            }
                <a
                        href="#" style="float:right"> Изменить</a></span></label>
        <label for="pic-8" class="grid-item"><img src="//unsplash.it/400/300?image=24"/><span class="visor">{
                {
                    name}
            }
                <a
                        href="#" style="float:right"> Изменить</a></span></label>
        <label for="pic-1" class="grid-item"><img src="//unsplash.it/400/300?image=1"/></label>
        <label for="pic-2" class="grid-item"><img src="//unsplash.it/400/300?image=20"/></label>
        <label for="pic-4" class="grid-item"><img src="//unsplash.it/400/300?image=42"/></label>
        <label for="pic-5" class="grid-item"><img src="//unsplash.it/400/300?image=20"/></label>
        <label for="pic-6" class="grid-item"><img src="//unsplash.it/400/300?image=42"/></label>
        <label for="pic-7" class="grid-item"><img src="//unsplash.it/400/300?image=42"/></label>
        <label for="pic-8" class="grid-item"><img src="//unsplash.it/400/300?image=24"/></label>
        <label for="pic-1" class="grid-item"><img src="//unsplash.it/400/300?image=1"/></label>
        <label for="pic-2" class="grid-item"><img src="//unsplash.it/400/300?image=20"/></label>
        <label for="pic-4" class="grid-item"><img src="//unsplash.it/400/300?image=42"/></label>
        <label for="pic-5" class="grid-item"><img src="//unsplash.it/400/300?image=20"/></label>
        <label for="pic-6" class="grid-item"><img src="//unsplash.it/400/300?image=42"/></label>
        <label for="pic-7" class="grid-item"><img src="//unsplash.it/400/300?image=42"/></label>
        <label for="pic-8" class="grid-item"><img src="//unsplash.it/400/300?image=24"/></label>
        <label for="pic-1" class="grid-item"><img src="//unsplash.it/400/300?image=1"/></label>
        <label for="pic-2" class="grid-item"><img src="//unsplash.it/400/300?image=20"/></label>
        <label for="pic-4" class="grid-item"><img src="//unsplash.it/400/300?image=42"/></label>
        <label for="pic-5" class="grid-item"><img src="//unsplash.it/400/300?image=20"/></label>
        <label for="pic-6" class="grid-item"><img src="//unsplash.it/400/300?image=42"/></label>
        <label for="pic-7" class="grid-item"><img src="//unsplash.it/400/300?image=42"/></label>
        <label for="pic-8" class="grid-item"><img src="//unsplash.it/400/300?image=24"/></label>
        <label for="pic-1" class="grid-item"><img src="//unsplash.it/400/300?image=1"/></label>
        <label for="pic-2" class="grid-item"><img src="//unsplash.it/400/300?image=20"/></label>
        <label for="pic-4" class="grid-item"><img src="//unsplash.it/400/300?image=42"/></label>
        <label for="pic-5" class="grid-item"><img src="//unsplash.it/400/300?image=20"/></label>
        <label for="pic-6" class="grid-item"><img src="//unsplash.it/400/300?image=42"/></label>
        <label for="pic-7" class="grid-item"><img src="//unsplash.it/400/300?image=42"/></label>
        <label for="pic-8" class="grid-item"><img src="//unsplash.it/400/300?image=24"/></label>
        <label for="pic-1" class="grid-item"><img src="//unsplash.it/400/300?image=1"/></label>
        <label for="pic-2" class="grid-item"><img src="//unsplash.it/400/300?image=20"/></label>
        <label for="pic-4" class="grid-item"><img src="//unsplash.it/400/300?image=42"/></label>
        <label for="pic-5" class="grid-item"><img src="//unsplash.it/400/300?image=20"/></label>
        <label for="pic-6" class="grid-item"><img src="//unsplash.it/400/300?image=42"/></label>
        <label for="pic-7" class="grid-item"><img src="//unsplash.it/400/300?image=42"/></label>
        <label for="pic-8" class="grid-item"><img src="//unsplash.it/400/300?image=24"/></label>
        <label for="pic-1" class="grid-item"><img src="//unsplash.it/400/300?image=1"/></label>
        <label for="pic-2" class="grid-item"><img src="//unsplash.it/400/300?image=20"/></label>
        <label for="pic-4" class="grid-item"><img src="//unsplash.it/400/300?image=42"/></label>
        <label for="pic-5" class="grid-item"><img src="//unsplash.it/400/300?image=20"/></label>
        <label for="pic-6" class="grid-item"><img src="//unsplash.it/400/300?image=42"/></label>
        <label for="pic-7" class="grid-item"><img src="//unsplash.it/400/300?image=42"/></label>
        <label for="pic-8" class="grid-item"><img src="//unsplash.it/400/300?image=24"/></label>

        <label class="grid-item" style="width: 97vw; height: 10vh"></label>


</div>
</form>

<script>
    $('#photoForm').ajaxForm({
        dataType: 'json',
        beforeSubmit: function () {

        },
        success: function (data) {

            if (data.result === 'Success') {

                $('#message').fadeTo(1, 1).css('display', 'block').html('Результат: ' + data.text);

                setTimeout(function () {
                    $('#message').fadeTo(1000, 0);
                }, 20000);

                configpage();

            } else if (data.result === 'Error') {

                $('#loginBlock').find('div[data-result="error"]').removeClass('hidden').html(data.error);

            }

        }
    });
</script>
