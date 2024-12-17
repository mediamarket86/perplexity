<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<div class="pvp-sms-auth credentials">
    <?$frame = $this->createFrame("pvp-sms-auth-credentials")->begin("");?>
    <script type="text/javascript">
        $(function() {
            $('body').on('click', '.login_anch', function(e){
                e.preventDefault();
                $('.login_body').css({'display':'block'});
                $('.login').css({'display':'block'});
            });
            $('body').on('click', '.login_close, .login_body', function(e) {
                e.preventDefault();
                $('.login_body').css({'display': 'none'});
                $('.login').css({'display': 'none'});
            });
        });
    </script>
    <?if(!$USER->IsAuthorized()):?>
        <div class="login-form" id="loginForm">
            <div class="login-form-error error">
            </div>
            <form name="form_auth" id="ajax-login-form" method="post" target="_top" action="<?=$this->GetFolder()?>/ajax.php">
                <div class="fields">
                    <input type="hidden" name="AUTH_FORM" value="Y"/>
                    <input type="hidden" name="TYPE" value="AUTH"/>
                    <?if(strlen($arResult["BACKURL"]) > 0):?>
                        <input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>"/>
                    <?endif?>
                    <?if(isset($arResult["POST"]) && is_array($arResult["POST"])) foreach($arResult["POST"] as $key => $value) {?>
                        <input type="hidden" name="<?=$key?>" value="<?=$value?>"/>
                    <?}?>
                    <div class="field">
                        <input type="text" name="USER_LOGIN" maxlength="50" placeholder="<?=GetMessage('AUTH_LOGIN')?>" value="" class="input-field"/>
                    </div>
                    <div class="field">
                        <input type="password" name="USER_PASSWORD" maxlength="50" placeholder="<?=GetMessage('AUTH_PASSWORD')?>" value="" class="input-field"/>
                    </div>
                    <div class="field field-button">
                        <button type="submit" name="Login" class="login-button" value="<?=GetMessage('LOGIN')?>"><?=GetMessage("LOGIN")?></button>
                    </div>
                    <div class="field">
                        <a class="forgot field__link" href="<?=SITE_DIR?>personal/private/?forgot_password=yes" rel="nofollow"><?=GetMessage("AUTH_FORGOT_PASSWORD")?></a>
                    </div>
                    <div class="field" style="margin:0px;">
                        <a class="register field__link" href="<?=SITE_DIR?>register/" rel="nofollow"><?=GetMessage("AUTH_REGISTRATION")?></a>
                    </div>
                </div>
            </form>
            <script type="text/javascript">
                <?if(strlen($arResult["LAST_LOGIN"])>0) {?>
                try {
                    document.form_auth.USER_PASSWORD.focus();
                } catch(e) {}
                <?} else {?>
                try {
                    document.form_auth.USER_LOGIN.focus();
                } catch(e) {}
                <?}?>
            </script>
            <?if($arResult["AUTH_SERVICES"] && COption::GetOptionString("main", "allow_socserv_authorization", "Y") != "N"):?>
                <p class="login_as"><?=GetMessage("LOGIN_AS_USER")?></p>
                <?$APPLICATION->IncludeComponent("bitrix:socserv.auth.form", "icons",
                    array(
                        "AUTH_SERVICES"=>$arResult["AUTH_SERVICES"],
                        "SUFFIX"=>"form",
                    ),
                    $component,
                    array("HIDE_ICONS"=>"Y")
                );?>
                <?$APPLICATION->IncludeComponent("bitrix:socserv.auth.form", "",
                    array(
                        "AUTH_SERVICES"=>$arResult["AUTH_SERVICES"],
                        "AUTH_URL"=>$arResult["AUTH_URL"],
                        "POST"=>$arResult["POST"],
                        "POPUP"=>"Y",
                        "SUFFIX"=>"form",
                    ),
                    $component,
                    array("HIDE_ICONS"=>"Y")
                );?>
            <?endif?>
        </div>
    <?else:?>
        <a class="personal" href="<?=SITE_DIR?>personal/" title="<?=GetMessage("PERSONAL")?>" rel="nofollow"><i class="svg-icon svg-user"></i><span><?=$USER->GetFirstName()?></span></a>
        <a class="exit" href="?logout=yes" title="<?=GetMessage("EXIT")?>"><i class="svg-icon svg-sign-out"></i></a>
    <?endif;
    $frame->end();?>
</div>