<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$this->setFrameMode(true);

if(count($arResult["SECTIONS"]) < 1)
	return;?>
<div class="homepage-catalog-tile">
        <?foreach($arResult["SECTIONS"] as $arSection) { ?>
                <?if($arSection["NAME"] && $arResult["SECTION"]["ID"] != $arSection["ID"]) {?>
                    <a class="homepage-catalog tile-link" href="<?=$arSection['SECTION_PAGE_URL']?>" title="<?=$arSection['NAME']?>">
                        <span class="tile-link-icon">
                            <?php if (! empty($arSection['ICON']['SRC'])) : ?>
                                <img class="tile-link-icon__img" src="<?=$arSection['ICON']['SRC']?>">
                            <?php endif;?>
                        </span>
                        <span><?=$arSection["NAME"]?></span>
                    </a>
                <?}?>
        <?}?>
</div>
