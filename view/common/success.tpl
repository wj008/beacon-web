<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>操作成功提示</title>
    <link rel="stylesheet" href="/common/css/success.css" type="text/css">
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
    <script type="text/javascript" src="/common/js/success.js?v=1.0.2"></script>
</div>
</body>
</html>