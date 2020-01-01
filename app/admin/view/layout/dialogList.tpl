<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{block name="title"}{/block}</title>
    <link type="text/css" rel="stylesheet" href="/yeeui/css/yeeui.css"/>
    <link type="text/css" rel="stylesheet" href="/icofont/icofont.css"/>
    <link type="text/css" rel="stylesheet" href="/static/home/css/common.css"/>
    <link type="text/css" rel="stylesheet" href="/static/home/css/style.css"/>
    <script src="/yeeui/third/jquery-3.3.1.min.js"></script>
    <script src="/yeeui/yee.js"></script>
    {block name='header'}{/block}
    {literal}
        <style>
            html, body {
                background: #fff;
            }

            .yee-wrap .yee-list-search {
                box-shadow: none;
            }

            .yee-datatable-layer {
                box-shadow: none;
                border-bottom: 1px #eee solid;
            }
        </style>
    {/literal}
</head>

<body class="scrollbar">
<div class="yee-wrap yee-dialog">
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
<script src="/static/home/js/list.js"></script>
</body>
</html>