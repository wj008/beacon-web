<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{block name="title"}{/block}</title>
    <link type="text/css" rel="stylesheet" href="/yeeui/css/yeeui.css"/>
    <link type="text/css" rel="stylesheet" href="/icofont/icofont.css"/>
    <script src="/yeeui/third/jquery-3.3.1.min.js"></script>
    <script src="/yeeui/yee.js"></script>
    {block name='header'}{/block}
</head>
<body>
<div class="yee-wrap">
    {block name='list-header'}{/block}
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
{literal}
    <script>
        $(function () {
            $("#list").on("render", function (ev, source) {
                if (source && source["pageInfo"]) {
                    $("#records-count").text(source["pageInfo"]["recordsCount"] || "0");
                }
            });
            $("#list").on("order", function (ev, data) {
                var form = $("#searchForm"), inp1 = form.find(":input[name=sort]");
                if (inp1.length == 0) {
                    inp1 = $('<input type="hidden" name="sort"/>').appendTo(form);
                }
                inp1.val(data.name + "-" + (data.order == 1 ? "asc" : "desc"));
                $("#searchForm").submit();
            });
            Yee.readyDialog(function (dialog) {
                $(".yee-wrap").addClass("yee-dialog");
            });
        });
    </script>
{/literal}
</body>
</html>