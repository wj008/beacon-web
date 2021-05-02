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
<body>

<div class="yee-form-header">
    {block name='form-header'}{/block}
</div>

<div class="yee-wrap">
    {block name='form-content'}{/block}
</div>

{block name='footer'}{/block}

</body>
</html>