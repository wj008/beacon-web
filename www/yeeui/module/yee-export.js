Yee.define('export', 'a', function (element) {
    var qel = $(element);
    var selForm = qel.data('form') || '#searchForm';
    var form = $(selForm);
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