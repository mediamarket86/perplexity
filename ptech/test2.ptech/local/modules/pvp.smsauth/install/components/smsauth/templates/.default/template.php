<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?php
$signer = new \Bitrix\Main\Security\Sign\Signer;
$signedParams = $signer->sign(base64_encode(serialize($arParams)), 'pvp.smsauth');
?>

<script>
    if ('undefined' == typeof(window.pvpSmsAuthParams)) {
        window.pvpSmsAuthParams = {
            callbackUrl: '<?=$component->GetPath()?>/ajax.php',
            componentParams: "<?=CUtil::JSEscape($signedParams)?>"
        };
    }
</script>
<?$frame = $this->createFrame("pvp-checkcode-block")->begin();?>
<div class="pvp-sms-auth-block">
    <div class="auth-line">
        <?php if ($USER->isAuthorized()) : ?>
            <a class="personal auth-line__link" href="<?=SITE_DIR?>personal/" title="<?=GetMessage("PERSONAL")?>" rel="nofollow"><i class="svg-icon svg-user"></i><span><?=$USER->GetFirstName()?></span></a>
            <a class="exit auth-line__link" href="?logout=yes" title="<?=GetMessage("EXIT")?>"><i class="svg-icon svg-sign-out"></i></a>
        <?php else : ?>
            <a class="login auth-line__link show-pvp-auth-block" href="javascript:void(0)" title="<?=GetMessage("LOGIN")?>"><i class="svg-icon svg-user"></i><span><?=GetMessage("LOGIN")?></span><i class="svg-icon svg-sign-in"></i></a>
            <a class="register auth-line__link" href="<?=SITE_DIR?>register/" title="<?=GetMessage("REGISTRATION")?>" rel="nofollow"><i class="svg-icon svg-register"></i><span><?=GetMessage("REGISTRATION")?></span></a>
        <?php endif; ?>
    </div>
    <div class="shadow"></div>
    <div class="auth-form">
        <div class="close-circle">
            <a href="javascript:void()" class="close-icon"></a>
        </div>

            <?php if ($USER->isAuthorized()) : ?>
                <div class="alert-danger">Вы уже авторизованы!</div>
            <?php else : ?>
                <div class="header"><?=GetMessage('HEADER')?></div>
                <div class="tab-switch">
                    <button class="tab-switch__button active" data-tab="credentials">
                        <?=GetMessage('CREDENTIALS')?>
                    </button>
                    <button class="tab-switch__button" data-tab="phone">
                        <?=GetMessage('PHONE')?>
                    </button>
                </div>
                <div class="tabs">
                    <div class="tab credentials active">
                        <?$APPLICATION->IncludeComponent("bitrix:system.auth.form", "pvpsmsauth",
                            array(
                                "REGISTER_URL" => $arParams['REGISTER_URL'],
                                "FORGOT_PASSWORD_URL" => $arParams['FORGOT_PASSWORD_URL'],
                                "PROFILE_URL" => $arParams['PROFILE_URL'],
                                "SHOW_ERRORS" => "Y"
                            ),
                            $component,
                            array("HIDE_ICONS" => "Y")
                        );?>
                    </div>
                    <div class="tab phone">
                        <?php include('send.php');?>
                    </div>
                </div>
            <?php endif; ?>
    </div>
</div>
<?$frame->end();?>