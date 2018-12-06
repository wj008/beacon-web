<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>错误提示</title>
    {literal}
        <style type="text/css">
            body, html {
                padding: 0;
                margin: 0;
                background: #fff;
                font-family: '微软雅黑';
                color: #333;
                font-size: 16px
            }

            .message {
                padding: 0;
                margin: 80px auto 100px auto;
            }
        </style>
    {/literal}
</head>
<body>
<div class="message">
    <p style="line-height: 1.8em;font-size: 28px;text-align: center;">{$info.msg}</p>
    {if !empty($info.dialog) && $info.dialog=='close'}
        <p style="padding-top: 10px; text-align: center;">页面自动 <a style="color: #333" id="back" href="javascript:closeDialog();">关闭</a> 等待时间： <b id="wait">3</b></p>
        <input type="hidden" id="data" value="{json_encode($info)}">
    {literal}
        <script type="text/javascript">
            var dialog = null;

            function closeDialog() {
                clearInterval(interval);
                var data = document.getElementById('data').value || null;
                if (dialog && data) {
                    data = JSON.parse(data);
                    dialog.fail(data);
                    dialog.close();
                }
            }

            //重新设置高宽
            var time = 0;
            var dlgInterval = setInterval(function () {
                time++;
                if (time > 2000) {
                    clearInterval(dlgInterval);
                }
                if (window['dialogHandle']) {
                    dialog = window['dialogHandle'];
                    clearInterval(dlgInterval);
                    var width = window.parent.innerWidth;
                    dialog.layer.style(dialog.index, {width: 500, left: ((width - 500) / 2)});
                    dialog.layer.iframeAuto(dialog.index);
                }
            }, 1);

            var wait = document.getElementById('wait');
            var interval = setInterval(function () {
                var time = --wait.innerHTML;
                if (time <= 0) {
                    closeDialog();
                }
            }, 1000);
        </script>
    {/literal}
    {elseif !empty($info.back)}
        <p style="padding-top: 10px;padding-left: 30px">页面自动 <a style="color: #333" id="back" href="{$info.back}">跳转</a> 等待时间： <b id="wait">3</b></p>
    {literal}
        <script type="text/javascript">
            var wait = document.getElementById('wait'), back = document.getElementById('back').href;
            var interval = setInterval(function () {
                var time = --wait.innerHTML;
                if (time <= 0) {
                    clearInterval(interval);
                    location.href = back;
                }
            }, 1000);
        </script>
    {/literal}
    {/if}
</div>
</body>
</html>