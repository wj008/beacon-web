<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>页面异常</title>
    {literal}
        <style type="text/css">body, html {
                background: #fff;
                font-family: '微软雅黑';
                color: #333;
                font-size: 16px;
            }</style>
    {/literal}
</head>
<body>
<div style="line-height: 1.5em;font-size:18px;padding-left: 30px;">
    <b style="transform:rotate(90deg); text-align: center; width: 42px; height: 42px; line-height:35px; display: inline-block; font-size:36px;">:(</b>
    <span style="color: #c00262">{$info.msg}</span>
</div>
{if isset($info._stack)}
    <pre style="font-size: 12px;line-height: 20px;padding:10px;margin-top: 12px; background:#f7f7f7; color: #666;">
    {foreach from=$info._stack item=item}{$item}
    {/foreach}
</pre>
{/if}
</body>
</html>
