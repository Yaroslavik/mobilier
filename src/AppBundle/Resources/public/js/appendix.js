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
});