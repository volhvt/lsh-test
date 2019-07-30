if (window['loadFirebugConsole']) {
    window.loadFirebugConsole();
} else if (!window['console']) {

    (function () {
        var method;
        var noop = function () {
        };
        var methods = [
            'assert', 'clear', 'count', 'debug', 'dir', 'dirxml', 'error',
            'exception', 'group', 'groupCollapsed', 'groupEnd', 'info', 'log',
            'markTimeline', 'profile', 'profileEnd', 'table', 'time', 'timeEnd',
            'timeline', 'timelineEnd', 'timeStamp', 'trace', 'warn'
        ];
        var length = methods.length;
        var console = (window.console = window.console || {});

        while (length--) {
            method = methods[length];

            if (!console[method]) {
                console[method] = noop;
            }
        }
    }());

}

Number.prototype.complitedZero = function (len) {
    if (typeof(len) === 'undefined' || isNaN(parseInt(len))) {
        len = 5;
    }
    len = parseInt(len);
    var str = this.toString();
    while (str.length < len) {
        str = '0' + str;
    }
    return str;
};
Number.prototype.formatMoney = function (c, d, t) {
    var n = this,
        c = isNaN(c = Math.abs(c)) ? 2 : c,
        d = (typeof(d) === 'undefined') ? "." : d,
        t = (typeof(t) === 'undefined') ? "," : t,
        s = n < 0 ? "-" : "",
        i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "",
        j = (j = i.length) > 3 ? j % 3 : 0;
    return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
};
String.prototype.normalizeInt = function () {
    var val = parseInt(this.replace(/^0*/g, ''));
    if (isNaN(val)) {
        val = 0;
    }
    return val;
};
String.prototype.normalizeFloat = function () {
    var val = parseFloat(this.replace(/^0*/g, ''));
    if (isNaN(val)) {
        val = 0.00;
    }
    return val;
};
String.prototype.toInt = function () {
    var i = parseInt(this.replace(/[ ]+/g, "").replace(/[,]+/g, "."));
    if (isNaN(i)) {
        i = 0;
    }
    return i;
};
String.prototype.toFloat = function () {
    var i = parseFloat(this.replace(/[ ]+/g, "").replace(/[,]+/g, "."));
    if (isNaN(i)) {
        i = 0.00;
    }
    return i;
};
String.prototype.queryToObject = function () {
    var ar = this.split('&');
    var values = {};
    for (var i in ar) {
        var item = ar[i].split('=');
        values[item[0]] = item[1];
    }
    return values;
};
String.prototype.formatMoney = function (c, d, t) {
    return Number(this).formatMoney(c, d, t);
};
String.prototype.normalizeId = function () {
    var pattern = /\?| |\/|=|&/g;
    return this.replace(pattern, '_');
};

if (typeof(entityAction) === 'undefined') {
    var entityAction = '';
}
if (typeof(entityWrapper) === 'undefined') {
    var entityWrapper = '';
}
if (typeof(dlgs) === 'undefined') {
    var dlgs = {};
}

var successToast = function (message, options) {
    $('.toast-success .toast-body').html(message);
    $('.toast-success').toast('show', options);
};
var errorToast = function (message, options) {
    $('.toast-error .toast-body').html(message);
    $('.toast-error').toast('show', options);
};
var initDialog = function (select) {
    var $dlg = $(select);
    $dlg.remove();
    $('body').append($dlg);
    return $dlg;
};
var getDlg = function (name) {
    return $(dlgs[name]);
};
var setDlg = function (name, $dlg) {
    dlgs[name] = $dlg;
};
var closeDialog = function (name) {
    getDlg(name).modal('hide');
};
var htmlDialog = function (name, message) {
    getDlg(name).find('.modal-body').html(message);
};
var openDialog = function (name, options) {
    var _options = {dismissible: false};
    $.extend(_options, options);
    getDlg(name).modal('show');
};

