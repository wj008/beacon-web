$(function () {

    if (window.openShower == void 0) {
        window.openShower = function (url, urlList) {
            if (window.top != window && window.top.openShower) {
                window.top.openShower(url, urlList);
                return;
            }
            showImage(url, urlList);
            return;
        }

        function createImage(url, width, height) {
            var def = $.Deferred();
            var img = new Image();
            img.onload = function () {
                var w = img.width, h = img.height;
                if (w > width) {
                    h = h * (width / w);
                    w = width;
                }
                if (h > height) {
                    w = w * (height / h);
                    h = height;
                }
                var qel = $(img).css({width: Math.round(w), height: Math.round(h)});
                def.resolve(qel);
            };
            img.src = url;
            return def;
        }

        function showImage(url, urlList) {
            var useGroup = false;
            var index = -1;
            var length = urlList.length;
            if (length > 0) {
                index = $.inArray(url, urlList);
            }
            if (length > 1 && index >= 0) {
                useGroup = true;
            }
            var bg = $('<div class="lightbox-overlay" style="display: block;"></div>').height($(document).height()).appendTo(document.body);
            var show = $('<div class="lightbox-shower"></div>').appendTo(document.body);
            var image = $('<div class="lightbox-image-shower"></div>').appendTo(show);
            var leftBtn = null;
            var rightBtn = null;
            var numberLabel = null;
            var nextImage = function () {
                if (!useGroup) {
                    return;
                }
                index += 1;
                if (index >= length) {
                    index = length - 1;
                }
                url = urlList[index];
                showDeg(0);
            };
            var prevImage = function () {
                if (!useGroup) {
                    return;
                }
                index -= 1;
                if (index < 0) {
                    index = 0;
                }
                url = urlList[index];
                showDeg(0);
            };
            var remove = function () {
                bg.remove();
                show.remove();
                toolbar.remove();
                if (useGroup) {
                    leftBtn.remove();
                    rightBtn.remove();
                    numberLabel.remove();
                    $(window).off('keyup', keyFunc);
                }
            };
            var keyFunc = function (ev) {
                if (ev.key == 'ArrowRight') {
                    nextImage();
                }
                if (ev.key == 'ArrowLeft') {
                    prevImage();
                }
                if (ev.key == 'Escape') {
                    remove();
                }
            };

            bg.on('click', function () {
                remove();
            });
            var showDeg = function (deg) {
                var winH = $(window).height() - 240;
                var winW = $(window).width() - 200;
                image.empty();
                if (deg % 180 == 0) {
                    createImage(url, winW, winH).then(function (img) {
                        image.append(img);
                    });
                } else {
                    createImage(url, winH, winW).then(function (img) {
                        image.append(img);
                    });
                }
                image.css('transform', 'rotate(' + deg + 'deg)');
                if (useGroup) {
                    numberLabel.text((index + 1) + '/' + length);
                }
            }

            var currDeg = 0;
            var toolbar = $('<div class="image-toolbar">' +
                '<a href="javascript:;" class="open" target="_blank" title="新窗口打开"></a>' +
                '<a href="javascript:;" class="rotate1" title="逆时间旋转"></a>' +
                '<a href="javascript:;"  class="rotate2" title="正时间旋转"></a>' +
                '<a class="close" href="javascript:;" title="关闭"></a>' +
                '</div>').appendTo(document.body);

            if (useGroup) {
                leftBtn = $('<div class="image-left-btn"></div>').appendTo(document.body);
                rightBtn = $('<div class="image-right-btn"></div>').appendTo(document.body);
                leftBtn.on('click', prevImage);
                rightBtn.on('click', nextImage);
                numberLabel = $('<div class="image-number"></div>').appendTo(document.body);
                $(window).on('keyup', keyFunc);
            }
            toolbar.find('a.open').attr('href', url);
            toolbar.find('a.rotate1').on('click', function () {
                currDeg -= 90;
                if (currDeg < 0) {
                    currDeg += 360;
                }
                showDeg(currDeg);
            });
            toolbar.find('a.rotate2').on('click', function () {
                currDeg += 90;
                if (currDeg > 360) {
                    currDeg -= 360;
                }
                showDeg(currDeg);
            });
            toolbar.find('a.close').on('click', function () {
                remove();
            });
            showDeg(0);
            $(window).on('resize', function () {
                bg.height($(document).height());
                showDeg(currDeg);
            });
        }

        var initImg = function () {
            var img = $(this);
            var imgParent = img.parents('.image-group:first');
            var urlList = [];
            if (imgParent.length) {
                imgParent.find('a.image-show,img.image-show,div.image-show img').each(function (idx, imgElem) {
                    var it = $(imgElem);
                    var src = null;
                    if (it.is('a')) {
                        src = $(imgElem).data('img');
                    } else {
                        src = $(imgElem).attr('src');
                    }
                    urlList.push(src);
                });
            }
            var url = null;
            if (img.is('a')) {
                url = img.data('img');
            } else {
                url = img.attr('src');
            }
            window.openShower(url, urlList);
        }
        $(document).on('click', 'img.image-show', initImg);
        $(document).on('click', 'div.image-show img', initImg);
        $(document).on('click', 'a.image-show', initImg);
    }
});