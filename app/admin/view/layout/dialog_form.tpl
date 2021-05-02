<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{block name='title'}{/block}</title>
    <link type="text/css" rel="stylesheet" href="/yeeui/css/yeeui.css"/>
    <link type="text/css" rel="stylesheet" href="/icofont/icofont.css"/>
    <link type="text/css" rel="stylesheet" href="/static/admin/css/dialog.css"/>
    <script src="/yeeui/third/jquery-3.3.1.min.js"></script>
    <script src="/yeeui/yee.js"></script>
    <script src="/static/admin/js/dialog.js"></script>
    {block name='header'}{/block}
</head>
<body>
<div class="yee-wrap yee-dialog scrollbar" style="height:calc(100% - 82px); overflow-y: auto;">
    {block name='form-content'}{/block}
</div>
{block name='footer'}{/block}
</body>
</html>