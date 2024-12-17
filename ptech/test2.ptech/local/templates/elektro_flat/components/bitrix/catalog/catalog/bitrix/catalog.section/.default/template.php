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

//PR( $arResult ); 
//PR($arParams);
PR('aaa');
?>

<?

$arSortSel = get_site_param('arrSortBy');
$arItemsPerPage = get_site_param('arrPerPage');

$reqSort = nfGetCurPageParam('', array('sort'), NULL, NULL);
$reqSort .= (strpos($reqSort, '?') === false ? '?' : '&') . 'sort=';

$reqPerPage = nfGetCurPageParam('', array('perpage', 'PAGEN_1'), NULL, NULL);
$reqPerPage .= (strpos($reqPerPage, '?') === false ? '?' : '&') . 'perpage=';

$sort_select = isset($_SESSION['sort_select']) ? (int) $_SESSION['sort_select'] : 1;
$per_pages = $arParams["PAGE_ELEMENT_COUNT"];


$view_type = isset($_REQUEST['view']) ? $_REQUEST['view'] :
	( isset($_SESSION['view_type']) ? $_SESSION['view_type'] : 'tile' );
$_SESSION['view_type'] = $view_type;

$favorites = isset($_SESSION['favorites']) ? $_SESSION['favorites'] : array();
$compares = get_site_values('compare_list');   // isset($_SESSION['compare_list']) ?  $_SESSION['compare_list'] : array();
//PR($compares);
foreach ($arResult['ITEMS'] as $key => $arItem) {
	$arResult['ITEMS'][$key]['isFavorite'] = in_array($arItem['ID'], $favorites);
	$arResult['ITEMS'][$key]['isCompare'] = in_array($arItem['ID'], $compares);
}
?>

<!-- сортировка и пагинация сверху -->
<script type="text/javascript">
	$(document).ready(function () {
		$(".sort-select").change(function () {
			console.log('<?= $reqSort ?>' + $('.sort-select option:selected').val());
			document.location.href = '<?= $reqSort ?>' + $('.sort-select option:selected').val();
		});
		$(".per-page").change(function () {
			console.log('<?= $reqPerPage ?>' + $('.sort-select option:selected').val());
			document.location.href = '<?= $reqPerPage ?>' + $('.per-page option:selected').val();
		});
	});
</script>
<div class="func_panel"><!--111-->
	<span class="pre">Сортировать</span>

	<select name="sort" class="sort-select">
		<?
		if ($arSortSel) {
			foreach ($arSortSel as $iSort) {
				?>
				<option value="<?= $iSort['value'] ?>"<?= ($sort_select == $iSort['value'] ? ' selected' : '') ?>><?= $iSort['capt'] ?></option>
			<?
			}
		}
		?>
	</select>	

	<span class="pre">Отображать</span>
	<select name="perpage" id="" class="per-page">
		<?
		if ($arItemsPerPage) {
			foreach ($arItemsPerPage as $val) {
				?>
				<option value="<?= $val ?>"<?= ($per_pages == $val ? ' selected' : '') ?>><?= $val ?></option>
	<?
	}
}
?>
	</select>

	<span class="pre">Вид</span>

	<div class="<?= ($view_type != 'tile') ? 'spiskom' : ''; ?>" id="fixs">
		<a href="<?= $APPLICATION->GetCurPageParam("view=tile", array("view")); ?>" class="vidlist1"></a>
		<a href="<?= $APPLICATION->GetCurPageParam("view=list", array("view")); ?>" class="vidlist2"></a>
	</div>


<?= $arResult["NAV_STRING"] ?>
	<div class="clear"></div>
</div>


