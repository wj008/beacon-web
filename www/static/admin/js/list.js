$(function () {

    var timer = null;

    function updataHeight() {
        var winH = $(window).height();
        var headH = $('.yee-list-header').outerHeight(true) || 0;
        var searchH = $('.yee-list-search').outerHeight(true) || 0;
        var barH = $('.yee-pagebar').outerHeight(true) || 0;
        var tabH = $('div.yee-tab').outerHeight(true) || 0;
        var grouH = $('div.group-inc').outerHeight(true) || 0;
        var attentionH = $('div.yee-attention').outerHeight(true) || 0;
        var myH = winH - headH - searchH - barH - tabH - grouH - attentionH - 50;
        // $('div.yee-datatable-layer').height(myH);
        $('#list').emit('setHeight', myH);
    }

    $("#list").on("render", function (ev, source) {
        if (source && source["pageInfo"]) {
            $("#records-count").text(source["pageInfo"]["recordsCount"] || "0");
        }
        updataHeight();
    });
    $(window).on('resize', function () {
        updataHeight();
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