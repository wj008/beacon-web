Yee.define('qiniu', 'input', function (element) {
    var qel = $(element);
    //上传之前
    qel.on('uploadBefore', function (ev, bindData, files) {
        var orgName = files[0].name.toString();
        var extension = orgName.lastIndexOf('.') === -1 ? '' : orgName.substr(orgName.lastIndexOf('.') + 1, orgName.length).toLowerCase();
        bindData.key = Yee.randomString(20) + '.' + extension;
        return new Promise(function (resolve, reject) {
            $.post('/service/qiniu/auth', function (ret) {
                if (ret && ret.token) {
                    bindData.token = ret.token;
                    bindData['x:name'] = orgName
                    return resolve(bindData);
                }
                return reject('没有获取到正确的token');
            }, 'json');
        });
    });
});