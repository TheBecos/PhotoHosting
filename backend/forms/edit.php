<?php

$rpath = realpath(__DIR__ . '/../..');

require_once $rpath . "/settings/Control.php";

$id = $_REQUEST['id'];

if ($id > 0) {

    $photo = \Control::infoPhoto($id);

}


?>
    <div class="zagolovok"><b>Редактирование описания</b></div>
    <FORM method="post" action="backend/core/photos.php" name="formEdit" id="formEdit">
        <INPUT name="action" id="action" type="hidden" value="edit">
        <INPUT name="id" id="id" type="hidden" value="<?= $id ?>">

        <div class="row">

            <div class="column grid-2 pt15 fs-12 right-text">Название:</div>
            <div class="column grid-8">
                <INPUT name="name" id="name" type="text" class="required wp100"
                       value="<?= $photo['name'] ?>">
            </div>

            <div class="column grid-2 pt5 fs-12 right-text">Описание:</div>
            <div class="column grid-8">
                <textarea name="des" class="required wp100" id="des"><?= $photo['des'] ?></textarea>
            </div>

        </div>

        <hr>

        <div class="button--pane text-right">

            <A href="javascript:void(0)" onClick="$('#formEdit').submit()" class="greenbtn">Сохранить</A>&nbsp;
            <A href="javascript:void(0)" onClick="DClose();" class="graybtn">Отмена</A>

        </div>

    </FORM>

    <script>
        $('#formEdit').ajaxForm({
            beforeSubmit: function () {


                $('#dialog').css('display', 'none');
                $('#dialog_container').css('display', 'none');

                $('#message').empty().css('display', 'block').fadeTo(1, 1).append('<div id=loader><img src=/images/loading.svg> Выполняю...</div>');

                return true;

            },
            success: function (data) {

                $('#message').css('display', 'block').fadeTo(1, 1).html(data);

                setTimeout(function () {
                    $('#message').fadeTo(1000, 0);
                }, 1000);

                configpage();
            }

        });
    </script>
<?php
exit();