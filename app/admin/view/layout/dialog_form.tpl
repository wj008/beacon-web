<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{block name='title'}{/block}</title>
    <link type="text/css" rel="stylesheet" href="/yeeui/css/yeeui.css"/>
    <link type="text/css" rel="stylesheet" href="/static/admin/css/dialog.css"/>
    <link type="text/css" rel="stylesheet" href="/icofont/icofont.css"/>
    <script src="/yeeui/third/jquery-3.3.1.min.js"></script>
    <script src="/yeeui/yee.js?v=2.0.3"></script>
    <script src="/static/admin/js/dialog.js"></script>
    {block name='header'}{/block}
</head>
<body>
<div class="yee-wrap yee-dialog scrollbar" style="height:calc(100% - 82px); overflow-y: auto;">
    {block name='form-content'}{/block}
    <div style="clear: both;margin-bottom: 20px"></div>
</div>
{block name='footer'}{/block}
</body>
</html>