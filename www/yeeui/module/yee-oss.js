Yee.define('oss', ['a', 'input'], function (elem) {
    const qel = $(elem);
    let pathname = qel.data('folder') || 'upfiles';
    pathname = pathname.replace(/^\/+/, '');
    pathname = pathname.replace(/\/+$/, '');
    let webUrl = qel.data('web-url') || '';
    webUrl = webUrl.replace(/\/+$/, '');
    const convert = qel.data('convert') || false;
    const url = qel.data('url') || '';
    const fieldName = qel.data('field-name') || 'filedata';
    //上传之前
    qel.on('uploadBefore', function (ev, bindData, files) {
        if (files.length >= 2) {
            console.log("使用批量上传");
            const uploads = [];
            const promiseList = [];
            for (const file of files) {
                const promise = new Promise(function (resolve, reject) {
                    const orgName = file.name.toString();
                    const extension = orgName.lastIndexOf('.') === -1 ? '' : orgName.substr(orgName.lastIndexOf('.') + 1, orgName.length).toLowerCase();
                    const name = Yee.randomString(20);
                    const fd = new FormData();
                    for (let key in bindData) {
                        if (bindData[key] !== null) {
                            fd.append(key, bindData[key]);
                        }
                    }
                    const key = pathname + '/' + name + '.' + extension;
                    const src = key.replace(/^\/+/, '');
                    uploads.push({
                        url: webUrl + '/' + src,
                        orgName: orgName,
                        extension: extension
                    });

                    $.post('/service/aliyun/auth', function (ret) {
                        if (ret) {
                            fd.append('key', key);
                            fd.append('OSSAccessKeyId', ret.OSSAccessKeyId);
                            fd.append('signature', ret.signature);
                            fd.append('policy', ret.policy);
                            fd.append('success_action_status', ret.success_action_status);
                            fd.append('orgName', orgName);
                            let forgName = encodeURIComponent(orgName);
                            if (extension == 'jpg' || extension == 'jpeg' || extension == 'png' || extension == 'gif' || extension == 'mp3' || extension == 'mp4') {
                                fd.append('Content-Disposition', 'inline;filename="' + forgName + '"');
                            } else {
                                fd.append('Content-Disposition', 'attachment;filename="' + forgName + '"');
                            }
                            fd.append(fieldName, file);
                            let xhr = new XMLHttpRequest();
                            xhr.upload.addEventListener("progress", function (evt) {
                                let percent = Math.round(evt.loaded / evt.total * 100);
                                qel.emit('uploadProgress', [{total: evt.total, loaded: evt.loaded, percent: percent}]);
                            }, false);
                            xhr.addEventListener("load", function (evt) {
                                if (Math.floor(xhr.status / 100) === 2) {
                                    return resolve(key);
                                } else {
                                    return reject('上传失败,文件名:' + orgName);
                                }
                            }, false);
                            xhr.open("POST", url);
                            xhr.send(fd);
                        } else {
                            return reject('没有获取到正确的token');
                        }
                    }, 'json');
                });
                promiseList.push(promise);
            }
            Promise.all(promiseList).then(function (items) {
                const data = {
                    url: uploads[0].url,
                    orgName: uploads[0].orgName,
                    extension: uploads[0].extension,
                    files: uploads
                };
                qel.emit('uploadComplete', {
                    status: true,
                    data: data
                });
            }).catch(function (e) {
                console.log(e);
            });
            return false;
        }
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
