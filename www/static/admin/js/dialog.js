$(function () {
    var form = $('form:first');
    form.on('fail', function (ev, ret) {
        if (ret.confirm) {
            Yee.confirm(ret.confirm, function (idx) {
                var confirmInp = $(':input#confirm');
                if (confirmInp.length == 0) {
                    confirmInp = $('<input type="hidden" id="confirm" name="confirm"/>').appendTo(form);
                }
                confirmInp.val(1);
                setTimeout(function () {
                    form.submit();
                    Yee.close(idx);
                }, 50);
            });
            return false;
        }
        return true;
    });
    Yee.readyDialog(function (dlg) {
        $('form').on('success', function (ev, ret) {
            dlg.success(ret);
            dlg.close();
            return false;
        });
    });
});
