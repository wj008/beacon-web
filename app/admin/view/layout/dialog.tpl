<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{block name='title'}{/block}</title>
    <link type="text/css" rel="stylesheet" href="/yeeui/css2/yeeui.css"/>
    <link type="text/css" rel="stylesheet" href="/icofont/icofont.css"/>
    <link type="text/css" rel="stylesheet" href="/yeeui/css2/dialog.css"/>
    <script src="/yeeui/third/jquery-3.3.1.min.js"></script>
    <script src="/yeeui/yee.js?v=2.0.3"></script>
    {block name='header'}{/block}
</head>
<body class="scrollbar" style="overflow-y: auto;display: flex;flex-flow: row nowrap;background:#f3f3f3;">
<div class="yee-wrap yee-dialog scrollbar" style="overflow-y: auto;flex-grow: 1">
    {block name="wrapper"}{/block}
</div>
{block name='footer'}{/block}
{literal}
    <script>
        $(window).on('success', function () {
            setTimeout(function () {
                window.location.reload();
            }, 500);
        });
    </script>
{/literal}
</body>
</html>