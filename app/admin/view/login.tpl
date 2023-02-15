<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="renderer" content="webkit">
    <title>登录</title>
    <link type="text/css" rel="stylesheet" href="/yeeui/css/yeeui.css"/>
    <link type="text/css" rel="stylesheet" href="/icofont/icofont.css"/>
    <link rel="stylesheet" type="text/css" href="/static/admin/css/login.css"/>
    <script src="/yeeui/third/jquery-3.3.1.min.js"></script>
    <script src="/yeeui/yee.js"></script>
    <script>if (window.top != window) window.top.location.href = window.location.href;</script>
</head>
<body>
<div class="centerTable">
    <div class="centerTd">
        <div class="login-head" style="width: 440px"><img src="/static/admin/images/logo-login.png" alt=""></div>
        <div class="login" style="width: 440px">
            <div class="img" style="display:none"><img src="/static/admin/images/login-img.jpg" alt=""></div>
            <div class="con">
                <form id="loginForm" method="post" yee-module="validate ajax" data-mode="2" on-success="loginSuccess();">
                    <div class="title">账号登录</div>
                    <div class="list">
                        <div class="item">
                            <div class="icobox"><img src="/static/admin/images/login-ico1.png" alt=""></div>
                            <div class="inpbox"><input id="username" name="username" type="text" class="inp" placeholder="请输入账号名" autocomplete="off"/></div>
                        </div>
                        <div class="item">
                            <div class="icobox"><img src="/static/admin/images/login-ico2.png" alt=""></div>
                            <div class="inpbox"><input id="password" type="password" name="password" class="inp" placeholder="请输入账号密码" autocomplete="off"/></div>
                        </div>
                        <div class="item">
                            <div class="icobox"><img src="/static/admin/images/login-ico3.png" alt=""></div>
                            <div class="inpbox inpbox2"><input id="code" type="text" name="code" class="inp" placeholder="请输入验证码" /></div>
                            <div class="yzmbox">
                                <img id="codeImg" align="right" height="40" src="/service/img_code?r={time()}" alt="看不清楚点击刷新！" onclick="this.src = '/service/img_code?r=' + Math.random();"/>
                            </div>
                        </div>
                    </div>
                    <div class="button"><input type="submit" value="立即登录" class="btn"></div>
                    <div class="remember">
                        <label><input id="remember" type="checkbox">记住密码（不要在公用电脑上使用）</label>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
{literal}
    <script>

        $(function () {
            if (window.localStorage) {
                const userName = window.localStorage.getItem("userName") || '';
                const passWord = window.localStorage.getItem("passWord") || '';
                const remember = window.localStorage.getItem("remember") || '';
                $('#username').val(userName);
                $('#password').val(passWord);
                if (remember == '1') {
                    $('#remember').prop('checked', true);
                }
            }
        });

        function loginSuccess() {
            const rememberBox = $('#remember');
            if (rememberBox.is(':checked')) {
                const userName = $('#username').val() || '';
                const passWord = $('#password').val() || '';
                if (window.localStorage) {
                    window.localStorage.setItem("userName", userName);
                    window.localStorage.setItem("passWord", passWord);
                    window.localStorage.setItem("remember", '1');
                }
            } else {
                window.localStorage.clear();
            }
            window.location.href = '/admin/';
        }
    </script>
{/literal}
</body>
</html>