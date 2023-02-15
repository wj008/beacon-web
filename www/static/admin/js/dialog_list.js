$(function () {


    $("#list").on("render", function (ev, source) {
        if (source && source["pageInfo"]) {
            $("#records-count").text(source["pageInfo"]["recordsCount"] || "0");
        }
    });

    $(window).on('parent-reload', function () {
        Yee.readyDialog(function (dlg) {
            dlg.success();
        });
    });

    $("#list").on("order", function (ev, data) {
        var form = $("#searchForm"), inp1 = form.find(":input[name=sort]");
        if (inp1.length == 0) {
            inp1 = $('<input type="hidden" name="sort"/>').appendTo(form);
        }
        inp1.val(data.name + "-" + (data.order == 1 ? "asc" : "desc"));
        $("#searchForm").submit();
    });

    var shower = null;
    $(document.body).on('mousedown', '.yee-btn-more a.yee-btn', function (ev) {
        var btn = $(this);
        $('.yee-btn-menu').hide();
        var parent = btn.parents('.yee-btn-warp:first');
        var offset = parent.offset();

        var menu = parent.find('.yee-btn-menu');

        var translateY = -$(document).scrollTop();
        menu.show();
        var arrow = menu.find('span.arrow');
        var docH = $(document).height() - 60;
        var menuH = menu.outerHeight();
        var menuW = menu.width();
        var top = offset.top;
        if (menuH + top > docH) {
            top = docH - menuH;
        }
        menuW = menuW + 30;
        //console.log(menuW);
        var arrowTop = 10 + (offset.top - top);
        arrow.css('top', arrowTop + 'px');
        menu.css({top: top, left: offset.left - menuW});
        menu.css({transform: 'translateY(' + translateY + 'px)'});
        shower = menu;
        ev.stopPropagation();
        return false;
    });
    $(document.body).on('mousedown', 'div.yee-btn-menu', function (ev) {
        ev.stopPropagation();
        return false;
    });

    $(document).on('mousedown', function () {
        $('.yee-btn-menu').hide();
        shower = null;
    });

    $(window).on('blur', function () {
        $('.yee-btn-menu').hide();
        shower = null;
    });

    $(window).scroll(function () {
        if (shower) {
            var translateY = -$(document).scrollTop();
            shower.css({transform: 'translateY(' + translateY + 'px)'});
        }
    });
});