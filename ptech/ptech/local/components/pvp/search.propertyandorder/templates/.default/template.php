<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<?php
\Bitrix\Main\Page\Asset::getInstance()->addJs($templateFolder . '/jscatalogpropertysearchproducts.js');

$signer = new \Bitrix\Main\Security\Sign\Signer;
$signedTemplate = $signer->sign($templateName, "pvp__search.property");
$signedParams = $signer->sign(base64_encode(serialize($arParams)), "pvp__search.property");
?>

<script>
    if ('undefined' == typeof(window.pvpPropertySearchParams)) {
        window.pvpPropertySearchParams = {
            callbackUrl: '<?=$component->GetPath()?>/ajax.php',
            componentParams: '<?=CUtil::JSEscape($signedParams)?>'
        };
    }
</script>
<div class="pvp-search-property">
    <div class="search-form">
        <input class="search-form__input" type="text" placeholder="<?=GetMessage('ENTER_PROPERTY_VALUE')?>">
    </div>
    <div class="search-block">
        <div class="search-result-wrap">
            <div class="results-close">
                <a href="#" class="results-close__link">
                    <i class="fa fa-times"></i>
                </a>
            </div>
            <div class="search-result">
            </div>
        </div>
    </div>
</div>

<?$APPLICATION->IncludeComponent(
    "pvp:favorites",
    "",
    Array(
        "COMPOSITE_FRAME_MODE" => "A",
        "COMPOSITE_FRAME_TYPE" => "AUTO",
        "IBLOCK_ID" => "26",
        "IBLOCK_TYPE" => "1c_catalog",
        "MODE" => "CONTROLLER"
    )
);?>
