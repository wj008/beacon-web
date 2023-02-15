var userAgent = navigator.userAgent;
var ie6 = (/msie\s*(\d+)\.\d+/g.exec(userAgent.toLowerCase()) || [0, "0"])[1] == "6",
    ie7 = userAgent.indexOf('MSIE 7.0') > -1,
    ie8 = userAgent.indexOf('MSIE 8.0') > -1,
    ie9 = userAgent.indexOf('MSIE 9.0') > -1;
if (ie6 || ie7 || ie8 || ie9) {
    window.location.href = "/browser.html?referrer=" + encodeURIComponent(window.location.href);
}