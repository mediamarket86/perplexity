<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<div id="pvp-favorites-<?=$arParams['PRODUCT_ID']?>" class="pvp-favorite-block favorite-button catalog-element" data-product-id="<?=$arParams['PRODUCT_ID']?>">
    <span class="favorite loading placeholder">
        <span class="lds-ring"><span></span><span></span><span></span><span></span></span>
    </span>
    <a href="#" class="favorite-button favorite-button__add">
        <i class="fa favorite-button-icon"></i>
        <span class="favorite-button-desc"><?=GetMessage('DELAY_BUTTON_DESC')?></span>
    </a>
</div>
