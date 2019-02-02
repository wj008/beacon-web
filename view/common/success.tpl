<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>操作成功提示</title>
    {literal}
        <style type="text/css">
            body, html {padding: 0;margin: 0;background: #fff;font-family: '微软雅黑';color: #666;font-size: 14px}
            .box {padding: 0;width: 500px;margin: 40px auto 60px auto;}
            .msg {line-height: 26px;font-size: 18px; padding: 0 20px;text-align: left;position: relative;}
            .msg p { display: inline-block; padding-left: 50px;}
            .face {position: absolute;top: 8px;transform: rotate(90deg); color: #27d073; text-align: center; width: 42px; height: 42px; line-height: 35px; display: inline-block; font-size: 36px;}
            .auto-back {padding-top: 10px; text-align: center;}
            .auto-back a {color: #3da9ee;}
        </style>
    {/literal}
</head>
<body>
<div id="box" class="box">
    <div class="msg"><b class="face">:)</b>
        <p>{$info.msg}</p></div>
    {if !empty($info.back)}
        <p class="auto-back">页面自动 <a id="back" href="{$info.back}">跳转</a> 等待时间： <b id="wait">{$info.timeout|default:1}</b></p>
    {else}
        <p class="auto-back">页面自动 <a id="back" id="back" href="javascript:;">关闭</a> 等待时间： <b id="wait">{$info.timeout|default:1}</b></p>
    {/if}
    <script type="text/javascript">var data ={json_encode($info)|raw};</script>
    {literal}
        <script type="text/javascript">
            var dialog = null;

            function readyDialog(func) {
                if (dialog) {
                    return func(dialog);
                }
                var time = 0;
                var dlgInterval = setInterval(function () {
                    time++;
                    if (time > 2000) {
                        window.clearInterval(dlgInterval);
                    }
                    if (window['dialogHandle']) {
                        window.clearInterval(dlgInterval);
                        dialog = window['dialogHandle'];
                        return func(dialog);
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
            var closeDlg = function () {
                dialog.success(data);
                dialog.close();
            }
            if (window.location.hash == '#dialog') {
                readyDialog(function (dlg) {
                    backBtn.href = 'javascript:;';
                    backBtn.innerText = '关闭对话框';
                    backBtn.onclick = closeDlg;
                    timeOut(closeDlg);
                    var width = window.parent.innerWidth;
                    dlg.layer.style(dlg.index, {width: 540, left: ((width - 540) / 2)});
                    dlg.layer.iframeAuto(dlg.index);
                });
            } else {
                document.getElementById('box').style.marginTop = '100px';
                timeOut(function () {
                    window.location.href = backBtn.href;
                });
            }
        </script>
    {/literal}
</div>
</body>
</html>