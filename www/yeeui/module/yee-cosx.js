(function ($, Yee) {

    Yee.extend('cosx', 'input', function (elem, setting) {
        let qel = $(elem);
        let orgName = '';
        let keyName = '';
        //上传之前
        qel.on('uploadBefore', function (ev, bindData, files) {
            let promise = new Promise(function (resolve, reject) {
                orgName = files[0].name.toString();
                let extension = orgName.lastIndexOf('.') === -1 ? '' : orgName.substr(orgName.lastIndexOf('.') + 1, orgName.length).toLowerCase();
                let name = new Date().getTime();
                keyName = setting.path + name + '.' + extension;
                let key = 'upfiles/' + name + '.' + extension;
                $.get('/service/ugc_sign/image', {method: 'post', pathname: '/'}, function (data) {
                    if (data) {
                        bindData.key = key;
                        bindData.Signature = data.Authorization;
                        bindData['Content-Type'] = '';
                        bindData['x-cos-security-token'] = data.XCosSecurityToken;
                        resolve(bindData);
                    } else {
                        reject('授权失败');
                    }
                }, 'json');

            });
            return promise;
        });
        //上传后
        qel.on('uploadLoad', function (ev, event, xhr) {
            if (Math.floor(xhr.status / 100) === 2) {
                let ETag = xhr.getResponseHeader('etag');
                let Location = xhr.getResponseHeader('location');
                console.log({url: url, ETag: ETag, Location: Location});
                qel.emit('uploadComplete', {
                    status: true,
                    data: {
                        url: Location,
                        orgName: orgName,
                    }
                });
            } else {
                Yee.alert('文件上传失败，状态码：' + xhr.status)
            }
            return false;
        });

    });

})(jQuery, Yee);