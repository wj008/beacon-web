<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="renderer" content="webkit">
    <title>{block name='title'}后台管理系统{/block}</title>
    <link type="text/css" rel="stylesheet" href="/icofont/icofont.css"/>
    <link type="text/css" rel="stylesheet" href="/yeeui/css2/yeeui.css?v=2"/>
    <link rel="stylesheet" href="/static/admin/css/common.css" type="text/css">
    <script src="/yeeui/third/jquery-3.3.1.min.js"></script>
    <script src="/yeeui/yee.js?v=2.0.3"></script>
    <script src="/static/admin/js/menu.js"></script>
    <link rel="stylesheet" href="/common/css/image-show.css" type="text/css">
    <script src="/common/js/image-shower.js?v=2.0.0"></script>
    {block name="header"}{/block}
</head>
<body>

<div class="header">
    <div class="logo">
        <div class="img"><a href=""><img src="/static/admin/images/logo.png?v={time()}" alt=""></a></div>
    </div>
    {*
    <div id="main-navs" class="navs">
        {foreach from=$this->topMenu() item=rs}
            <div class="item">
                <a href="{$rs.url}" data-app="{$rs.app}" {if $rs.active} class="on"{/if}><i class="{$rs.icon}"></i>{$rs.name}</a>
            </div>
        {/foreach}
    </div>
    *}
    <div class="right">
        <div class="back"><a href="/" target="_blank">返回首页</a></div>
        {*<div class="notice"><a href="{url ctl='TaskNotice' state=0}"><span id="noticeNumber" style="display: none">0</span></a></div>*}
        <div class="user">
            <div class="head">

                <a href="javascript:void(0)">
                    <div class="portrait"><img src="{if !empty($this->adminFace)}{$this->adminFace}{else}/common/images/portrait.png{/if}" onerror="this.onerror=null;this.src='/common/images/portrait.png';" alt=""></div>
                    <div class="name">{if !empty($this->adminName)}{$this->adminName}{/if}</div>
                </a>
            </div>
            <div class="list">
                <ul>
                    <li><a href="{url app='admin' ctl='Manager' act='info'}"><span class="icon-data">基本信息</span></a></li>
                    <li><a href="{url app='admin' ctl='Manager' act='password'}"><span class="icon-pwd">修改密码</span></a></li>
                    <li><a href="{url app='admin' ctl='Index' act="logout"}"><span class="icon-exit">退出登录</span></a></li>
                </ul>
            </div>
        </div>
    </div>
</div>

{*右侧菜单*}
<div class="left-bar">
    <div id="left-menu" class="menu scrollbar">
        {include file="layout/left-menu.tpl" list=$this->leftMenu()}
    </div>
</div>

{block name="wrapper"}{/block}

{block name='footer'}{/block}
</body>
</html>