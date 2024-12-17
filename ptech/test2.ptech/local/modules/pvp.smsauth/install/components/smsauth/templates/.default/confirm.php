<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="fields">
    <div class="field phone-auth">
        <input class="input-field disabled" name="phone" value="<?=$arResult['PHONE']?>" type="text" id="pvp-sms-auth-phone" disabled/>
    </div>
    <div class="field phone-auth">
        <label for="pvp-sms-auth-code" class="field-label <?=$arResult['MESSAGE']['CLASS']?>"><?=$arResult['MESSAGE']['TEXT']?></label>
        <input class="input-field" value="<?=$arResult['CODE'] ?? ''?>" type="text" pattern="[0-9]{6,6}" id="pvp-sms-auth-code" placeholder="000000"  inputmode="numeric" />
    </div>
    <div class="field field-button">
        <button type="submit" class="login-button check-code"><?=GetMessage("SMSAUTH_BTN_ENTER_CODE")?></button>
    </div>

    <?php if (isset($arResult['NEXT_SEND_TIMEOUT']) && $arResult['NEXT_SEND_TIMEOUT']) : ?>
            <div class="field timeout">
                <?=GetMessage('SMSAUTH_RESEND_ACTIVE_AFTER')?> <span class="timer" data-timeout="<?=$arResult['NEXT_SEND_TIMEOUT']?>"></span>
            </div>

            <script>
                BX.ready(function () {
                    clearInterval(pvpSmsAuthParams.countdown);
                    pvpSmsAuthParams.countdown = setInterval('countTimer()', 1000);
                    $('#pvp-sms-auth-code').focus();

                    var cleave = new Cleave('#pvp-sms-auth-code', {
                        numericOnly: true,
                        blocks: [6],
                        delimiter: '',
                        delimiterLazyShow: true,
                    });

                    cleave.setRawValue('<?=$arResult['CODE'] ?? ''?>');

                    <?php if (empty($arResult['CODE'])) :?>
                        $('#pvp-sms-auth-code').on('input', function() {
                                pvpSmsAuth.checkCode();
                        });
                    <?php endif; ?>
                });

                function countTimer() {
                    let timer = $('.pvp-sms-auth-block .tab .fields .field.timeout .timer')[0];

                    if (typeof timer === 'undefined') {
                        clearInterval(pvpSmsAuthParams.countdown);
                        return;
                    }

                    let timeout = timer.dataset.timeout;
                    let minute = Math.floor(timeout / 60);
                    let seconds = timeout - minute * 60;

                    if (10 > seconds) {
                        seconds = '0' + seconds;
                    }

                    let time = minute + ':' + seconds;

                    $(timer).html(time);
                    --timeout;

                    if (1 > timer.dataset.timeout) {
                        clearInterval(pvpSmsAuthParams.countdown);
                        $('.pvp-sms-auth-block .tab .fields .field.timeout').hide();
                        $('.pvp-sms-auth-block .tab .fields .field.resend-code').removeClass('hidden');
                    }

                    timer.dataset.timeout = timeout
                }

            </script>
    <?php $resendFieldClass = 'hidden'?>
    <?php endif; ?>

    <div class="field resend-code <?=$resendFieldClass ?? ''?>">
        <a class="resend field__link" href="#" rel="nofollow"><?=GetMessage('SMSAUTH_BTN_RESEND')?></a>
    </div>
    <div class="field">
        <a class="cancel field__link" href="#" rel="nofollow"><?=GetMessage("SMSAUTH_BTN_CANCEL")?></a>
    </div>
</div>
