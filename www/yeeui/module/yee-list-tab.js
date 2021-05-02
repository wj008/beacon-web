Yee.define('list-tab', 'ul', function (element) {
    var qem = $(element);
    var lis = qem.find('li');
    lis.each(function (idx, el) {
        var a = $(el).find('a');
        var tabIndex = a.data('tab-index') || null;
        if (tabIndex !== null) {
            var href = a.attr('href');
            var aInfo = Yee.parseUrl(href);
            aInfo.param['tabIndex'] = tabIndex;
            href = Yee.toUrl(aInfo);
            a.attr('href', href);
        }
    });
});