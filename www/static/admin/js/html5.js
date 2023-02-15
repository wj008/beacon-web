var e = 'abbr,article,aside,audio,canvas,datalist,details,dialog,eventsource,figure,footer,header,hgroup,mark,menu,meter,nav,output,progress,section,time,video'.split(',');
var i = e.length;
while (i--) {
    document.createElement(e[i]);
}
window.onload = function () {
    var imgs = document.getElementsByTagName('img');
    var i = imgs.length;
    while (i--) {
        var img = imgs[i];
        if (/\.png$/.test(img.src)) {
            img.style.filter = "progid:DXImageTransform.Microsoft.AlphaImageLoader(rc='" + img.src + "', sizingMethod='image')";
            img.src='/images/transparent.gif';
        }
    }
}