var bindDialogForm = function (dialogName) {
    getDlg(dialogName)
        .off('submit', '.modal-body')
        .on('submit', '.modal-body', function () {
            try {
                var $form = $(this).find('form');
                var data = $form.serializeArray();
                var request = {};

                $.map(data, function (n, i) {
                    request[n['name']] = n['value'];
                });
                $.ajax({
                    url: $form.attr('action'),
                    method: 'post',
                    dataType: 'json',
                    data: request
                }).done(function (response) {

                    if (response.status == 'success') {
                        var $item = $(response.content);
                        if (entityAction == 'create') {
                            //
                            $(entityWrapper).append($item);
                        } else if (entityAction == 'update') {
                            $(entityWrapper).find('#' + $item.attr('id')).replaceWith($item);
                        }
                        entityAction = '';
                        entityWrapper = '';

                        closeDialog(dialogName);
                        successToast(response.message, {delay: 5000});

                    } else {
                        //Materialize.toast(response.message, 5000, 'red lighten-1');
                        var errorsStr = '';
                        if (typeof(response.errors) !== 'undefined'
                            && !$.isEmptyObject(response.errors)
                            || ($.isArray(response.errors) && response.errors.length > 0)
                        ) {
                            for (var k in response.errors) {
                                if ($.isArray(response.errors[k])) {
                                    for (var n in response.errors[k]) {
                                        errorsStr += '<p>' + response.errors[k][n] + '</p>';
                                    }
                                } else {
                                    errorsStr += '<p>' + response.errors[k] + '</p>';
                                }
                            }
                        }
                        errorToast(errorsStr, {delay: 5000});
                    }


                }).fail(function () {

                });

            } catch (e) {
                console.log(e);
            }
            return false;
        });
};

function _entity_create(url, params) {
    $.ajax({
        type: "POST",
        url: url,
    }).done(function (response) {
        if (response.status == 'success') {
            entityAction = 'create';
            entityWrapper = '#' + params['wrapperId'];
            htmlDialog(params['dialogName'], response.content);
            openDialog(params['dialogName'], params['dialogOptions']);
        } else {
            //Materialize.toast(response.message, 5000, (  ? 'red' : 'green') + ' lighten-1');
            if (response.status == 'error') {
                errorToast(response.message, {delay: 5000});
            } else {
                successToast(response.message, {delay: 5000});
            }
        }
    }).fail(function (jqXHR, textStatus, errorThrown) {
        console.log(arguments);
        errorToast('Ошибка при обращении к серверу[' + textStatus + ' ->> ' + errorThrown + ']', {delay: 5000});
    });
}

function _entity_edit(url, params) {
    $.ajax(
        {
            type: "POST",
            url: url,
            data: {},
        }
    ).done(function (response) {
        if (response.status == 'success') {
            entityAction = 'update';
            entityWrapper = '#' + params['wrapperId'];
            htmlDialog(params['dialogName'], response.content);
            openDialog(params['dialogName'], params['dialogOptions']);
        } else {
            if (response.status == 'error') {
                errorToast(response.message, {delay: 5000});
            } else {
                successToast(response.message, {delay: 5000});
            }
        }
    }).fail(function (jqXHR, textStatus, errorThrown) {
        console.log(arguments);
        errorToast('Ошибка при обращении к серверу[' + textStatus + ' ->> ' + errorThrown + ']', {delay: 5000});
    });
}

function _entity_delete(url, params) {
    if (confirm("Вы уверены, что хотите удалить?")) {
        $.ajax({
            type: "POST",
            url: url,
            data: {},
        }).done(function (response) {
            if (response.status == 'success') {
                $("#" + params['rowId']).remove();
            }
            if (response.status == 'error') {
                errorToast(response.message, {delay: 5000});
            } else {
                successToast(response.message, {delay: 5000});
            }
        }).fail(function (jqXHR, textStatus, errorThrown) {
            console.log(arguments);
            errorToast('Ошибка при обращении к серверу[' + textStatus + ' ->> ' + errorThrown + ']', {delay: 5000});
        });
    }
}

$('.toast').toast({delay: 5000});

//(function($){
//})(jQuery);