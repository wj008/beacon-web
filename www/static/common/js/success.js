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
    var interval = setInterval(function () {
        var time = --waitBtn.innerHTML;
        if (time <= 0) {
            window.clearInterval(interval);
            func();
        }
    }, 1000);
}

var backBtn = document.getElementById('back');

readyDialog(function (dlg) {
    var closeDlg = function () {
        dlg.success(window.data);
        dlg.close();
    }
    backBtn.href = 'javascript:;';
    backBtn.innerText = '关闭对话框';
    backBtn.onclick = closeDlg;
    timeOut(closeDlg);
    var width = window.parent.innerWidth;
    document.documentElement.className = 'dialog';
    document.body.className = 'dialog';
    dlg.layer.style(dlg.index, {width: 540, left: ((width - 540) / 2)});
    dlg.layer.iframeAuto(dlg.index);
}, function () {
    document.getElementById('box').style.marginTop = '100px';
    timeOut(function () {
        window.location.href = backBtn.href;
    });
});
