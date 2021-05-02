Yee.define('progress', ':input', function (element) {
    var qel = $(element);
    var barWarp = $('<div style="position: relative; vertical-align: top"></div>').insertBefore(qel);
    barWarp.css({width: 0, height: 0});
    var bar = $('<div style="position:absolute"></div>').appendTo(barWarp);
    bar.hide();
    var load = $('<div></div>').appendTo(bar);
    load.css({width: '0%', height: 3, background: "#16b444"});
    qel.on('uploadBefore', function () {
        bar.css({
            width: qel.outerWidth(),
            height: 3,
            'border-radius': '5px',
            'opacity': '0.8',
            background: "#cccccc",
            top: qel.outerHeight(),
            left: 0
        });
        bar.show();
    });
    qel.on('uploadProgress', function (ev, data) {
        load.css('width', data[0].percent + '%');
    });
    qel.on('uploadComplete', function () {
        bar.hide();
        load.css({width: '0%'});
    });
});
