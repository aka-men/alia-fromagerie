$(function () {
    $('#sign_up').validate({
        rules: {
            'fos_user_resetting_form[plainPassword][second]': {
                equalTo: '[name="fos_user_resetting_form[plainPassword][first]"]'
            }
        },
        highlight: function (input) {
            console.log(input);
            $(input).parents('.form-line').addClass('error');
        },
        unhighlight: function (input) {
            $(input).parents('.form-line').removeClass('error');
        },
        errorPlacement: function (error, element) {
            $(element).parents('.input-group').append(error);
            $(element).parents('.form-group').append(error);
        }
    });
});