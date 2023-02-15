$(function () {
    $('#images').on('uploadComplete', function (ret) {
        const files = [];
        if (ret.status) {
            if (ret.data.files && ret.data.files.length > 0) {
                for (const file of ret.data.files) {
                    files.push({url: file.url, name: file.orgName});
                }
            } else {
                files.push({url: ret.data.url, name: ret.data.orgName});
            }
        }
        $('#files').val(JSON.stringify(files));
    })
});