<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>错误提示</title>
    <link rel="stylesheet" href="/common/css/error.css" type="text/css">
</head>
<body>
<div id="error-main" class="error-main">
    <div class="icon"><img src="/common/image/error-5.png" alt=""></div>
    <div class="name">{$info.msg}</div>
    <div class="note">页面将在<span id="wait">5</span>秒后自动跳转，<a id="back" href="{$info.back}">马上跳转</a></div>
</div>
</body>
</html>
<script type="text/javascript">var data ={json_encode($info)|raw};</script>
<script type="text/javascript" src="/common/js/error.js?v=1.0.2"></script>
