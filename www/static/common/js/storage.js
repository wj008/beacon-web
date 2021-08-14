function YeeStorage() {
    this.get = function (name) {
        if (window.localStorage) {
            return window.localStorage.getItem(name);
        }
        var arr, reg = new RegExp('(^| )' + name + '=([^;]*)(;|$)');
        if (arr = document.cookie.match(reg)) {
            return unescape(arr[2]);
        } else {
            return null;
        }
    };
    this.set = function (name, value) {
        if (window.localStorage) {
            window.localStorage.setItem(name, value);
            return;
        }
        var Days = 30;
        var exp = new Date();
        exp.setTime(exp.getTime() + Days * 24 * 60 * 60 * 1000);
        document.cookie = name + '=' + escape(value) + ';expires=' + exp.toGMTString();
    };
    this.del = function (name) {
        if (window.localStorage) {
            return window.localStorage.removeItem(name);
        }
        var exp = new Date();
        exp.setTime(exp.getTime() - 1);
        var cval = this.get(name);
        if (cval != null) {
            document.cookie = name + '=' + cval + ';expires=' + exp.toGMTString();
        }
    }
}
