Yee.define('txcos', ['a', 'input'], function (elem) {
    let qel = $(elem);
    let pathname = qel.data('folder') || 'upfiles';
    pathname = pathname.replace(/^\/+/, '');
    pathname = pathname.replace(/\/+$/, '');
    let webUrl = qel.data('web-url') || '';
    webUrl = webUrl.replace(/\/+$/, '');
    //上传之前
    qel.on('uploadBefore', function (ev, param, files) {
        return new Promise(function (resolve, reject) {
            let orgName = files[0].name.toString();
            let extension = orgName.lastIndexOf('.') === -1 ? '' : orgName.substr(orgName.lastIndexOf('.') + 1, orgName.length).toLowerCase();
            let name = Yee.randomString(20);
            let key = pathname + '/' + name + '.' + extension;
            $.post('/service/txcos/auth', {method: 'post', pathname: '/'}, function (data) {
                if (data) {
                    param.key = key;
                    param.Signature = data.Authorization;
                    param['Content-Type'] = '';
                    param['x-cos-security-token'] = data.XCosSecurityToken;
                    param.orgName = orgName;
                    resolve(param);
                } else {
                    reject('授权失败');
                }
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
            let fileUrl = webUrl + '/' + key;
            qel.emit('uploadComplete', {
                status: true,
                data: {
                    url: fileUrl,
                    orgName: param.orgName,
                }
            });
        } else {
            Yee.alert('文件上传失败，状态码：' + xhr.status);
        }
        return false;
    });
});

