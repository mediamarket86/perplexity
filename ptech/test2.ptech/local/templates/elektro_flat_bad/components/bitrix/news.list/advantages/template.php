<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$this->setFrameMode(true);

if(count($arResult["ITEMS"]) < 1)
	return;?>

<div class="advantages">
	<?foreach($arResult["ITEMS"] as $arItem):?>
		<div class="advantages-item">
			<div class="advantages-item-icon-wrap">
				<div class="advantages-item-icon">
                    <?php if (isset($arItem['PREVIEW_PICTURE']['SRC'])) : ?>
                    <img class="advantages-item-icon__img" src="<?=$arItem['PREVIEW_PICTURE']['SRC']?>">
                    <?php endif; ?>
				</div>
			</div>
			<div class="advantages-item-text">
				<?=$arItem['NAME']?>
			</div>
		</div>
	<?endforeach;?>
</div>