<!-- товары -->
	<? $num_items = 0; ?>
	<? //if (!isset($_GET['view']) || ($_GET['view'] === 'tile')): ?>
	<? if ($view_type == 'tile'): ?>
	<div class="bx_catalog_top_home news_dop">
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
			<div class="prod_item"><div class="bx_catalog_item_container" id="<? echo $strMainID; ?>">
					   <? // PR($arItem,1);?>
					<a href="" data-id="<?= $arItem['ID']; ?>"  
					<? if ($arItem['isFavorite']) { ?>
						   class="tofavor active">в закладках</a>
					<? } else { ?>
						class="tofavor">в закладки</a>
					   <? } ?>

					<a href="<? //=$arItem['COMPARE_URL']  ?>" data-id="<?= $arItem['ID']; ?>"
					<? if ($arItem['isCompare']) { ?>
						   class="tocompare active">в сравнении</a>
						<? } else { ?>
						class="tocompare">к сравнению</a>
						<? } ?>
					<div class="novinka_wrap">
						<? if (($arItem['PROPERTIES']['KHIT_PRODAZH']['VALUE'] === 'Y') || ($arItem['PROPERTIES']['KHIT_PRODAZH']['VALUE'] === 'Да')): ?>
							<span class="novinka">Хит продаж</span>
						<? endif; ?>
						<? if (($arItem['PROPERTIES']['NOVINKA']['VALUE'] === 'Y') || ($arItem['PROPERTIES']['NOVINKA']['VALUE'] === 'Да')): ?>
							<span class="novinka">Новинка</span>
		<? endif; ?>
		<? if ($arItem['PROPERTIES']['ACTION']['VALUE'] === 'Y'): ?>
							<span class="novinka">Акция</span>
		<? endif; ?>
					</div>
					<a href="<? echo $arItem['DETAIL_PAGE_URL']; ?>" class="pic_tit">
						<p class="pics"><span><img src="<? echo $arItem['PREVIEW_PICTURE']['SRC']; ?>" alt="" /></span></p>
						<p class="title"><? echo $arItem['NAME']; ?> </p>
					</a>
					<p class="size"><? echo $arItem['PROPERTIES']['SIZE']['VALUE'] ?></p>
					<p class="art">код <? echo $arItem['KOD_TOVARA']; //$arItem['PROPERTIES']['CML2_ARTICLE']['VALUE'] ?></p>
					<? if ($minPrice['PRINT_DISCOUNT_VALUE'] != $minPrice['PRINT_VALUE']) : ?>
						<s class="old_price"><?= $minPrice['PRINT_VALUE']; ?></s>
					<? else: ?> 
						<i class="old_price">	&nbsp; </i>
					<? endif; ?>
					<div class="new_price"><?=($minPrice)? $minPrice['PRINT_DISCOUNT_VALUE'] : '&nbsp;'; ?></div>
					<div class="stars"><span class="q<?= $arItem['RATING']['ocenka']; ?>"></span><a href=""><?= $arItem['RATING']['count']; ?></a></div>

					<? if ($minPrice) : ?>
						<a href="<? echo $arItem['ADD_URL']; ?>" data-id="<?= $arItem['ID']; ?>" class="tocart from_fav">В корзину</a>
					<? else: ?>
						<a href="#zakaz_no_price" class="toorder fancybox" data-id="<?=$arItem['ID'];?>">Заказать</a>
					<? endif; ?>

				</div></div>
		<?
		$num_items++;
	}
	?>
	</div>
<? endif ?>
<? //if (isset($_GET['view']) && ($_GET['view'] === 'list')):  ?>
<? if ($view_type == 'list'): ?>
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

				$min_order = $arItem['PROPERTIES']['MIN_ORDER']['VALUE'] ? $arItem['PROPERTIES']['MIN_ORDER']['VALUE'] : 1;
				$arItem['PROPERTIES']['MIN_ORDER']['VALUE'] = $min_order;
				$shtuk_v_upakovke = $arItem['PROPERTIES']['SHTUK_V_UPAKOVKE']['VALUE'] ? $arItem['PROPERTIES']['SHTUK_V_UPAKOVKE']['VALUE'] : 1;
				$arItem['PROPERTIES']['SHTUK_V_UPAKOVKE']['VALUE'] = $shtuk_v_upakovke;
				?>
				<tr>
					<td><? echo $arItem['KOD_TOVARA']; //$arItem['PROPERTIES']['CML2_ARTICLE']['VALUE']  ?></td>
					<td><a href="<? echo $arItem['DETAIL_PAGE_URL']; ?>" ><? echo $arItem['NAME']; ?></a></td>
					<td><? echo $arItem['PROPERTIES']['SIZE']['VALUE'] ?></td>
					<td><? echo $arItem['PROPERTIES']['MIN_ORDER']['VALUE'] ?></td>
					<td><? echo $arItem['PROPERTIES']['SHTUK_V_UPAKOVKE']['VALUE'] ?></td>
					<td>
<?if($arItem['PRINT_VALUE']):?>
				<? if ($minPrice['PRINT_DISCOUNT_VALUE'] != $minPrice['PRINT_VALUE']) : ?>
					<s class="old_price"><?= $minPrice['PRINT_VALUE']; ?></s>
				<? else: ?> 
					<s class="old_price">&nbsp;</s>
				<? endif; ?> 
<?endif?>				
			<div class="new_price"><?=($minPrice)? $minPrice['PRINT_DISCOUNT_VALUE'] : '&nbsp;'; ?></div>
		</td>
		<td><input type="text" class="inp" placeholder="<? echo $arItem['PROPERTIES']['SHTUK_V_UPAKOVKE']['VALUE'] ?>"></td>
		<td>
			<? if ($minPrice) : ?>
				<input type="submit" class="butt tocart from_fav" data-id="<?= $arItem['ID']; ?>" value="в корзину">
			<? else: ?>
				<a href="#zakaz_no_price" class="toorder fancybox" data-id="<?=$arItem['ID'];?>">Заказать</a>
		<? endif; ?>
		</td>
		</tr>
		<?
		$num_items++;
	}
	?>
	</tfoot>
	</table>
<? endif ?>
<div style="clear: both;"></div>
<?
if ($arParams["DISPLAY_BOTTOM_PAGER"]) {
	//$num = $arResult['NAV_RESULT']->NavLastRecordShow - $arResult['NAV_RESULT']->NavFirstRecordShow;
	?>
		<? //PR($arResult['NAV_RESULT'],1);  ?>
	<div class="pagenav">
		<span>Показано <?= $num_items; ?> товаров из <?= $arResult['NAV_RESULT']->NavRecordCount; ?></span>
	<? echo $arResult["NAV_STRING"]; ?>
	</div>
	<?
}
?>
 

