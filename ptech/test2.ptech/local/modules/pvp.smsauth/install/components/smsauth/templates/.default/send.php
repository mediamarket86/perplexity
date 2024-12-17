<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<div class="fields">
    <form method="POST" id="send-code">
        <div class="field phone-auth">
            <input type="hidden" name="action" value="send">
            <label for="pvp-sms-auth-phone" class="field-label <?=$arResult['MESSAGE']['CLASS']?>"><?=$arResult['MESSAGE']['TEXT']?></label>
            <input class="input-field" inputmode="tel" name="phone" value="<?=$arResult['PHONE'] ?? ''?>" type="text" id="pvp-sms-auth-phone" required placeholder="+7(900)000-00-00"/>
        </div>
        <div class="field field-button">
            <button type="submit" class="login-button send-code"><?=GetMessage("SMSAUTH_BTN_GET_CODE")?></button>
        </div>
    </form>
</div>
<script>
    BX.ready(function () {
        var cleave = new Cleave('#pvp-sms-auth-phone', {
            numericOnly: true,
            blocks: [2, 3, 3, 2, 2],
            delimiters: ['(', ')', '-'],
            delimiterLazyShow: true,
            prefix: '+7'
        });

        cleave.setRawValue('<?=$arResult['PHONE'] ?? ''?>');
    });
</script>
