(function ($, Yee) {
    Yee.extend('export', 'a', function (elem, setting) {
        var qel = $(elem);
        var form = $(setting.form || '#searchForm');
        var href = qel.attr('href');
        qel.on('click', function () {
            var sendData = '';
            if (form.length > 0) {
                sendData = form.serialize();
            }
            var url = href + '?' + sendData;
            qel.attr('href', url);
        });
    });
})(jQuery, Yee);