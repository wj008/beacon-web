<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link type="text/css" rel="stylesheet" href="/yeeui/css/yeeui.css"/>
    <link type="text/css" rel="stylesheet" href="/icofont/icofont.css"/>
    <link rel="stylesheet" type="text/css" href="/static/admin/css/login.css"/>
    <script src="/yeeui/third/jquery-3.3.1.min.js"></script>
    <script src="/yeeui/yee.js"></script>
    <title>登录系统</title>
    <script>if (window.top != window) window.top.location.href = window.location.href;</script>
</head>
<body>
<div id="stars"></div>
<div style="margin: 0; position: relative; z-index: 3; padding-top: 200px;">
    <div class="login-wrap">
        <div class="login-title">{#webname#}</div>
        <form id="loginForm" method="post" yee-module="validate ajax" on-sussess="window.location.href='{url ctl='index' act='index'}'">
            <div class="input-wrap">
                <input id="username" name="username" type="text" class="form-inp" placeholder="请输入账号名" autocomplete="off"/><i class="icofont-business-man-alt-1"></i>
            </div>
            <div class="input-wrap">
                <input id="password" type="password" name="password" class="form-inp" placeholder="请输入账号密码" autocomplete="off"/><i class="icofont-unlock"></i>
            </div>
            <div class="input-wrap">
                <input id="code" type="text" name="code" class="form-inp" placeholder="请输入验证码" style="width:120px;"/><i class="icofont-key"></i>
                <img id="codeImg" align="right" height="40" src="/service/code?r={time()}" alt="看不清楚点击刷新！" onclick="this.src = '/service/code?r=' + Math.random();"/>
            </div>
            <div class="input-wrap">
                <input type="submit" class="form-btn blue" value="登录"/>
            </div>
            <div class="input-wrap">
                <span id="errorInfo"></span>
            </div>
        </form>
    </div>
</div>


{literal}
    <script>
        $('#username').data({
            'v@rule': {r: true},
            'v@message': {r: '账号名不能为空'},
            'v@output': '#errorInfo'
        });
        $('#password').data({
            'v@rule': {r: true},
            'v@message': {r: '账号密码不能为空'},
            'v@output': '#errorInfo'
        });
        $('#code').data({
            'v@rule': {r: true},
            'v@message': {r: '验证码不能为空'},
            'v@output': '#errorInfo'
        });

        var stars = document.getElementById('stars')
        var star = document.getElementsByClassName('star')

        // js随机生成流星
        for (var j = 0; j < 30; j++) {
            var newStar = document.createElement("div")
            newStar.className = "star"
            newStar.style.top = randomDistance(30, -30) + 'px'
            newStar.style.left = randomDistance(150, 20) + 'px'
            stars.appendChild(newStar)
        }

        // 封装随机数方法
        function randomDistance(max, min) {
            var distance = Math.floor(Math.random() * (max - min + 1) * 10 + min)
            return distance
        }

        // 给流星添加动画延时
        for (var i = 0, len = star.length; i < len; i++) {
            if (i % 6 == 0) {
                star[i].style.animationDelay = '0s'
            } else {
                star[i].style.animationDelay = i * 0.8 + 's'
            }
        }
    </script>
{/literal}
</body>
</html>
