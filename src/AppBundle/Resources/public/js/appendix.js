/**
 * Created by alexsholk on 14.10.15.
 */

$(function () {
    // Заявка на звонок
    function sendApplication(action, name, phone, callback) {
        // Проверка
        if (!action || !name || !phone) {
            alert('Заполните поля "Имя" и "Телефон"');
            return false;
        }

        $.post(action, {name: name, phone: phone}, callback);
    }

    $('#form, #form_callback form, #zakaz-calc form').on('submit', function (e) {
        e.preventDefault();
        sendApplication(
            $(this).attr('action'),
            $(this).find('input[name="name"]').val(),
            $(this).find('input[name="phone"]').val(),
            function (response) {
                alert(response.message);
            }
        );
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