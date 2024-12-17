<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !==true) die();

use \Bitrix\Main\Localization\Loc;?>

<div class="catalog-item-info">
    <div class="price-view-left-side">
        <?//ITEM_PREVIEW_PICTURE//?>
        <div class="catalog-item-image-cont">
            <div class="catalog-item-image">
                <meta content="<?=(is_array($arElement['PREVIEW_PICTURE']) ? $arElement['PREVIEW_PICTURE']['SRC'] : SITE_TEMPLATE_PATH.'/images/no-photo.jpg');?>" itemprop="image" />
                <a href="<?=$arElement['DETAIL_PAGE_URL']?>">
                    <?if(is_array($arElement["PREVIEW_PICTURE"])) {?>
                        <img class="item_img" src="<?=$arElement['PREVIEW_PICTURE']['SRC']?>" width="<?=$arElement['PREVIEW_PICTURE']['WIDTH']?>" height="<?=$arElement['PREVIEW_PICTURE']['HEIGHT']?>" alt="<?=$strAlt?>" title="<?=$strTitle?>" />
                    <?} else {?>
                        <img src="<?=SITE_TEMPLATE_PATH?>/images/no-photo.jpg" width="150" height="150" alt="<?=$strAlt?>" title="<?=$strTitle?>" />
                    <?}?>
                    <span class="sticker">
                        <?=$sticker?>
                    </span>
                </a>
            </div>
        </div>
        <?//ITEM_TITLE//?>
        <div class="catalog-item-title<?=$class?>">
            <a href="<?=$arElement['DETAIL_PAGE_URL']?>" title="<?=$arElement['NAME']?>" itemprop="url">
                <span itemprop="name"><?=$arElement["NAME"]?></span>
            </a>
        </div>

        <?//ARTICLE//
        if($inArticle) {?>
            <div class="article">
                <?=Loc::getMessage("CT_BCS_ELEMENT_ARTNUMBER")?><?=!empty($arElement["PROPERTIES"]["CML2_ARTICLE"]["VALUE"]) ? $arElement["PROPERTIES"]["CML2_ARTICLE"]["VALUE"] : "-";?>
            </div>
        <?}?>

        <? //ITEM_COMPARE//
        if($arParams["DISPLAY_COMPARE"]=="Y") {?>
            <div class="compare">
                <a href="javascript:void(0)" class="catalog-item-compare" id="catalog_add2compare_link_<?=$itemIds['ID']?>" onclick="return addToCompare('<?=$arElement["COMPARE_URL"]?>', 'catalog_add2compare_link_<?=$itemIds["ID"]?>', '<?=SITE_DIR?>');" title="<?=Loc::getMessage('CT_BCS_ELEMENT_ADD_TO_COMPARE')?>" rel="nofollow"><i class="fa fa-bar-chart"></i><i class="fa fa-check"></i></a>
            </div>
        <?}?>
    </div>
    <div class="price-view-right-side">
        <div class="need-auth-block">
            <a class="auth-btn show-pvp-auth-block" href="javascript:void(0)" rel="nofollow"><i class="fa fa-user-o"></i></a>
        </div>
    </div>
</div>
