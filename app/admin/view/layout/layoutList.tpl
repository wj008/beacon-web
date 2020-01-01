<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{block name="title"}{/block}</title>
    <link type="text/css" rel="stylesheet" href="/yeeui/css/yeeui.css"/>
    <link type="text/css" rel="stylesheet" href="/icofont/icofont.css"/>
    <script src="/yeeui/third/jquery-3.3.1.min.js"></script>
    <script src="/yeeui/yee.js"></script>
    {block name='header'}{/block}
</head>
<body>
<div class="yee-wrap">
    {block name='list-header'}{/block}
    {block name='list-tab'}{/block}
    {block name='list-attention'}{/block}
    {block name='list-search'}{/block}
    <div class="yee-list">
        {block name='list-table'}{/block}
        {block name='list-pagebar'}{/block}
    </div>
    {block name='list-information'}{/block}
</div>
{block name='footer'}{/block}
<script src="/static/admin/js/list.js"></script>
</body>
</html>