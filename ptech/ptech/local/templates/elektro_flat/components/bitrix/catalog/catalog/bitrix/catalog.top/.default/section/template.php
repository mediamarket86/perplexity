<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var CBitrixComponentTemplate $this */
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @var string $strElementEdit */
/** @var string $strElementDelete */
/** @var array $arElementDeleteParams */
/** @var array $arSkuTemplate */
/** @var array $templateData */
global $APPLICATION;
?>
<?if (!isset($_GET['view']) || ($_GET['view'] === 'tile')): ?>
<div class="bx_catalog_top_home news_dop">
<?
foreach ($arResult['ITEMS'] as $key => $arItem)
{
	$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], $strElementEdit);
	$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], $strElementDelete, $arElementDeleteParams);
	$strMainID = $this->GetEditAreaId($arItem['ID']);

	$arItemIDs = array(
		'ID' => $strMainID,
		'PICT' => $strMainID.'_pict',
		'SECOND_PICT' => $strMainID.'_secondpict',
		'MAIN_PROPS' => $strMainID.'_main_props',

		'QUANTITY' => $strMainID.'_quantity',
		'QUANTITY_DOWN' => $strMainID.'_quant_down',
		'QUANTITY_UP' => $strMainID.'_quant_up',
		'QUANTITY_MEASURE' => $strMainID.'_quant_measure',
		'BUY_LINK' => $strMainID.'_buy_link',
		'BASKET_ACTIONS' => $strMainID.'_basket_actions',
		'NOT_AVAILABLE_MESS' => $strMainID.'_not_avail',
		'SUBSCRIBE_LINK' => $strMainID.'_subscribe',
		'COMPARE_LINK' => $strMainID.'_compare_link',

		'PRICE' => $strMainID.'_price',
		'DSC_PERC' => $strMainID.'_dsc_perc',
		'SECOND_DSC_PERC' => $strMainID.'_second_dsc_perc',

		'PROP_DIV' => $strMainID.'_sku_tree',
		'PROP' => $strMainID.'_prop_',
		'DISPLAY_PROP_DIV' => $strMainID.'_sku_prop',
		'BASKET_PROP_DIV' => $strMainID.'_basket_prop'
	);

	$strObName = 'ob'.preg_replace("/[^a-zA-Z0-9_]/", "x", $strMainID);
	$productTitle = (
		isset($arItem['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE'])&& $arItem['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE'] != ''
		? $arItem['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']
		: $arItem['NAME']
	);
	$imgTitle = (
		isset($arItem['IPROPERTY_VALUES']['ELEMENT_PREVIEW_PICTURE_FILE_TITLE']) && $arItem['IPROPERTY_VALUES']['ELEMENT_PREVIEW_PICTURE_FILE_TITLE'] != ''
		? $arItem['IPROPERTY_VALUES']['ELEMENT_PREVIEW_PICTURE_FILE_TITLE']
		: $arItem['NAME']
	);

	$minPrice = false;
	if (isset($arItem['MIN_PRICE']) || isset($arItem['RATIO_PRICE']))
		$minPrice = (isset($arItem['RATIO_PRICE']) ? $arItem['RATIO_PRICE'] : $arItem['MIN_PRICE']);
?>
	<div class="prod_item"><div class="bx_catalog_item_container" id="<? echo $strMainID; ?>">
        <a href="" class="tofavor">в закладки</a>
        <a href="<?=$arItem['COMPARE_URL']?>" class="tocompare">к сравнению</a>
        <?if($arItem['PROPERTIES']['NEW']['VALUE'] === 'Y'):?>
            <span class="novinka">Новинка</span>
        <?endif;?>
        <?if($arItem['PROPERTIES']['ACTION']['VALUE'] === 'Y'):?>
            <span class="novinka">Акция</span>
        <?endif;?>
        <a href="<? echo $arItem['DETAIL_PAGE_URL']; ?>" class="pic_tit">
            <p class="pics"><span><img src="<? echo $arItem['PREVIEW_PICTURE']['SRC']; ?>" alt="" /></span></p>
            <p class="title"><? echo $arItem['NAME']; ?> </p>
        </a>
        <p class="size"><? echo $arItem['PROPERTIES']['SIZE']['VALUE']?></p>
        <p class="art">код <? echo $arItem['PROPERTIES']['ARTICLE']['VALUE']?></p>
        <s class="old_price"><? echo $minPrice['PRINT_VALUE']; ?></s>
        <div class="new_price"><? echo $minPrice['PRINT_VALUE']; ?></div>
        <div class="stars"><span class="q3"></span><a href="">2</a></div>
        <a href="<? echo $arItem['ADD_URL']; ?>" class="tocart">В корзину</a>
