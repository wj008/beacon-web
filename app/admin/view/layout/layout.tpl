<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="renderer" content="webkit">
    <title>{block name='title'}后台管理系统{/block}</title>
    <link type="text/css" rel="stylesheet" href="/icofont/icofont.css"/>
    <link type="text/css" rel="stylesheet" href="/yeeui/css/yeeui.css"/>
    <link rel="stylesheet" href="/static/admin/css/main.css" type="text/css">
    <link rel="stylesheet" href="/static/common/css/image-show.css" type="text/css">
    <script src="/yeeui/third/jquery-3.3.1.min.js"></script>
    <script src="/yeeui/yee.js?v=2.0.3"></script>
    <script src="/static/common/js/image-shower.js"></script>
    {block name="header"}{/block}
</head>
<body>

<div class="header">
    <div class="logo">
        <div class="img"><a href=""><img src="/static/admin/images/logo.png" alt=""></a></div>
    </div>
    <div class="right">
        <div class="back"><a href="/" target="_blank">返回首页</a></div>
        {*<div class="notice"><a href="#"><span id="noticeNumber" style="display: none">0</span></a></div>*}
        <div class="user">
            <div class="head">
                <a href="javascript:void(0)">
                    <div class="portrait"><img src="{if !empty($this->adminAvatar)}{$this->adminAvatar}{else}/static/common/images/portrait.png{/if}" onerror="this.src='/static/common/images/portrait.png'" alt=""></div>
                    <div class="name">{if !empty($this->adminName)}{$this->adminName}{/if}</div>
                </a>
            </div>
            <div class="list">
                <ul>
                    <li><a href="{url ctl='Member' act='info'}"><span class="icon-data">基本信息</span></a></li>
                    <li><a href="{url ctl='Member' act='password'}"><span class="icon-pwd">修改密码</span></a></li>
                    <li><a href="{url ctl='Index' act="logout"}"><span class="icon-exit">退出登录</span></a></li>
                </ul>
            </div>
        </div>
    </div>
</div>

{*右侧菜单*}
<div class="left-bar">
    {*
    {foreach from=$this->leftTop() item=rs}
        <div class="menu-top"><a href="{$rs.url}"><i class="icofont-rounded-double-left"></i>{$rs.name}</a></div>
    {/foreach}
    *}
    <div class="menu scrollbar">
        {foreach from=$this->leftBar() item=rs}
            <div class="item">
                <div class="name"><a href="javascript:;"><i class="{$rs.icon}"></i>{$rs.name}</a></div>
                <div class="list">
                    <ul>
                        {foreach from=$rs.items item=xrs}
                            <li><a {if $xrs.active} class="on"{/if} {if $xrs.blank}target="_blank"{/if} href="{$xrs.url}">{$xrs.name}</a></li>
                        {/foreach}
                    </ul>
                </div>
            </div>
        {/foreach}
    </div>
</div>

{block name="wrapper"}{/block}

{block name='footer'}{/block}
</body>
</html>