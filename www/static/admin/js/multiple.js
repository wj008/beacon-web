Yee.readyDialog(function (dialog) {


    //更新高
    var updateHeight = function () {
        var winH = $(window).height();
        if (winH < 100) {
            window.setTimeout(updateHeight, 500);
        }
        var height = winH - 120;
        $('#main-layout').height(height);
        $('#main-select').height(height);
        $('#main-select-split').height(height);
        var searchH = $('.yee-list-search').outerHeight(true) || 0;
        var barH = $('.yee-pagebar').outerHeight(true) || 0;
        $('#list').emit('setHeight', height - searchH - barH - 15);
    }

    $(window).resize(function () {
        updateHeight();
    });
    updateHeight();

    var listTable = $("#list");

    function ChoseData() {
        var select = [];
        var map = [];
        this.add = function (item) {
            if (map[item.value] !== void 0) {
                return;
            }
            map[item.value] = item;
            select.push(item);
        }
        this.remove = function (value) {
            if (typeof value == 'object' && value !== null) {
                value = value.value || '';
            }
            if ((typeof value == 'string' || typeof value == 'number') && value !== '') {
                if (map[value] === void 0) {
                    return;
                }
                var temp = [];
                for (var i = 0; i < select.length; i++) {
                    if (String(select[i].value) !== String(value)) {
                        temp.push(select[i]);
                    }
                }
                select = temp;
                delete map[value];
            }
        }
        this.list = function () {
            var temp = [];
            for (var i = 0; i < select.length; i++) {
                temp.push(select[i]);
            }
            return temp;
        }
        this.refresh = function () {
            listTable.find(':checkbox').prop('checked', false);
            for (var key in map) {
                var rs = map[key];
                listTable.find(':checkbox.check-item[value=' + rs.value + ']').prop('checked', true);
            }
        }
        this.update = function () {
            Yee.getTemplate('selected').render({select: select});
        }
    }

    var app = new ChoseData();

    //关闭
    $('#close-btn').on('click', function () {
        dialog.close();
    });
    //选择
    $('#select-btn').on('click', function () {
        var text = app.list();
        var value = [];
        for (var i = 0; i < text.length; i++) {
            value.push(text[i].value);
        }
        dialog.success({value: JSON.stringify(value), text: text});
        dialog.close();
    });

    //更新
    listTable.on("render", function (ev, source) {
        if (source && source["pageInfo"]) {
            $("#records-count").text(source["pageInfo"]["recordsCount"] || "0");
        }
        app.refresh();
        updateHeight();
    });
    listTable.on('click', 'input:checkbox', function () {
        if ($(this).is('.check-item')) {
            var box = $(this);
            var data = box.data() || {};
            if (box.is(':input')) {
                data.value = box.val();
            }
            if (data.value === void 0) {
                return;
            }
            var val = data.value;
            if (!box.prop('checked')) {
                app.remove(val);
            } else {
                app.add(data);
            }
            app.update();
        } else {
            setTimeout(function () {
                $('#list').find('input:checkbox.check-item').each(function (_, elem) {
                    var box = $(elem);
                    var data = box.data() || {};
                    if (box.is(':input')) {
                        data.value = box.val();
                    }
                    if (data.value === void 0) {
                        return;
                    }
                    var val = data.value;
                    if (!box.prop('checked')) {
                        app.remove(val);
                    } else {
                        app.add(data);
                    }
                });
                app.update();
            }, 1);
        }
    });
    $('#main-table').on('click', 'a.remove', function () {
        var value = $(this).data('value');
        $(this).parents('tr:first').remove();
        app.remove(value);
        app.refresh();
    });

    if (dialog.assign != null && dialog.assign.text !== null && Yee.isArray(dialog.assign.text)) {
        var items = dialog.assign.text || [];
        for (var i = 0; i < items.length; i++) {
            if (items[i].value !== undefined) {
                app.add(items[i]);
            }
        }
        setTimeout(function () {
            app.update();
        }, 100);
    }
});