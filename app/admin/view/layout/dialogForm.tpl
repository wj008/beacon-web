<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{block name='title'}{/block}</title>
    <link type="text/css" rel="stylesheet" href="/yeeui/css/yeeui.css"/>
    <link type="text/css" rel="stylesheet" href="/icofont/icofont.css"/>
    <script src="/yeeui/third/jquery-3.3.1.min.js"></script>
    <script src="/yeeui/yee.js"></script>
    {block name='header'}{/block}
</head>
{literal}
    <style>
        html, body {
            background: #fff;
            height: 100%;
        }

        .yee-submit {
            padding: 15px 0 15px 0;
            text-align: center;
            display: block;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            border-top: 1px #eee solid;
        }

        .yee-submit .submit-label, .yee-submit .submit-cell {
            display: inline;
        }

        .yee-row .row-label {
            width: 150px;
            text-align: right;
            vertical-align: top;
        }

        .yee-panel {
            padding: 0;
        }
    </style>
{/literal}
<body>
<div class="yee-wrap yee-dialog scrollbar" style="height:calc(100% - 82px); overflow-y: auto;">
    {block name='form-content'}{/block}
</div>
{block name='footer'}{/block}
{literal}
    <script>
        $(function () {
            var form = $('form:first');
            form.on('fail', function (ev, ret) {
                if (ret.confirm) {
                    Yee.confirm(ret.confirm, function (idx) {
                        var confirmInp = $(':input#confirm');
                        if (confirmInp.length == 0) {
                            confirmInp = $('<input type="hidden" id="confirm" name="confirm"/>').appendTo(form);
                            console.log('add_confirm');
                        }
                        confirmInp.val(1);
                        setTimeout(function () {
                            console.log('submit');
                            form.submit();
                            Yee.close(idx);
                        }, 50);
                    });
                    return false;
                }
                return true;
            });
        });
        Yee.readyDialog(function (dlg) {
            $('form').on('success', function (ev, ret) {
                window.top.postMessage('task-notice', '*');
                dlg.success(ret);
                dlg.close();
                return false;
            });
        });
    </script>
{/literal}
</body>
</html>