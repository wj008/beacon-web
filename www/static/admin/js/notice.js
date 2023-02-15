$(function () {
    let url = '/admin/task_notice/wait';
    let noticeNumber = 0;

    let updateNotice = function (count) {
        $.post(url, function (ret) {
            if (ret.status) {
                let count = ret.noticeCount || 0;
                Yee.setStorage('notice_count', count);
                if (noticeNumber != count) {
                    //推送让任务刷新页面
                    window.top.postMessage('task-reload', '*');
                    //console.log('发送更新任务');
                }
                noticeNumber = count;
                if (count > 0) {
                    $('#noticeNumber').show().text(noticeNumber);
                } else {
                    $('#noticeNumber').hide().text(noticeNumber);
                }
            } else {
                if (ret.logout == 1 && ret.back) {
                    Yee.msg(ret.msg);
                    setTimeout(function () {
                        window.location.href = ret.back;
                    }, 1000);
                }
            }
        }, 'json');
    };
    let notice = function () {
        let lastTime = parseInt(Yee.getStorage('notice_time') || 0);
        let lastCount = parseInt(Yee.getStorage('notice_count') || 0);
        noticeNumber = lastCount;
        if (lastCount > 0) {
            $('#noticeNumber').show().text(lastCount);
        } else {
            $('#noticeNumber').hide().text(lastCount);
        }
        let nowTime = Math.round(new Date().getTime() / 1000);
        if (nowTime < lastTime + 30) {
            return;
        }
        Yee.setStorage('notice_time', nowTime);
        updateNotice();
    };

    /*
    setInterval(function () {
        notice();
    }, 1000);
    notice();
*/
    window.top.addEventListener('message', function (event) {
        if (event.data != 'task-notice') {
            return;
        }
        updateNotice();
    }, false);
});