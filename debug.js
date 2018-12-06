var server = require('dgram').createSocket('udp4');
var args = process.argv.splice(2);
var temp = [], tempfile = null;
setInterval(function () {
    if (temp.length == 0) {
        return;
    }
    var item = temp.shift();
    if (!item) {
        return;
    }
    var act = item[2] || 'log', file = item[1] || '', data = item[0] || null;
    if (file && tempfile != file) {
        tempfile = file;
        if (args[0] == '-c') {
            var a = '<div style="color: #c2c0cc"></div>';
            console.log("%c------>" + file, 'color:#ff4892;');
        } else {
            console.log("------>" + file);
        }
    }
    if (data !== null && data.length > 0) {
        if (args[0] == '-c' && (act == 'sql' && data.length == 2)) {
            data[0] = '%c' + data[0];
            data[1] = '%c' + data[1];
            var code = data.join('   ');
            console.log(code, 'color:#0088ff;', 'color:#c2c0cc;');
        } else {
            if (act == 'sql') {
                act = 'log';
            }
            console[act].apply(console, data);
        }
    }
}, 10);
server.on('message', function (msg) {
    if (/^[\{\[].*[\}\]]$/.test(msg)) {
        temp.push(JSON.parse(msg));
    }
});
server.on('error', function (err) {
});
server.on('listening', function () {
    console.log(
        '  ____                                        \n' +
        ' |  _ \\                                       \n' +
        ' | |_) |   ___    __ _    ___    ___    _ __  \n' +
        ' |  _ <   / _ \\  / _` |  / __|  / _ \\  | \'_ \\ \n' +
        ' | |_) | |  __/ | (_| | | (__  | (_) | | | | |\n' +
        ' |____/   \\___|  \\__,_|  \\___|  \\___/  |_| |_|');
    console.log('=====================debug====================');
});
server.bind(1024);
