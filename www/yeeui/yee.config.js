Yee.config({
    version: (function () {
        return '1.0.1';
        //return new Date().getTime();
    }()),
    //预加载,提前加载的
    preloading: {
        'layer': (Yee.isMobile ? 'layer/mobile/layer.js' : 'layer/layer.js')
    },
    //模块,可以用 use 引入
    modules: {
        //定义模块路径
        'json': window.JSON ? '' : 'third/json3.min.js',
        'jquery-cookie': 'third/jquery.cookie.js',
        'jquery-mousewheel': 'third/jquery.mousewheel.min.js',
        'base64': window.atob ? '' : 'base64.min.js',
        'xheditor': 'xheditor/xheditor-1.2.2.min.js',
        'xheditor-lang': 'xheditor/xheditor_lang/zh-cn.js',

        'tinymce': 'tinymce/tinymce.min.js',
        'tinymce-jquery': 'tinymce/jquery.tinymce.min.js',
        'tinymce-lang': 'tinymce/langs/zh_CN.js',

        'vue': 'third/vue.min.js',
    },
    //依赖,加载包的时候自动引入
    depends: {},
    dataFormat: null
});