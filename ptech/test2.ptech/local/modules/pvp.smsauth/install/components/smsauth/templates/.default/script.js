BX.ready(function() {
    window.pvpSmsAuth = new SmsAuth();
    pvpSmsAuth.init();
});

class SmsAuth
{
    init() {
        let bodyElm = $('body');

        bodyElm.on('click', '.pvp-sms-auth-block .tab-switch__button', function (e) {
            pvpSmsAuth.switchTabs(e.currentTarget);
        })

        bodyElm.on('click', '.pvp-sms-auth-block .tab-switch__button', function (e) {
            pvpSmsAuth.switchTabs(e.currentTarget);
        })

        bodyElm.on('click', '.show-pvp-auth-block', function (e) {
            e.preventDefault();
            e.stopPropagation();

            $('.pvp-sms-auth-block .shadow').show();
            $('.pvp-sms-auth-block .auth-form').addClass('active');
        });

        bodyElm.on('click', '.pvp-sms-auth-block .shadow, .pvp-sms-auth-block .auth-form .close-circle a.close-icon',
            function(e) {
                pvpSmsAuth.close(e.currentTarget);
        });

        bodyElm.on('submit', '.pvp-sms-auth-block #send-code', function(event) {
            pvpSmsAuth.send(event);
        });

        bodyElm.on('click', '.pvp-sms-auth-block .fields .field.field-button .check-code', function() {
            pvpSmsAuth.checkCode();
        });

        bodyElm.on('click', '.pvp-sms-auth-block .fields .cancel.field__link', function() {
            pvpSmsAuth.resetPhone();
        });

        bodyElm.on('click', '.pvp-sms-auth-block .tab .fields .field.resend-code', function () {
           pvpSmsAuth.resendCode();
        });
    }

    close() {
        $('.pvp-sms-auth-block .shadow').hide();
        $('.pvp-sms-auth-block .auth-form').removeClass('active');
    }

    switchTabs(elm) {
        $('.pvp-sms-auth-block .tabs .tab, .pvp-sms-auth-block .tab-switch__button').removeClass('active');
        $(elm).addClass('active');

        $('.pvp-sms-auth-block .tabs .tab.' + elm.dataset.tab).addClass('active');
    }

    send(event) {
        event.preventDefault();
        event.stopPropagation()

        let data = this.formToObj(event.currentTarget);

        data.phone = data.phone.replace(/[^\+0-9]/g, '');

        if (data.phone.match(/^\+7[0-9]{10,10}$/)) {
            this.ajaxCall(data);
        } else {
            let label = $('label[for="pvp-sms-auth-phone"]');
            label.addClass('error');
            label.html('Введите корректный номер телефона');
        }
    }

    checkCode() {
        let code = $('.pvp-sms-auth-block #pvp-sms-auth-code').val();

        if (6 > code.length) {
            return;
        }

        let data = {};
        data.action = 'checkCode';
        data.code = code;

        this.ajaxCall(data);
    }

    resetPhone() {
        let data = {};
        data.action = 'resetPhone';

        this.ajaxCall(data);
    }

    resendCode() {
        let data = {};
        data.action = 'resendCode';

        this.ajaxCall(data);
    }

    formToObj(form) {
        let data = {};
        $(form).find ('input, textearea, select').each(function() {
            data[this.name] = $(this).val();
        });

        return data;
    }

    ajaxCall(data) {
        data.componentParams = window.pvpSmsAuthParams.componentParams;
        data.mode = 'pvp.smsauth';
        $.post(window.pvpSmsAuthParams.callbackUrl,
            data,
            function (data) {
                $('.pvp-sms-auth-block .auth-form .tabs .tab.phone').html(data);
            },
            'html'
        );
    }
}