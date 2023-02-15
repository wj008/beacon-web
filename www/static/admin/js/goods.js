$(function () {
    let box = $('input[name=hasSpecs]').eq(1);
    $('#specs').on('update', function (ev) {
        box.emit('dynamic');
    });
    box.emit('dynamic');
});