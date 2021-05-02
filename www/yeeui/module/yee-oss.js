Yee.define('oss', ['a', 'input'], function (elem) {
    let qel = $(elem);
    let pathname = qel.data('folder') || 'upfiles';
    pathname = pathname.replace(/^\/+/, '');
    pathname = pathname.replace(/\/+$/, '');
    let webUrl = qel.data('web-url') || '';
    webUrl = webUrl.replace(/\/+$/, '');
    let convert = qel.data('convert') || false;
    //上传之前
    qel.on('uploadBefore', function (ev, bindData, files) {
        return new Promise(function (resolve, reject) {
            let orgName = files[0].name.toString();
            let extension = orgName.lastIndexOf('.') === -1 ? '' : orgName.substr(orgName.lastIndexOf('.') + 1, orgName.length).toLowerCase();
            let name = Yee.randomString(20);
            $.post('/service/aliyun/auth', function (ret) {
                if (ret) {
                    bindData.key = pathname + '/' + name + '.' + extension;
                    bindData.OSSAccessKeyId = ret.OSSAccessKeyId;
                    bindData.signature = ret.signature;
                    bindData.policy = ret.policy;
                    bindData.success_action_status = ret.success_action_status;
                    bindData.orgName = orgName;
                    let forgName = encodeURIComponent(orgName);
                    if (extension == 'jpg' || extension == 'jpeg' || extension == 'png' || extension == 'gif' || extension == 'mp3' || extension == 'mp4') {
                        bindData['Content-Disposition'] = 'inline;filename="' + forgName + '"';
                    } else {
                        bindData['Content-Disposition'] = 'attachment;filename="' + forgName + '"';
                    }
                    return resolve(bindData);
                }
                return reject('没有获取到正确的token');
            }, 'json');
        });
    });

    //上传后
    qel.on('uploadLoad', function (ev, xhr, param) {
        if (Math.floor(xhr.status / 100) === 2) {
            let key = param.key || '';
            if (key == '') {
                Yee.alert('文件上传失败');
                return false;
            }
            key = key.replace(/^\/+/, '');
            let url = webUrl + '/' + key;
            let extension = key.lastIndexOf('.') === -1 ? '' : key.substr(key.lastIndexOf('.') + 1, key.length).toLowerCase();
            let data = {
                url: url,
                orgName: param.orgName,
                extension: extension
            };

            if (convert && ['pptx', 'ppt', 'xls', 'xlsx', 'doc', 'wps', 'docx', 'pdf'].indexOf(extension) >= 0) {
                let leyIndex = null;
                let checkState = function (taskId) {
                    let timer = window.setInterval(function () {
                        $.post('/service/aliyun/convert_state.json', {taskId: taskId}, function (ret) {
                            //没有成功
                            if (!ret.status) {
                                window.clearInterval(timer);
                                if (leyIndex !== null) {
                                    Yee.close(leyIndex);
                                    leyIndex = null;
                                }
                                Yee.alert('错误的消息：' + ret.msg);
                                timer = null;
                                return;
                            }
                            //成功并完成转换
                            if (ret.status && ret.finish) {
                                window.clearInterval(timer);
                                if (leyIndex !== null) {
                                    Yee.close(leyIndex);
                                    leyIndex = null;
                                }
                                timer = null;
                                data.document = ret.document;
                                qel.emit('uploadComplete', {
                                    status: true,
                                    data: data
                                });
                                return;
                            }
                        }, 'json');
                    }, 2000);
                };
                //如果是这些后缀 可以转换
                $.post('/service/aliyun/convert_task.json', {file: key}, function (ret) {
                    if (!ret.status) {
                        Yee.alert(ret.msg);
                        return;
                    }
                    //定时检查文档是否转换完成
                    if (ret.taskId) {
                        checkState(ret.taskId);
                    }
                }, 'json');
                leyIndex = Yee.msg('文档转换中,请不要关闭或者刷新页面。', {icon: 16, shade: 0.1, time: 1200000});
            } else {
                qel.emit('uploadComplete', {
                    status: true,
                    data: data
                });
            }
        } else {
            Yee.alert('文件上传失败，状态码：' + xhr.status);
        }
        return false;
    });
});
