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
                //console.log(response);
                alert(response.message);
            }
        );
    });

    // Калькулятор
    $('#calc input[name="shape"]').on('change', function () {
        $('.size-inputs input[type="text"]').val('');
        switch ($('#calc input[name="shape"]:checked').val()) {
            case 'shape1':
            case 'shape2':
                $('#input-size-c').hide();
                $('#input-size-a, #input-size-b').show();
                break;
            case 'shape3':
                $('#input-size-c, #input-size-b').hide();
                $('#input-size-a').show();
                break;
            case 'shape4':
                $('#input-size-a, #input-size-b, #input-size-c').show();
                break;
        }
    }).trigger('change');

    $('#btn-calculate').on('click', function () {
        var usdbyr = parseInt($('#usd-byr').val());
        var matcost = parseInt($('input[name="matcost"]:checked').val());
        var sizeA = toFloat($('#input-size-a input').val());
        var sizeB = toFloat($('#input-size-b input').val());
        var sizeC = toFloat($('#input-size-c input').val());
        var size = sizeA + sizeB + sizeC;
        var cost = Math.ceil(usdbyr * matcost * size / 1000) * 1000;
        $('#summary').text(
            size == 0 || isNaN(cost) ?
                'Проверьте правильность ввода данных':
                'Примерная стоимость кухни: ' + cost.toLocaleString() + 'р'
            ).show(300);
    });

    function toFloat(value) {
        return parseFloat(value.trim().replace(/,/, '.')) || 0;
    }
});

$(window).on('load', function () {
    // Fix gallery
    $(this).trigger('resize');
});