<!doctype html>
<html lang="zh-cn">
<head>
    <meta charset="utf-8">
    <title>多选对话框</title>
    <link type="text/css" rel="stylesheet" href="/yeeui/css/yeeui.css"/>
    <link type="text/css" rel="stylesheet" href="/icofont/icofont.css"/>
    <script src="/yeeui/third/jquery-3.3.1.min.js"></script>
    <script src="/yeeui/third/vue.min.js"></script>
    <script src="/yeeui/yee.js?v=2.0.3"></script>
    {literal}

    {/literal}
</head>
<body>
<div class="yee-wrap yee-dialog">

    <div class="yee-tab">
        <ul style="float:left;" yee-module="form-tab">
            <li class="curr" data-bind-name="main-list"><a href="javascript:;">待选</a></li>
            <li data-bind-name="selected-list"><a href="javascript:;">已选</a></li>
        </ul>
        <div class="yee-tab-right">
            <span> 已选 <span id="selected-count" class="green">0</span>/<span id="records-count">0</span> 条记录</span>
            <a href="javascript:window.location.reload()" class="refresh-btn"><i class="icofont-refresh"></i>刷新</a>
        </div>
    </div>

    <div name="main-list">
        {block name='list-search'}{/block}

        <div class="scrollbar" style="height:calc(100vh - 200px);overflow-y: auto">
            {block name='list-table'}{/block}
        </div>
        {block name='list-pagebar'}{/block}
    </div>

    <div name="selected-list" class="scrollbar" style="height:calc(100vh - 100px);overflow-y: auto">
        <table id="selected-table" class="yee-datatable" border="0" width="100%" style="background:#fff;">
            <thead>
            <tr>
                <th align="center" width="60">ID值</th>
                <th align="left">已选选项(文本)</th>
                <th width="80">操作</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="rs in Object.values(select)">
                <td align="center" v-text="rs.value"></td>
                <td align="left" v-text="rs.text"></td>
                <td align="center"><a href="javascript:;" class="yee-btn remove" style="margin-right: 5px" @click="remove(rs)">移除</a></td>
            </tr>
            </tbody>
        </table>
    </div>

    <div class="yee-submit" style="position: fixed; right: 0; bottom: 0; margin: 0px; padding: 10px 0; text-align: right;">
        <input id="select-btn" type="button" class="form-btn red" style="margin-right: 10px;" value="确定"/>
        <a id="close-btn" href="javascript:;" style="margin-right: 20px;" class="form-btn">关闭</a>
    </div>
</div>

{literal}
    <script>
        //当对话框初始化完成后，该函数会返回 dialog 的对话框句柄。
        Yee.readyDialog(function (dialog) {
            let dataMap = {};
            let table = $("#list");

            //使用vue渲染以选中的数据
            let app = new Vue({
                el: '#selected-table',
                data: {
                    select: []
                },
                methods: {
                    remove: function (value) {
                        removeItem(value);
                        refreshItem();
                    }
                }
            });

            function addItem(item) {
                let key = item.value;
                //去重，如果已经存在，不要再加入
                if (dataMap[key] !== void 0) {
                    return;
                }
                dataMap[key] = item;
                app.select.push(item);
                $("#selected-count").text(app.select.length);
            }

            function removeItem(item) {
                let key = item.value;
                delete dataMap[key];
                let temp = [];
                for (let i = 0; i < app.select.length; i++) {
                    let k = app.select[i].value;
                    if (dataMap[k]) {
                        temp.push(app.select[i]);
                    }
                }
                app.select = temp;
                $("#selected-count").text(app.select.length);
            }

            //刷新列表中的 勾选状态
            function refreshItem() {
                table.find(':checkbox').prop('checked', false);
                for (let key in dataMap) {
                    let rs = dataMap[key] || null;
                    if (rs) {
                        table.find(':checkbox.check-item[value=' + rs.value + ']').prop('checked', true);
                    }
                }
            }

            function setBox(elem) {
                let box = $(elem);
                let data = box.data() || {};
                if (!box.prop('checked')) {
                    removeItem(data);
                } else {
                    addItem(data);
                }
            }

            //关闭对话框
            $('#close-btn').on('click', function () {
                dialog.close();
            });

            //确定选择
            $('#select-btn').on('click', function () {
                let items = [];
                for (let i = 0; i < app.select.length; i++) {
                    let rs = app.select[i];
                    if (rs) {
                        items.push(rs);
                    }
                }
                //使用对话框句柄返回数据 items 数据
                dialog.success(items);
                //关闭对话框
                dialog.close();
            });

            //列表控件更新后
            table.on("render", function (ev, source) {
                $("#records-count").text(source.pageInfo.recordsCount || "0");
                refreshItem();
            });

            table.on('click', 'input:checkbox', function () {
                if ($(this).is('.check-item')) {
                    setBox(this);
                } else {
                    setTimeout(function () {
                        $('#list').find('input:checkbox.check-item').each(function (_, elem) {
                            setBox(elem);
                        });
                    }, 1);
                }
            });

            //从对话框中拿取传过来的数据
            if (dialog !== void 0 && dialog.assign && Yee.isArray(dialog.assign)) {
                let items = dialog.assign || [];
                for (let i = 0; i < items.length; i++) {
                    if (items[i].value !== void 0) {
                        addItem(items[i]);
                    }
                }
            }
        });
    </script>
{/literal}

</html>