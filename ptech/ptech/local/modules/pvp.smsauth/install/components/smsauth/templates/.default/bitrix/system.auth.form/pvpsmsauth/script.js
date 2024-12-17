$(function() {
    $('body').on('submit', '#ajax-login-form', function (e) {
       e.preventDefault();
       e.stopPropagation();

       let form = $(e.currentTarget);

        $('#loginForm .login-form-error').html('');
        
        $.post(
            form.attr("action"),
            form.serialize(),
            function (data) {
                if (Object.hasOwn(data, 'success')) {
                    window.location.reload();
                } else if(Object.hasOwn(data, 'error')) {
                    $('#loginForm .login-form-error').html(data.error);
                } else {
                    $('#loginForm .login-form-error').html('Произошла ошибка обновите страницу и повторите попытку!');
                }
            },
            "json"
        );
    });
});