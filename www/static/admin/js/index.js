// JavaScript Document
var Timer = null;

var newpage = window.newpage = function (url, title) {
    new PageObject(url, title);
};

var PageObject = function (url, title) {
    var that = this, tag, frame, lable;
    this.url = url;
    this.title = title;
    //更新宽度==
    var update = function () {
        var items = $('#move-bar a');
        var barwidth = 0;
        items.each(function (index, element) {
            barwidth += $(element).outerWidth(true);
        });
        //预留300
        barwidth += 300;
        $('#move-bar').width(barwidth);
    };

    var setLast = function () {
        var scrollLeft = $('#move-bar').width() - $('#page-bar').width() - 290;
        if (scrollLeft > 0) {
            $('#page-bar').scrollLeft(scrollLeft);
        }
    };

    var meun = null;
    //创建==
    var create = (function () {
        var items = $('#move-bar a');
        items.removeClass('idx');
        tag = $('<a href="javascript:;" class="idx tag"><span class="text">正在打开...</span><i class="icofont icofont-close"></i></a>').appendTo('#move-bar');
        update();
        setLast();
        lable = tag.find('.text');
        tag[0].mypage = that;
        tag.on('click', function () {
            that.show();
            return false;
        });
        tag.on('mouseup', function (ev) {
            ev.stopPropagation();
            return false;
        });

        var yesdomain = false;
        tag.on('contextmenu', function (ev) {
            if (meun) {
                meun.remove();
            }
            meun = $('<div class="contextmenu">\
             <a class="opentag" href="javascript:;">新标签打开</a>\
             <a target="_blank" href="' + that.url + '">在浏览器标签打开</a>\
             <a class="reload"  href="javascript:;">重新加载(原连接)</a>\
             <hr/>\
             <a class="close_left" href="javascript:;">关闭左侧所有标签</a>\
             <a class="close_right" href="javascript:;">关闭右侧所有标签</a>\
             <a class="close_other" href="javascript:;">关闭其他标签</a>\
             <a class="close_self" href="javascript:;">关闭当前标签</a>\
            </div>').data('tag', tag).appendTo(document.body);
            if (yesdomain) {
                var relc = $('<a href="javascript:;">重新加载(当前页)</a>').insertBefore(meun.find('a.reload'));
                relc.one('click', function () {
                    var mtag = meun.data('tag');
                    if (mtag[0].mypage) {
                        mtag[0].mypage.reload(true);
                    }
                    meun.remove();
                    return false;
                });
            }
            meun.find('a.close_left').one('click', function () {
                var mtag = meun.data('tag');
                var seltags = mtag.prevAll('a.tag');
                seltags.each(function (idx, elem) {
                    if (elem.mypage) {
                        elem.mypage.remove();
                    }
                });
                meun.remove();
                return false;
            });
            meun.find('a.close_right').one('click', function () {
                var mtag = meun.data('tag');
                var seltags = mtag.nextAll('a.tag');
                seltags.each(function (idx, elem) {
                    if (elem.mypage) {
                        elem.mypage.remove();
                    }
                });
                meun.remove();
                return false;
            });

            meun.find('a.close_other').one('click', function () {
                var mtag = meun.data('tag');
                var seltags1 = mtag.prevAll('a.tag');
                seltags1.each(function (idx, elem) {
                    if (elem.mypage) {
                        elem.mypage.remove();
                    }
                });
                var seltags2 = mtag.nextAll('a.tag');
                seltags2.each(function (idx, elem) {
                    if (elem.mypage) {
                        elem.mypage.remove();
                    }
                });
                meun.remove();
                return false;
            });

            meun.find('a.close_self').one('click', function () {
                var mtag = meun.data('tag');
                if (mtag[0].mypage) {
                    mtag[0].mypage.remove();
                }
                meun.remove();
                return false;
            });

            meun.find('a.reload').one('click', function () {
                var mtag = meun.data('tag');
                if (mtag[0].mypage) {
                    mtag[0].mypage.reload();
                }
                meun.remove();
                return false;
            });

            meun.find('a.opentag').one('click', function () {
                var mtag = meun.data('tag');
                if (mtag[0].mypage) {
                    newpage(mtag[0].mypage.url, mtag[0].mypage.title);
                }
                meun.remove();
                return false;
            });

            meun.css({top: ev.clientY + 'px', left: ev.clientX + 'px'});
            meun.on('mouseup', function (ev) {
                ev.stopPropagation();
                return false;
            });
            $(document).one('mouseup', function () {
                meun.remove();
            });
            $(window).one('blur', function () {
                meun.remove();
            });
            return false;
        });

        tag.find('i').click(function () {
            that.remove();
            return false;
        });
        $('#content iframe').hide();
        frame = $('<iframe scrolling="auto" frameborder="0" width="100%" height="100%" style="display: none"></iframe>').appendTo('#content');
        frame.on('load', function () {
            frame.fadeIn(100, function () {
                frame.show();
            });
            try {
                var doc = this.contentWindow.document;
                yesdomain = true;
                lable.text(doc.title);
                $(this.contentWindow).on('unload', function () {
                    frame.hide();
                });
            } catch (e) {
                yesdomain = false;
                lable.text(title);
            }
            update();
        });
        frame.attr('src', url);
    })();

    this.show = function () {
        $('#move-bar a').removeClass('idx');
        $('#content iframe').hide();
        tag.addClass('idx');
        frame.show();
    };

    this.reload = function (cq) {
        $('#move-bar a').removeClass('idx');
        $('#content iframe').hide();
        tag.addClass('idx');
        if (cq) {
            try {
                frame[0].contentWindow.location.reload();
            } catch (e) {
                frame[0].src = frame[0].src;
            }
        } else {
            frame[0].src = frame[0].src;
        }
    };

    this.remove = function () {
        frame.remove();
        if (tag.mypage != null) {
            delete(tag.mypage);
            tag.mypage = null;
        }
        if (tag.is('.idx')) {
            var ptag = tag.prev('a');
            if (ptag.length > 0 && typeof (ptag[0].mypage) != 'undefined') {
                ptag[0].mypage.show();
            } else {
                ptag = tag.next('a');
                if (ptag.length > 0 && typeof (ptag[0].mypage) != 'undefined') {
                    ptag[0].mypage.show();
                }
            }
        }
        tag.remove();
        var iframes = $('#content iframe');
        if (iframes.length == 1) {
            iframes.show();
        }
        update();
    };
};


