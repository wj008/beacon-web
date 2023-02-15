$(function () {
    $('#title').on('select', function (ev, data) {
        $('#telephone').val(data.telephone);
        $('#taxNumber').val(data.number);
        $('#address').val(data.address);
        $('#bankName').val(data.bankName);
        $('#bankNumber').val(data.bankNumber);
    });
});