</div></div>
<?
}
?>
</div>
<?endif?>
<?if (isset($_GET['view']) && ($_GET['view'] === 'list')): ?>
    <table class="prod_list">
        <thead>
        <tr>
            <th>код</th>
            <th>наименование</th>
            <th>характеристики</th>
            <th>мин</th>
            <th>упак</th>
            <th>цена, руб.</th>
            <th>к заказу</th>
            <th></th>
        </tr>
        </thead>
        <tfoot>
        <?
        foreach ($arResult['ITEMS'] as $key => $arItem)
        {
        $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], $strElementEdit);
        $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], $strElementDelete, $arElementDeleteParams);
        $strMainID = $this->GetEditAreaId($arItem['ID']);

        $arItemIDs = array(
            'ID' => $strMainID,
            'PICT' => $strMainID.'_pict',
            'SECOND_PICT' => $strMainID.'_secondpict',
            'MAIN_PROPS' => $strMainID.'_main_props',

            'QUANTITY' => $strMainID.'_quantity',
            'QUANTITY_DOWN' => $strMainID.'_quant_down',
            'QUANTITY_UP' => $strMainID.'_quant_up',
            'QUANTITY_MEASURE' => $strMainID.'_quant_measure',
            'BUY_LINK' => $strMainID.'_buy_link',
            'BASKET_ACTIONS' => $strMainID.'_basket_actions',
            'NOT_AVAILABLE_MESS' => $strMainID.'_not_avail',
            'SUBSCRIBE_LINK' => $strMainID.'_subscribe',
            'COMPARE_LINK' => $strMainID.'_compare_link',

            'PRICE' => $strMainID.'_price',
            'DSC_PERC' => $strMainID.'_dsc_perc',
            'SECOND_DSC_PERC' => $strMainID.'_second_dsc_perc',

            'PROP_DIV' => $strMainID.'_sku_tree',
            'PROP' => $strMainID.'_prop_',
            'DISPLAY_PROP_DIV' => $strMainID.'_sku_prop',
            'BASKET_PROP_DIV' => $strMainID.'_basket_prop'
        );

        $strObName = 'ob'.preg_replace("/[^a-zA-Z0-9_]/", "x", $strMainID);
        $productTitle = (
        isset($arItem['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE'])&& $arItem['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE'] != ''
            ? $arItem['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']
            : $arItem['NAME']
        );
        $imgTitle = (
        isset($arItem['IPROPERTY_VALUES']['ELEMENT_PREVIEW_PICTURE_FILE_TITLE']) && $arItem['IPROPERTY_VALUES']['ELEMENT_PREVIEW_PICTURE_FILE_TITLE'] != ''
            ? $arItem['IPROPERTY_VALUES']['ELEMENT_PREVIEW_PICTURE_FILE_TITLE']
            : $arItem['NAME']
        );

        $minPrice = false;
        if (isset($arItem['MIN_PRICE']) || isset($arItem['RATIO_PRICE']))
            $minPrice = (isset($arItem['RATIO_PRICE']) ? $arItem['RATIO_PRICE'] : $arItem['MIN_PRICE']);
        ?>
        <tr>
            <td><? echo $arItem['PROPERTIES']['ARTICLE']['VALUE']?></td>
            <td><? echo $arItem['NAME']; ?></td>
            <td><? echo $arItem['PROPERTIES']['SIZE']['VALUE']?></td>
            <td><? echo $arItem['PROPERTIES']['MIN_ORDER']['VALUE']?></td>
            <td><? echo $arItem['PROPERTIES']['AMOUNT_IN_PACK']['VALUE']?></td>
            <td>
                <s class="old_price"><? echo $minPrice['PRINT_VALUE']; ?></s>
                <div class="new_price"><? echo $minPrice['PRINT_VALUE']; ?></div>
            </td>
            <td><input type="text" class="inp" placeholder="шт."></td>
            <td><input type="submit" class="butt tocart" value="в корзину"></td>
        </tr>
        <?
        }
        ?>
        </tfoot>
    </table>
<?endif?>
<div style="clear: both;"></div>