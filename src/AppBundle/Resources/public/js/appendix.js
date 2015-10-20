/**
 * Created by alexsholk on 14.10.15.
 */

$(function () {
    $('#popup_contact form').on('submit', function (e) {
        e.preventDefault();
        var name = $(this).find('input[name="name"]').val();
        var phone = $(this).find('input[name="phone"]').val();
        $.post($(this).attr('action'), {name: name, phone: phone}, function (response) {
            //console.log(response);
            $('#popup_contact form').remove();
            $('#popup_contact').append('<h2>' + response.message + '</h2>');
        });
    });

    $('#form_callback form').on('submit', function (e) {
        e.preventDefault();
        var name = $(this).find('input[name="name"]').val();
        var phone = $(this).find('input[name="phone"]').val();
        $.post($(this).attr('action'), {name: name, phone: phone}, function (response) {
            //console.log(response);
            $('#form_callback form .controls').remove();
            $('#form_callback form').append('<h4>' + response.message + '</h4>');
        });
    });

    // Калькулятор
    $('#btn-calculate').on('click', function () {
        var usdbyr = parseInt($('#usd-byr').val());
        var matcost = parseInt($('input[name="matcost"]:checked').val());
        var size = parseFloat($('#input-size').val());
        var cost = Math.ceil(usdbyr * matcost * size / 1000) * 1000;
        $('#summary').text(
            isNaN(cost) ?
                'Проверьте правильность ввода данных':
                'Примерная стоимость кухни: ' + cost.toLocaleString() + 'р'
            ).show(300);
    });
});

$(window).on('load', function () {
    // Fix gallery
    $(this).trigger('resize');
});