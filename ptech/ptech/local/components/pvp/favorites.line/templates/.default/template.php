<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<div class="pvp-favorites-line">
    <?$frame = $this->createFrame("pvp-favorites-line")->begin();?>
    <a class="delay" href="<?=$arParams["PATH_TO_FAVORITES"]?>" title="<?=GetMessage("DELAY")?>" rel="nofollow">
        <i class="svg-icon svg-delay"></i>
        <span class="text"><?=GetMessage("DELAY")?></span>
        <span class="qnt-wrap">
			<span class="qnt pvp-favorites-quantity-value">
				<?=(isset($arResult["QUANTITY"]) && $arResult["QUANTITY"] > 0) ? $arResult["QUANTITY"] : "0";?>
			</span>
		</span>
    </a>
    <?$frame->end();?>
</div>