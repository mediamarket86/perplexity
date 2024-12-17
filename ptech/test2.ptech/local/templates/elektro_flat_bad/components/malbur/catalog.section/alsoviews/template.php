<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
	die();
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

//PR($arResult);
?>

<?
//список товаров в закладках и сравнении
$favorites = isset($_SESSION['favorites']) ? $_SESSION['favorites'] : array();
$compares = get_site_values('compare_list');   //isset($_SESSION['compare_list']) ?  $_SESSION['compare_list'] : array();
foreach ($arResult['ITEMS'] as $key => $arItem) {
	$arResult['ITEMS'][$key]['isFavorite'] = in_array($arItem['ID'], $favorites);
	$arResult['ITEMS'][$key]['isCompare'] = in_array($arItem['ID'], $compares);
}
?>


<?
$num_items = 0;
// <!-- товары -->
//    <div class="bx_catalog_top_home news_dop">
?>
<?
foreach ($arResult['ITEMS'] as $key => $arItem) {
	$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], $strElementEdit);
	$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], $strElementDelete, $arElementDeleteParams);
	$strMainID = $this->GetEditAreaId($arItem['ID']);

	$arItemIDs = array(
		'ID' => $strMainID,
		'PICT' => $strMainID . '_pict',
		'SECOND_PICT' => $strMainID . '_secondpict',
		'MAIN_PROPS' => $strMainID . '_main_props',
		'QUANTITY' => $strMainID . '_quantity',
		'QUANTITY_DOWN' => $strMainID . '_quant_down',
		'QUANTITY_UP' => $strMainID . '_quant_up',
		'QUANTITY_MEASURE' => $strMainID . '_quant_measure',
		'BUY_LINK' => $strMainID . '_buy_link',
		'BASKET_ACTIONS' => $strMainID . '_basket_actions',
		'NOT_AVAILABLE_MESS' => $strMainID . '_not_avail',
		'SUBSCRIBE_LINK' => $strMainID . '_subscribe',
		'COMPARE_LINK' => $strMainID . '_compare_link',
		'PRICE' => $strMainID . '_price',
		'DSC_PERC' => $strMainID . '_dsc_perc',
		'SECOND_DSC_PERC' => $strMainID . '_second_dsc_perc',
		'PROP_DIV' => $strMainID . '_sku_tree',
		'PROP' => $strMainID . '_prop_',
		'DISPLAY_PROP_DIV' => $strMainID . '_sku_prop',
		'BASKET_PROP_DIV' => $strMainID . '_basket_prop'
	);

	$strObName = 'ob' . preg_replace("/[^a-zA-Z0-9_]/", "x", $strMainID);
	$productTitle = (
		isset($arItem['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']) && $arItem['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE'] != '' ? $arItem['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE'] : $arItem['NAME']
		);
	$imgTitle = (
		isset($arItem['IPROPERTY_VALUES']['ELEMENT_PREVIEW_PICTURE_FILE_TITLE']) && $arItem['IPROPERTY_VALUES']['ELEMENT_PREVIEW_PICTURE_FILE_TITLE'] != '' ? $arItem['IPROPERTY_VALUES']['ELEMENT_PREVIEW_PICTURE_FILE_TITLE'] : $arItem['NAME']
		);

	$minPrice = false;
	if (isset($arItem['MIN_PRICE']) || isset($arItem['RATIO_PRICE']))
		$minPrice = (isset($arItem['RATIO_PRICE']) ? $arItem['RATIO_PRICE'] : $arItem['MIN_PRICE']);
	?>
	<div class="prod_item">
		<a href="" data-id="<?= $arItem['ID']; ?>"  
	<? if ($arItem['isFavorite']) { ?>
			   class="tofavor active"></a>
	<? } else { ?>
			class="tofavor"></a>
	<? } ?>

<?/*?>
	<a href="<? //=$arItem['COMPARE_URL']?>" data-id="<?= $arItem['ID']; ?>"
		   <? if ($arItem['isCompare']) { ?>
		   class="tocompare active">в сравнении</a>
	<? } else { ?>
		class="tocompare">к сравнению</a>
	<? } ?>
<?*/?>
	<? if (($arItem['PROPERTIES']['KHIT_PRODAZH']['VALUE'] === 'Y') || ($arItem['PROPERTIES']['KHIT_PRODAZH']['VALUE'] === 'Да')): ?>
			<span class="novinka2"></span>
	   <? endif; ?>
	<? if (($arItem['PROPERTIES']['NOVINKA']['VALUE'] === 'Y') || ($arItem['PROPERTIES']['NOVINKA']['VALUE'] === 'Да')): ?>
			<span class="novinka"></span>
		<? endif; ?>
		<? if ($arItem['PROPERTIES']['ACTION']['VALUE'] === 'Y'): ?>
			<span class="novinka3"></span>
		<? endif; ?>

	<a href="<? echo $arItem['DETAIL_PAGE_URL']; ?>" class="pic_tit">
		<p class="pics"><span>
				<img src="<? echo $arItem['PREVIEW_PICTURE']['SRC']; ?>" alt="" />
			</span></p>
		<p class="title"><? echo $arItem['NAME']; ?> </p>
	</a>
	<p class="size"><? echo $arItem['PROPERTIES']['SIZE']['VALUE'] ?></p>
	<p class="art">код <?= $arItem['KOD_TOVARA']; //echo $arItem['PROPERTIES']['ARTICLE']['VALUE'] ?></p>

<?if ($USER->IsAuthorized()):?>
	<? if ($minPrice['PRINT_DISCOUNT_VALUE'] != $minPrice['PRINT_VALUE']) : ?>
		<s class="old_price"><?= $minPrice['PRINT_VALUE']; ?></s>
	<? else: ?> 
		<i class="old_price">	&nbsp; </i>
	<? endif; ?>
	<div class="new_price"><?=($minPrice)? $minPrice['PRINT_DISCOUNT_VALUE'] : '&nbsp;'; ?></div>

<?/*?>	<div class="stars"><span class="q<?= $arItem['RATING']['ocenka']; ?>"></span><a href=""><?= $arItem['RATING']['count']; ?></a></div><?*/?>

		<? if ($arItem["CATALOG_QUANTITY"]>0) : ?>	
		<a href="<? echo $arItem['ADD_URL']; ?>" data-id="<?= $arItem['ID']; ?>" class="tocart from_fav">Купить</a>
		<? else: ?>
		<a href="#zakaz_no_price" class="toorder fancybox" data-id="<?=$arItem['ID'];?>" >Заказать</a>
	<? endif; ?>
<?else:?>
	<a href="/register/" class="tocart ">Купить</a>
<?endif?>	

	</div>
	<?
	$num_items++;
}
?>

<?
	if ($arParams["DISPLAY_BOTTOM_PAGER"]) {
		//$num = $arResult['NAV_RESULT']->NavLastRecordShow - $arResult['NAV_RESULT']->NavFirstRecordShow;
		?>
	<? //PR($arResult,1);?>
	<? //PR($arResult['NAV_RESULT'],1);?>
	<div class="clear"></div>		
	<div class="pagenav">
		<br<br><span>Показано <?= $num_items; ?> товаров из <?= $arResult['NAV_RESULT']->NavRecordCount; ?></span>
	<? echo $arResult["NAV_STRING"]; ?>
	</div>
	<?
}
?>


