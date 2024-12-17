<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$this->setFrameMode(true);

if(count($arResult["ITEMS"]) < 1)
	return;
?>

<div class="vendors-bottom-block pt-slider">
    <div class="header">
        <h2><?=GetMessage("VENDORS_TITLE")?></h2>
        <div class="more-data">
	        <a class="more-data__link" href="<?=SITE_DIR?>vendors/"><?=GetMessage("ALL_VENDORS")?></a>
        </div>
    </div>
    <div class="tagline-block">
        <div class="tagline">
            <span class="counter scrollable-counter-element" data-current="1" data-end="14" data-speed="20">1</span>
            <span class="desc">эксклюзивных<br> торговых марок</span>
        </div>
        <div class="tagline prod-count">
            <span class="desc">более</span>
            <span class="counter scrollable-counter-element" data-current="1" data-end="5000" data-speed="1" data-step="50">1</span>
            <span class="desc">наименований<br> товаров</span>
        </div>
    </div>

	<div class="vendor-list">
			<?php foreach($arResult["ITEMS"] as $arItem) : ?>
                <?php
                    $backgroundStyle = empty($arItem['BACKGROUND_IMG']) ? '' : 'style="background-image: url(\'' . $arItem['BACKGROUND_IMG']['SRC'] . '\')"';
                ?>
                <a class="item" href="<?=$arItem["DETAIL_PAGE_URL"]?>" <?=$backgroundStyle?>>
						<span class="logo">
								<?if(!empty($arItem["PREVIEW_PICTURE"]["SRC"])):?>
									<img class="logo__img" src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>" alt="<?=$arItem['NAME']?>" />
								<?else:?>
									<img class="logo__img" src="<?=SITE_TEMPLATE_PATH?>/images/no-photo.jpg" alt="<?=$arItem['NAME']?>" />
								<?endif;?>
							</span>
                        <span class="desc">
                            <?=empty($arItem['PROPERTIES']['SHORT_DESC']['~VALUE']['TEXT']) ? '' : $arItem['PROPERTIES']['SHORT_DESC']['~VALUE']['TEXT']?>
                        </span>
				</a>
			<?php endforeach;?>
	</div>
</div>

<script>
    BX.ready(function () {
        $('.vendors-bottom-block .vendor-list').slick({
            slidesToShow: 3,
            slidesToScroll: 1,
            infinite: false,
            variableWidth: true,
            prevArrow: '<a href="javascript:void(0)" class="button back"></a>',
            nextArrow: '<a href="javascript:void(0)" class="button forward"></a>',
            responsive: [
                {
                    breakpoint: 787,
                    settings: {
                        slidesToShow: 2,
                    }
                },
                {
                    breakpoint: 580,
                    settings: {
                        slidesToShow: 1,
                    }
                }
            ]
        });
    });
</script>