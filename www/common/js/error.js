var backBtn = document.getElementById('back');

function readyDialog(func1, func2) {
    var time = 0;
    var dlgInterval = setInterval(function () {
        time++;
        if (time > 300) {
            window.clearInterval(dlgInterval);
            return func2();
        }
        if (window['dialogHandle']) {
            window.clearInterval(dlgInterval);
            return func1(window['dialogHandle']);
        }
    }, 1);
}

function timeOut(func) {
    var waitBtn = document.getElementById('wait');
    if (waitBtn) {
        var wait = waitBtn.innerHTML;
        wait = parseInt(wait);
        if (isNaN(wait)) {
            wait = 5;
        }
        var interval = setInterval(function () {
            var time = --wait;
            waitBtn.innerHTML = wait;
            if (time <= 0) {
                window.clearInterval(interval);
                func();
            }
        }, 1000);
    }
}

readyDialog(function (dlg) {
    var closeDlg = function () {
        if (window.data !== void 0) {
            dlg.fail(window.data);
        }
        dlg.close();
    }
    if (backBtn) {
        backBtn.href = 'javascript:;';
        backBtn.innerText = '关闭对话框';
        backBtn.onclick = closeDlg;
    }
    timeOut(closeDlg);
    var width = window.parent.innerWidth;
    document.documentElement.className = 'dialog';
    document.body.className = 'dialog';
    document.getElementById('error-main').style.width = '500px';
    dlg.layer.style(dlg.index, {width: 600, left: ((width - 600) / 2), top: 200});
    dlg.layer.iframeAuto(dlg.index);
}, function () {
    timeOut(function () {
        window.location.href = backBtn.href;
    });
    var nTimer = window.setInterval(function () {
        var width = document.body.clientWidth;
        if (width > 800) {
            document.getElementById('error-main').style.width = '800px';
        }
        if (width > 0) {
            window.clearInterval(nTimer);
        }
    }, 20);
});
