$(document).ready(function(){
    $('#reloadCaptcha').click(function(){
        $.getJSON('/ajax/reloadCaptcha.php', function(data) {
            $('#captchaImg').attr('src','/bitrix/tools/captcha.php?captcha_sid='+data);
            $('#captchaSid').val(data);
        });
        return false;
    });
    $("#typ_sobstv").change(function(){
        var val = $("#typ_sobstv option:selected").val();
        $("#type_sobst1, #type_sobst2").hide();
        if(val==1) $("#type_sobst1").show();
        if(val==2) $("#type_sobst2").show();
    })
});
