<?php

$user = $_REQUEST['user'];

?>
<DIV class="zagolovok">Загрузка файла</DIV>

<FORM action="backend/core/core.photos.php" method="post" enctype="multipart/form-data" name="uploadForm" id="uploadForm">
    <INPUT type="hidden" name="action" id="action" value="upload">
    <INPUT name="user" type="hidden" id="user" value="<?= $user ?>">
    <DIV id="formtabs" class="box--child" style="max-height:80vh; overflow-x: hidden; overflow-y:auto !important">

            <div class="flex-container mb10">

                <div class="flex-string wp20 gray2 fs-12 pt7 right-text">Выбор файлов:</div>
                <div class="flex-string wp80 pl10">

                    <input name="file[]" type="file" class="files wp97" id="files[]" multiple>

                    <div class="fs-07 gray2">Вы можете выбрать несколько файлов с помощью клавиши Ctrl</div>
                    <div class="infodiv hidden pad5 fs-09 description" style="overflow: auto; max-height:100px"></div>

                </div>

            </div>
                <span>Разрешенные типы файлов: </span>

            </div>

    </DIV>

    <hr>

    <DIV class="button-pane text-right">

        <A href="javascript:void(0)" onClick="$('#uploadForm').submit()" class="button"><i class="icon-upload"></i>Загрузить</A>&nbsp;
        <A href="javascript:void(0)" onClick="DClose()" class="button">Отмена</A>

    </div>

</FORM>

<script>

    var user = parseInt($('#user').val());

    $('#dialog').css({'width': '700px'});

    $('#uploadForm').ajaxForm({
        beforeSubmit: function () {

            var $out = $('#message');

            $('#dialog').css('display', 'none');
            $('#dialog_container').css('display', 'none');

            $out.empty().css('display', 'block').append('<div id=loader><img src=images/loader.gif> Загрузка файлов...</div>');

            return true;

        },
        success: function (data) {

            $('#message').fadeTo(1, 1).css('display', 'block').html(data);
            setTimeout(function () {
                $('#message').fadeTo(1000, 0);
            }, 10000);

            configpage();

        }

    });
</script>