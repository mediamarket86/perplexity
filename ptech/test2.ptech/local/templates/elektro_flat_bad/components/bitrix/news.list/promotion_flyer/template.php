<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);

if (! count($arResult["ITEMS"] )) {
    return;
}

$promotionsFlyerAuthClass = $USER->isAuthorized() ? '' : 'show-pvp-auth-block';

?>
<div class="promotions-flyer">
    <h3>Акционная листовка с ценами</h3>
<?if($arParams["DISPLAY_TOP_PAGER"]):?>
	<?=$arResult["NAV_STRING"]?><br />
<?endif;?>
<?foreach($arResult["ITEMS"] as $arItem):?>
    <?php if (! empty($arItem["DISPLAY_PROPERTIES"]['FILE']['FILE_VALUE']['SRC'])) : ?>
        <?php
        $promotionsFlyerLink = $USER->isAuthorized() ? $arItem["DISPLAY_PROPERTIES"]['FILE']['FILE_VALUE']['SRC'] : '#';
        ?>
            <a class="promotions-flyer__link <?=$promotionsFlyerAuthClass?>" href="<?=$promotionsFlyerLink?>" target="_blank"><?=$arItem["NAME"]?></a>
    <?php endif; ?>
<?endforeach;?>
<?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
	<br /><?=$arResult["NAV_STRING"]?>
<?endif;?>
</div>