$(function () {

    var atops = $('#main-mune li').on('click', function () {
        atops.removeClass('idx');
        var that = $(this).addClass('idx');
        that.prev('li').addClass('lidx');
        var url = that.find('a').attr('href');
        if (url.length > 0 && url !== '#') {
            $.ajax({
                url: url, success: function (html) {
                    $('#left').html(html);
                }, cache: false
            });
        }
        return false;
    });
    atops.eq(0).trigger('click');

    $('#left').on('click', 'a[target="main"]', function () {
        $('#left a[target="main"]').removeClass('active');
        var that = $(this).addClass('active');
        var url = that.attr('href');
        var text = that.text();
        var items = $('#move-bar a.tag');
        var has = false;
        items.each(function (idx, elem) {
            if (elem.mypage && elem.mypage.url == url) {
                elem.mypage.reload(false);
                has = true;
                return false;
            }
        });
        has || newpage(url, text);
        return false;
    });

    $('#move-left').on('mousedown', function () {
        if (Timer != null) {
            window.clearInterval(Timer);
            Timer = null;
        }
        var bar_area = $('#page-bar');
        Timer = window.setInterval(function () {
            var L = bar_area.scrollLeft();
            if (L <= 0)
                return;
            L -= 15;
            L = L < 0 ? 0 : L;
            bar_area.scrollLeft(L);
        }, 20);
        $(document).one('mouseup', function () {
            if (Timer != null) {
                window.clearInterval(Timer);
                Timer = null;
            }
        });
    });

    $('#move-right').on('mousedown', function () {
        if (Timer != null) {
            window.clearInterval(Timer);
            Timer = null;
        }
        var bar_area = $('#page-bar');
        var mL = $('#movebar').width() - bar_area.width() - 490;
        if (mL <= 0)
            return;
        Timer = window.setInterval(function () {
            var L = bar_area.scrollLeft();
            if (L >= mL)
                return;
            L += 15;
            L = L > mL ? mL : L;
            bar_area.scrollLeft(L);
        }, 20);
        $(document).one('mouseup', function () {
            if (Timer != null) {
                window.clearInterval(Timer);
                Timer = null;
            }
        });
    });

    $('#close-all').on('click', function () {
        if (!confirm("确定要关闭所有标签页面吗？"))
            return;
        $('#move-bar a').each(function (index, element) {
            if (typeof (element.mypage) != 'undefined') {
                element.mypage.remove();
            }
        });
    });

    $('#left').on('click', 'dt', function () {
        var icon = $(this).find('i');
        if (icon.is('.folder-open')) {
            icon.removeClass('folder-open').addClass('folder-close');
            $(this).siblings('dd').hide();
            $(this).parent('dl').css('padding-bottom', '0px');
            $(this).removeClass('item_open').addClass('item_close');
        } else {
            icon.removeClass('folder-close').addClass('folder-open');
            $(this).siblings('dd').show();
            $(this).parent('dl').css('padding-bottom', '5px');
            $(this).removeClass('item_close').addClass('item_open');
        }
    });
});