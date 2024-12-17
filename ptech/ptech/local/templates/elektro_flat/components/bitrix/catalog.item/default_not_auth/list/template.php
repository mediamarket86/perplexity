<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main\Localization\Loc;?>

<div class="catalog-item-info">
	<?//ITEM_PREVIEW_PICTURE//?>
	<div class="catalog-item-image-cont">
		<div class="catalog-item-image">
			<meta content="<?=(is_array($arElement['PREVIEW_PICTURE']) ? $arElement['PREVIEW_PICTURE']['SRC'] : SITE_TEMPLATE_PATH.'/images/no-photo.jpg');?>" itemprop="image" />
			<a href="<?=$arElement['DETAIL_PAGE_URL']?>">
				<?if(is_array($arElement["PREVIEW_PICTURE"])) {?>
					<img class="item_img" src="<?=$arElement['PREVIEW_PICTURE']['SRC']?>" width="<?=$arElement['PREVIEW_PICTURE']['WIDTH']?>" height="<?=$arElement['PREVIEW_PICTURE']['HEIGHT']?>" alt="<?=$strAlt?>" title="<?=$strTitle?>" />
				<?} else {?>
					<img class="item_img" src="<?=SITE_TEMPLATE_PATH?>/images/no-photo.jpg" width="150" height="150" alt="<?=$strAlt?>" title="<?=$strTitle?>" />
				<?}?>
				<div class="sticker-cont">
					<?=$timeBuy?>
					<span class="sticker">
						<?=$sticker?>
					</span>
				</div>
				<?if(is_array($arElement["PROPERTIES"]["MANUFACTURER"]["PREVIEW_PICTURE"])) {?>
					<img class="manufacturer" src="<?=$arElement['PROPERTIES']['MANUFACTURER']['PREVIEW_PICTURE']['SRC']?>" width="<?=$arElement['PROPERTIES']['MANUFACTURER']['PREVIEW_PICTURE']['WIDTH']?>" height="<?=$arElement['PROPERTIES']['MANUFACTURER']['PREVIEW_PICTURE']['HEIGHT']?>" alt="<?=$arElement['PROPERTIES']['MANUFACTURER']['NAME']?>" title="<?=$arElement['PROPERTIES']['MANUFACTURER']['NAME']?>" />
				<?}?>
			</a>							
		</div>
	</div>
	<div class="catalog-item-params">
		<?//ITEM_TITLE//?>
		<div class="catalog-item-title">
			<a href="<?=$arElement['DETAIL_PAGE_URL']?>" title="<?=$arElement['NAME']?>" itemprop="url">
				<span itemprop="name"><?=$arElement["NAME"]?></span>
			</a>
		</div>
		<?//ITEM_PREVIEW_TEXT//
		if($inPreviewText) {?>
		<div class="catalog-item-preview-text" itemprop="description">
			<?=strip_tags($arElement["PREVIEW_TEXT"])?>
		</div>
		<?}?>

        <div class="article-compare-block">
				<?//ARTICLE//
			if($inArticle) {?>
                <div class="article">
                    <?=Loc::getMessage("CT_BCS_ELEMENT_ARTNUMBER")?><?=!empty($arElement["PROPERTIES"]["CML2_ARTICLE"]["VALUE"]) ? $arElement["PROPERTIES"]["CML2_ARTICLE"]["VALUE"] : "-";?>
                </div>
			<?}?>

            <?//ITEM_COMPARE//
            if($arParams["DISPLAY_COMPARE"]=="Y") {?>
                <div class="compare">
                    <a href="javascript:void(0)" class="catalog-item-compare" id="catalog_add2compare_link_<?=$itemIds['ID']?>" onclick="return addToCompare('<?=$arElement["COMPARE_URL"]?>', 'catalog_add2compare_link_<?=$itemIds["ID"]?>', '<?=SITE_DIR?>');" rel="nofollow"><i class="fa fa-bar-chart"></i><i class="fa fa-check"></i></a>
                </div>
            <?}?>
        </div>

        <div class="need-auth-block">
            <a class="auth-btn show-pvp-auth-block" href="javascript:void(0)" rel="nofollow"><i class="fa fa-user-o"></i><span>Авторизоваться</span></a>
        </div>

		<?//ITEM_PROPERTIES//
		if(!empty($arElement["DISPLAY_PROPERTIES"])) {?>
			<div class="properties">								
				<?foreach($arElement["DISPLAY_PROPERTIES"] as $k => $v) {?>
					<div class="property">
						<span class="name"><?=$v["NAME"]?></span> 
						<span class="val">
							<?=is_array($v["DISPLAY_VALUE"]) ? implode(", ", $v["DISPLAY_VALUE"]) : $v["DISPLAY_VALUE"];?>
						</span>
						<div class="clr"></div>
					</div>
				<?}?>
			</div>
		<?}?>

	</div>					
</div>
