<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>后台管理</title>
    <link rel="stylesheet" type="text/css" href="/static/admin/css/index.css">
    <link type="text/css" rel="stylesheet" href="/yeeui/css/yeeui.css"/>
    <link type="text/css" rel="stylesheet" href="/icofont/icofont.css"/>
    <script src="/yeeui/third/jquery-3.3.1.min.js"></script>
    <script src="/yeeui/yee.js"></script>
    <script src="/static/admin/js/index.js"></script>
</head>

<body>
<header>
    <div class="top-bar clearfix">
        <div class="logo">
            <a href="{url path='~/index'}" class="mypng"><img src="/static/admin/images/admin-logo.png" height="48"/></a>
            <div class="foldsider"><i id="foldsider" class="fa fa-outdent"></i></div>
        </div>

        <ul id="main-mune" class="main-mune">
            {foreach from=$rows item=rs}
                <li><a href="{url path='~/index/left' pid=$rs.id}" target="left">{$rs.name}</a></li>
            {/foreach}
        </ul>

        <div class="head-right">
            <div class="manager">
                <dl>
                    <dt class="name">admin</dt>
                    <dd class="group">超级管理员</dd>
                </dl>
                <span class="avatar">
				<img nctype="admin_avatar" src="/static/admin/images/admin.png">
			</span>
            </div>
            <div class="operate">
                <ul>
                    {* <li style="position: relative;">
                         <a href="javascript:void(0);" class="item" title="查看消息"><i class="icofont icofont-alarm"></i></a>
                         <!-- 消息通知 bylu -->
                         <div id="msg_Container">
                             <h3>消息通知</h3>
                             <div class="msg_content">
                                 <div class="no_msg">暂无消息!</div>
                             </div>
                         </div>
                     </li>
*}
                    <li><a href="/" target="_blank" class="item" title="新窗口打开首页"><i class="icofont icofont-ui-home"></i></a></li>
                    {* <li><a href="javascript:void(0);" class="item" title="查看全部管理菜单"><i class="icofont icofont-site-map"></i></a></li>*}
                    <li><a href="{url path='~/index/logout'}" class="item" title="安全退出管理中心"><i class="icofont icofont-power"></i></a></li>
                </ul>

            </div>
        </div>
    </div>
    <div class="top-border"></div>
</header>

<div class="main">
    <div id="left" class="left">
    </div>
    <div class="right">
        <div id="pagebars">
            <div id="page-bar">
                <div id="move-bar"></div>
            </div>
            <div id="page-btns">
                <i id="move-left" class="icofont icofont-circled-left"></i>
                <i id="move-right" class="icofont icofont-circled-right"></i>
                <i id="close-all" class="icofont icofont-close-circled"></i>
            </div>
        </div>
        <div id="content">
            <iframe scrolling="auto" name="main" id="main" src="{url path='~/index/welcome'}" frameborder="0" width="100%" height="100%"></iframe>
        </div>
    </div>
</div>
</body>
</html>
