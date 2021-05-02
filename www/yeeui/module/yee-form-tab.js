Yee.define('form-tab', 'ul', function (element) {
    var qel = $(element);
    var currCss = qel.data('currCss') || 'curr';
    var binds = [];
    var lis = qel.find('li');
    var allBinds = [];

    lis.each(function (idx, el) {
        var name = $(el).data('bind-name');
        allBinds.push('div[name="' + name + '"]:first');
        var bind = $('div[name="' + name + '"]').data('idx', idx);
        if (bind.length > 0) {
            binds.push({name: name, elem: bind});
        }
    });

    lis.on('click', function () {
        var that = $(this);
        var name = that.data('bind-name');
        $(binds).each(function (idx, item) {
            if (item.name == name) {
                item.elem.show();
            } else {
                item.elem.hide();
            }
        });
        lis.not(that).removeClass(currCss);
        that.addClass(currCss);
        $(window).triggerHandler('resize');
    });

    $('form').on('displayAllError', function (e, items) {
        $(items).each(function () {
            var div = this.elem.parents(allBinds.join(','));
            if (div.length > 0) {
                var idx = div.data('idx');
                lis.eq(idx).trigger('click');
            }
            return false;
        });
    });
    lis.first().trigger('click');
});