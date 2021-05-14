Yee.setConfig({
    version: '1.0.1',
    //预加载,提前加载的
    preload: {
        'layer': window.layer ? '' : (Yee.isMobile ? 'layer/mobile/layer.js' : 'layer/layer.js')
    },
    //依赖项,可以用 Yee.use() 引入
    depends: {
        //定义模块路径
        'json': window.JSON ? '' : 'third/json3.min.js',
        'jquery-cookie': 'third/jquery.cookie.js',
        'jquery-mousewheel': 'third/jquery.mousewheel.min.js',
        'base64': window.atob ? '' : 'third/base64.min.js',
        'xheditor': 'xheditor/xheditor-1.2.2.min.js',
        'xheditor-lang': 'xheditor/xheditor_lang/zh-cn.js',
        'tinymce': 'tinymce/tinymce.min.js',
        'tinymce-jquery': 'tinymce/jquery.tinymce.min.js',
        'tinymce-lang': 'tinymce/langs/zh_CN.js',
        'vue': window.Vue ? '' : 'third/vue.min.js',
    },
});