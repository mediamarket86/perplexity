<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?php
$signer = new \Bitrix\Main\Security\Sign\Signer;
$signedParams = $signer->sign(base64_encode(serialize($arParams)), 'pvp.favorites');
?>

<script data-skip-moving="true">
    if ('undefined' === typeof(window.pvpFavoritesParams)) {
       window.pvpFavoritesParams = {
            callbackUrl: '<?=$component->GetPath()?>/ajax.php',
            mode: '<?=$arParams['MODE']?>',
            componentParams: "<?=CUtil::JSEscape($signedParams)?>"
       };
    }
</script>

