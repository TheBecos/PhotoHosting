var javascripts = [];
var $display = '';

/**
 * Подключение js-файла
 * @param path
 * @returns {boolean}
 */
function includeJS(path) {

    for (var i = 0; i < javascripts.length; i++) {
        if (path === javascripts[i]) {
            return false;
        }
    }

    javascripts.push(path);
    $.ajax({
        url: path,
        dataType: "script",// при типе script, JS сам инклюдится и воспроизводится
        async: false
    });

}

$(document).ready(function () {

    configpage();

});

$('#message').click(function () {
    $(this).fadeTo(10, 0).hide('normal').empty();
});

$(document).mouseup(function (e) {

    var div = $(".ydropDown.open");

    if (!div.is(e.target) && div.has(e.target).length === 0) {

        $(".yselectBox.open").removeClass('open').hide();

        div.find(".action").addClass('hidden');
        div.removeClass('open');

    }

});

/*
 * Просмотр пароля
 */
$(document).on('click', '.showpass', function () {

    var $elm = $(this).siblings('input[data-id="password"]');
    var prop = $elm.prop('type');

    if (prop === 'password') $elm.prop('type', 'text');
    else $elm.prop('type', 'password');

    $(this).find('i').toggleClass('icon-eye icon-eye-off');

});

$(document).on('mouseleave', '.showpass', function () {

    var $elm = $(this).siblings('input[data-id="password"]');
    var prop = $elm.prop('type');

    if (prop === 'text') {

        $elm.prop('type', 'password');
        $(this).find('i').toggleClass('icon-eye-off icon-eye');

    }

});


/**
 * Открытие url в модальном окне. Используется для вызова форм
 * @param url
 * @returns {boolean}
 */
function doLoad(url) {

    var $dialog = $('#dialog');
    var $resultdiv = $('#resultdiv');
    var $container = $('#dialog_container');
    var $preloader = $('.dialog-preloader');

    $container.css('height', $(window).height());
    $dialog.css('width', '500px').css('height', 'unset').css('display', 'none');
    $container.css('display', 'block');
    //$preloader.center().css('display', 'block');

    $.ajax({
        type: "GET",
        url: url,
        success: function (data) {

            $resultdiv.empty().html(data);
            $preloader.css('display', 'none');

            $dialog.css('display', 'block').center();

            ShowModal.fire({
                etype: 'dialog',
                action: action
            });

        },
        statusCode: {
            404: function () {
                DClose();
                swal({
                    title: "Ошибка 404: Страница не найдена!",
                    type: "warning"
                });
            },
            500: function () {
                DClose();
                swal({
                    title: "Ошибка 500: Ошибка сервера!",
                    type: "error"
                });
            }
        }
    });

    return false;

}

/**
 * Закрытие модального окна
 * @constructor
 */
function DClose() {

    $('#subwindow').removeClass('open');

    $('#resultdiv').empty();
    $('#dialog_container').css('display', 'none');
    $('.dialog-preloader').css('display', 'none');
    $('#dialog').css({
        'display': 'none',
        'width': '500px',
        'position': 'absolute',
        'margin': 'unset'
    }).center();

    if (!isMobile) {

        //$('body').css('overflow-y','auto');

    }

}


/*
 * Функции для работы с COOKIE
 */
function getCookie(name) {
    var cookie = " " + document.cookie;
    var search = " " + name + "=";
    var setStr = null;
    var offset = 0;
    var end = 0;
    if (cookie.length > 0) {
        offset = cookie.indexOf(search);
        if (offset != -1) {
            offset += search.length;
            end = cookie.indexOf(";", offset)
            if (end == -1) {
                end = cookie.length;
            }
            setStr = unescape(cookie.substring(offset, end));
        }
    }
    return (setStr);
}

function setCookie(name, value, options) {
    options = options || {};

    var expires = options.expires;

    if (typeof expires == "number" && expires) {
        var d = new Date();
        d.setTime(d.getTime() + expires * 1000);
        expires = options.expires = d;
    }
    if (expires && expires.toUTCString) {
        options.expires = expires.toUTCString();
    }

    value = encodeURIComponent(value);

    var updatedCookie = name + "=" + value;

    for (var propName in options) {
        updatedCookie += "; " + propName;
        var propValue = options[propName];
        if (propValue !== true) {
            updatedCookie += "=" + propValue;
        }
    }

    document.cookie = updatedCookie;
}

function deleteCookie(name) {
    setCookie(name, "", {
        expires: -1
    })
}

// Обработчик кнопки выхода
$(document).on('click', '.exit-btn', function () {

    deleteCookie('session');

    window.location = 'login.php#logout';

    return false;

});

// Листинг фото
function configpage() {

    var user = getCookie('session');

    var search = $('#search').val();

    $('.photos').empty().load("backend/view/list.php?action=list&user=" + user + "&search=" + search).append('<img src="images/loading.svg">');


}

// Очистка поля поиска
function clrSearch() {

    $('#search').val('');

    configpage();

}

// Выбор изображения(checkbox)
$(document).on('change', '.selPhoto', function () {

    var i = 0;

    $.each($("input:checkbox:checked"), function () {

        ++i;

    });

    if (i > 0)
        $('.actions--photos').removeClass('hidden');
    else
        $('.actions--photos').addClass('hidden');


});


// Обработчик кнопки "Снять выделение"
$(document).on('click', '.unselectPhoto', function () {

    $('input:checkbox:checked').prop('checked', false);
    $('.actions--photos').addClass('hidden');

});

// Открытие фотографии
$(document).on('click', '.photo', function () {

    alert('хай');

});

// Удаление фото
$(document).on('click', '.deletePhoto', function () {

    var id = $(this).data('id');
    var url = 'backend/core/core.photos.php?action=delete&select=' + id;

    $('#message').empty().css('display', 'block').fadeTo(1, 1).append('<div id=loader><img src=/images/loading.svg> Выполняю...</div>');

    $.getJSON(url, function (data) {

        $('#message').css('display', 'block').fadeTo(1, 1).html(data.text);

        setTimeout(function () {
            $('#message').fadeTo(1000, 0);
        }, 1000);
        configpage();

    });

});

function Upload(){



}

