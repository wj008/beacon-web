<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{block name="title"}{#web.name#}{/block}</title>
    <link type="text/css" rel="stylesheet" href="/yeeui/css/yeeui.css"/>
    <link type="text/css" rel="stylesheet" href="/icofont/icofont.css"/>
    <link type="text/css" rel="stylesheet" href="/static/admin/css/multiple.css"/>
    <script src="/yeeui/third/jquery-3.3.1.min.js"></script>
    <script src="/yeeui/yee.js?v=2.0.3"></script>
    <script src="/static/admin/js/multiple.js"></script>
    {block name='header'}{/block}
</head>
<body>
<div class="yee-wrap">
    {block name='list-header'}{/block}
    <div class="clear"></div>
    <div style="width: 100%;">
        <div id="main-layout" style="float:left; width:calc(100% - 300px); box-shadow:inset 0px 0px 10px 2px #eee; background:#f7f7f7; overflow-y: auto; padding:3px;">
            {block name='list-tab'}{/block}
            {block name='list-attention'}{/block}
            {block name='list-search'}{/block}
            <div class="yee-list">
                {block name='list-table'}{/block}
                {block name='list-pagebar'}{/block}
            </div>
        </div>
        <div id="main-select" style="width: 280px; float: right;vertical-align: top; box-shadow:inset 0px 0px 10px 2px #eee;padding:3px;  background:#f7f7f7;">
            <table id="main-table" class="yee-show-table" border="0" width="100%" style="background:#fff;">
                <tr>
                    <th>已选选项</th>
                </tr>
                <tbody data-tpl-id="selected" yee-module="template">
                <tr yee-each="select" yee-item="rs">
                    <td>
                        <a href="javascript:;" class="yee-btn remove" style="margin-right: 5px" :data-value="rs.value">移除</a>
                        <span :text="'['+rs.value+'] '+(rs.text||'')"></span>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <div style="clear: both"></div>
    </div>
    <div class="yee-submit" style="position: fixed; right: 0; bottom: 0; margin: 0px; padding: 10px 0; text-align: right;">
        <input id="select-btn" type="button" class="form-btn red" style="margin-right: 10px;" value="确定"/>
        <a id="close-btn" href="javascript:;" style="margin-right: 20px;" class="form-btn">关闭</a>
    </div>
</div>
{block name='footer'}{/block}
</body>
</html>