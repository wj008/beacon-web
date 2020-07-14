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
{literal}
    <style>
        html, body {
            background: #fff;
            height: 100%;
        }

        .yee-panel {
            padding: 0;
        }

        .yee-wrap.yee-dialog {
            padding: 20px;
        }
    </style>
{/literal}
<body class="scrollbar" style=" overflow-y: auto;">
<div class="yee-wrap yee-dialog scrollbar">
    {block name='form-content'}{/block}
</div>
{block name='footer'}{/block}
</body>
</html>