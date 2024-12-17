<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
	die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
$templateLibrary = array('popup');
$currencyList = '';
if (!empty($arResult['CURRENCIES'])) {
	$templateLibrary[] = 'currency';
	$currencyList = CUtil::PhpToJSObject($arResult['CURRENCIES'], false, true, true);
}
$templateData = array(
	'TEMPLATE_CLASS' => 'bx_' . $arParams['TEMPLATE_THEME'],
	'TEMPLATE_LIBRARY' => $templateLibrary,
	'CURRENCIES' => $currencyList
);
unset($currencyList, $templateLibrary);

$strMainID = $this->GetEditAreaId($arResult['ID']);
$arItemIDs = array(
	'ID' => $strMainID,
	'PICT' => $strMainID . '_pict',
	'DISCOUNT_PICT_ID' => $strMainID . '_dsc_pict',
	'STICKER_ID' => $strMainID . '_sticker',
	'BIG_SLIDER_ID' => $strMainID . '_big_slider',
	'BIG_IMG_CONT_ID' => $strMainID . '_bigimg_cont',
	'SLIDER_CONT_ID' => $strMainID . '_slider_cont',
	'SLIDER_LIST' => $strMainID . '_slider_list',
	'SLIDER_LEFT' => $strMainID . '_slider_left',
	'SLIDER_RIGHT' => $strMainID . '_slider_right',
	'OLD_PRICE' => $strMainID . '_old_price',
	'PRICE' => $strMainID . '_price',
	'DISCOUNT_PRICE' => $strMainID . '_price_discount',
	'SLIDER_CONT_OF_ID' => $strMainID . '_slider_cont_',
	'SLIDER_LIST_OF_ID' => $strMainID . '_slider_list_',
	'SLIDER_LEFT_OF_ID' => $strMainID . '_slider_left_',
	'SLIDER_RIGHT_OF_ID' => $strMainID . '_slider_right_',
	'QUANTITY' => $strMainID . '_quantity',
	'QUANTITY_DOWN' => $strMainID . '_quant_down',
	'QUANTITY_UP' => $strMainID . '_quant_up',
	'QUANTITY_MEASURE' => $strMainID . '_quant_measure',
	'QUANTITY_LIMIT' => $strMainID . '_quant_limit',
	'BASIS_PRICE' => $strMainID . '_basis_price',
	'BUY_LINK' => $strMainID . '_buy_link',
	'ADD_BASKET_LINK' => $strMainID . '_add_basket_link',
	'BASKET_ACTIONS' => $strMainID . '_basket_actions',
	'NOT_AVAILABLE_MESS' => $strMainID . '_not_avail',
	'COMPARE_LINK' => $strMainID . '_compare_link',
	'PROP' => $strMainID . '_prop_',
	'PROP_DIV' => $strMainID . '_skudiv',
	'DISPLAY_PROP_DIV' => $strMainID . '_sku_prop',
	'OFFER_GROUP' => $strMainID . '_set_group_',
	'BASKET_PROP_DIV' => $strMainID . '_basket_prop',
);
$strObName = 'ob' . preg_replace("/[^a-zA-Z0-9_]/", "x", $strMainID);
$templateData['JS_OBJ'] = $strObName;

$strTitle = (
	isset($arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_TITLE"]) && $arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_TITLE"] != '' ? $arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_TITLE"] : $arResult['NAME']
	);
$strAlt = (
	isset($arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_ALT"]) && $arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_ALT"] != '' ? $arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_ALT"] : $arResult['NAME']
	);
$minPrice = false;
if (isset($arResult['MIN_PRICE']) || isset($arResult['RATIO_PRICE']))
	$minPrice = (isset($arResult['RATIO_PRICE']) ? $arResult['RATIO_PRICE'] : $arResult['MIN_PRICE']);

$favorites = isset($_SESSION['favorites']) ? $_SESSION['favorites'] : array();
$compares = get_site_values('compare_list');   // isset($_SESSION['compare_list']) ?  $_SESSION['compare_list'] : array();
$favor_active = in_array($arResult['ID'], $favorites) ? 'active' : '';
$compare_active = in_array($arResult['ID'], $compares) ? 'active' : '';
;


$min_order = $arResult['PROPERTIES']['MIN_ORDER']['VALUE'] ? $arResult['PROPERTIES']['MIN_ORDER']['VALUE'] : 1;
$arResult['PROPERTIES']['MIN_ORDER']['VALUE'] = $min_order;
$shtuk_v_upakovke = $arResult['PROPERTIES']['SHTUK_V_UPAKOVKE']['VALUE'] ? $arResult['PROPERTIES']['SHTUK_V_UPAKOVKE']['VALUE'] : 1;
$arResult['PROPERTIES']['SHTUK_V_UPAKOVKE']['VALUE'] = $shtuk_v_upakovke;

//получение кода из множественного свойства
//PR($arResult['PROPERTIES']['CML2_TRAITS']);
$kod = '';
foreach ($arResult['PROPERTIES']['CML2_TRAITS']['DESCRIPTION'] as $i => $val)
	if ($val == 'Код') {
		$kod = $arResult['PROPERTIES']['CML2_TRAITS']['VALUE'][$i];
		break;
	}

//PR($arResult,1);

?>
<div class="cart_item">
    <div class="prod_title"><?= $arResult['NAME'] ?></div>
	<!--
	<div class="novinka_wrap">
<? if (1 == 1):// ( ($arItem['PROPERTIES']['KHIT_PRODAZH']['VALUE'] === 'Y') || ($arItem['PROPERTIES']['KHIT_PRODAZH']['VALUE'] === 'Да') ): ?>
				<span class="novinka">Хит продаж</span>
	<? endif; ?>
	<? if (1 == 1):// ( ($arItem['PROPERTIES']['NOVINKA']['VALUE'] === 'Y') || ($arItem['PROPERTIES']['NOVINKA']['VALUE'] === 'Да') ):?>
				<span class="novinka">Новинка</span>
	<? endif; ?>
	<? if (1 == 1):// ($arItem['PROPERTIES']['ACTION']['VALUE'] === 'Y'):?>
				<span class="novinka">Акция</span>
	<? endif; ?>
	</div>
	-->
    <table class="tool_pic">
		<tr><td>
			<a>
				<img alt="" src="<?= $arResult['DETAIL_PICTURE_SMALL']['SRC'] ?>">
			</a>
		</td></tr>
	</table>
    <div class="tool_p2">
		<div class="stars">
			<span class="q<?= $arResult['RATING']; ?>"></span>
<? if ($arResult['RATINGS_COUNT'] > 0) { ?>
				<a href="#vk2"><?= $arResult['RATINGS_COUNT']; ?> отзывов</a>
			<? } else { ?>
				<a href="#vk2" >нет отзывов</a>
			<? } ?>
		</div>

        <p class="kod"><b>код <?= $kod; //$arResult['PROPERTIES']['CML2_ARTICLE']['VALUE']?></b></p>
        <p>Доставка по Москве от 300 руб.</p>
        <p>Самовывоз 100 руб.</p>
    </div>
    <div class="tool_p3">
		<form action="/ajax/add_to_basket.php" method="post">
<? if ($minPrice['PRINT_DISCOUNT_VALUE'] != $minPrice['PRINT_VALUE']) : ?>
				<s class="old_price"><?= $minPrice['PRINT_VALUE']; ?></s>
			<? endif; ?>
			<div class="new_price"><?= $minPrice['PRINT_DISCOUNT_VALUE']; ?></div>
			<div class="kol">минимальное кол-во</div>
			<div class="shtuk"><?= $min_order ?> шт</div>
			<div class="kol">в упаковке</div>
			<div class="shtuk"><?= $shtuk_v_upakovke ?> шт</div>
			<div class="kol">добавить</div>
			<div class="shtuk">
				<input type="text" id="QUANTITY" name="quantity" pattern="^[0-9]+$" value="<?= $min_order ?>"/>
				<input type="hidden" id="TOV_ID" name="tov_id" value="<?= $arResult['ID']; ?>"/>
				<input type="hidden" name="by_form" value="1"/>
			</div>
			<div class="clear"></div>
			<? if ($minPrice): ?>
				<input type="submit" value="в корзину" class="tocart" id="tocart_from_kard" onclick="yaCounter34220620.reachGoal('dobavlenievkorzinu'); ga('send', 'pageview',  '/dobavlenievkorzinu/'); return true;">
			<? endif; ?>
			<a class="buytoclick fancybox" href="#prod_order" onclick="yaCounter34220620.reachGoal('kupitvodinklik'); ga('send', 'pageview',  '/kupitvodinklik/'); return true;">Купить в один клик</a>

			<a class="tofavor <?= $favor_active; ?>" data-id="<?= $arResult['ID']; ?>">в закладках</a>
			<a class="tocompare <?= $compare_active; ?>" data-id="<?= $arResult['ID']; ?>">к сравнению</a>
		</form>
<br /><br /><br />
<script type="text/javascript" src="//yastatic.net/es5-shims/0.0.2/es5-shims.min.js" charset="utf-8"></script>
<script type="text/javascript" src="//yastatic.net/share2/share.js" charset="utf-8"></script>
<div class="ya-share2" data-services="vkontakte,facebook,odnoklassniki,moimir,gplus,twitter"></div>

    </div>
    <div class="clear"></div>
	
	<div class="small_pics">
<?			$renderImage0 = CFile::ResizeImageGet($arResult['DETAIL_PICTURE'], Array("width" => 1200, "height" => 1080),BX_RESIZE_IMAGE_EXACT);
			$renderImage_sm = CFile::ResizeImageGet($arResult['DETAIL_PICTURE'], Array("width" => 59, "height" => 54));
?>
<?/*?>
			<a href="<?=$renderImage0["src"]?>" class="fancybox_foto" rel="smm" id="tocl1">
				<img alt="" src="<?=$renderImage_sm['src'] ?>">
			</a>
<?*/?>
		<?foreach ($arResult['PROPERTIES']['MORE_PHOTO']['VALUE'] as $key => $prop):
			$arFile = CFile::GetFileArray($prop);
			$renderImage = CFile::ResizeImageGet($arFile, Array("width" => 1200, "height" => 1080),BX_RESIZE_IMAGE_EXACT);
			$renderImage_sm = CFile::ResizeImageGet($arFile, Array("width" => 59, "height" => 54));
			
		?>
		<a href="<?=$renderImage["src"]?>" class="fancybox_foto" rel="smm"><img src="<?=$renderImage_sm["src"]?>" alt="" ></a>
		<? endforeach?>
	</div>

    <div class="vkladki">
        <a class="active" href="#vk1">Описание</a>
        <a href="#vk2">Отзывы</a>
        <a href="#vk3">Расходные материалы</a>
        <a style="padding-right: 145px" href="#vk4">Аксессуары</a>
        <div class="clear"></div>
    </div>

    <div class="vklada" id="vk1">
        <p><?= $arResult['DETAIL_TEXT']; ?></p>
<? /*
  <p> Ударопрочный корпус. Комплектуется специальным штативом.
  Используется для выверки правильности взаимного расположения объектов на удалении. Имеет функцию
  автоматического построения плоскости для горизонтальной и вертикальной разметки в помещении. </p>
 */ ?>    

        <h2>Параметры</h2>
        <table>
            <tbody>
<?
$i = 5; //сколько видно свойств изначально
foreach ($arResult['PROPERTIES'] as $key => $prop) {
	if ((strpos($key, 'CML2') === false) &&
		(!empty($prop['VALUE'])) &&
		(!is_file_in_str($prop['VALUE'])) &&
		(!in_array($key, array('MORE_PHOTO', 'KARTINKA1', 'KARTINKA2', 'KARTINKA3', 'KARTINKA4', 'KARTINKA5', 'KARTINKA6', 'KARTINKA7', 'KARTINKA8', 'SYLKA_NA_TOVAR',
			'PODDERZHKA_1_FAYL', 'PODDERZHKA_2_FAYL', 'KARTINKA', 'FILES', 'INSTRUKTSIYA', 'VIDEO', 'NOVINKA', 'KHIT_PRODAZH', 'ACTION', 'MIN_ORDER', 'SHTUK_V_UPAKOVKE')))
	) {
		$class = '';
		if ($i-- <= 0)
			$class = 'class="hiden_row"';
		if ($prop['PROPERTY_TYPE'] == 'L') {
			if ($prop['MULTIPLE'] == 'Y') 
				$val = implode(', ', $prop['VALUE']);
			else 
				$val = $prop['VALUE'];
			//PR($prop);
		} else
			$val = $prop['VALUE'];
		echo '<tr ' . $class . ' data-code="' . $key . '" data-url="' . $prop['NAME'] . '"><td>' . $prop['NAME'] . '</td><td>' . $val . '</td></tr>'; 
		//.$key.': '      
	}
}
?>
            </tbody>
        </table>
		<? if (isset($arResult['PROPERTIES']['FILES']['VALUE']) && isset($arResult['PROPERTIES']['FILES']['VALUE'][0]) && !empty($arResult['PROPERTIES']['FILES']['VALUE'][0])) { ?>
			<p><a class="docs" href="<?= CFile::GetPath($arResult['PROPERTIES']['FILES']['VALUE'][0]) ?>"><span>Скачать инструкцию</span></a></p>
		<? } ?>
        <p class="showall"><a><span>показать все параметры</span></a></p>
        <hr>

		<? if (isset($arResult['PROPERTIES']['FILES']['VALUE']) && isset($arResult['PROPERTIES']['FILES']['VALUE'][1]) && !empty($arResult['PROPERTIES']['FILES']['VALUE'][1])) { ?>
			<h2>Документация</h2>
			<p><a class="docs" href="<?= CFile::GetPath($arResult['PROPERTIES']['FILES']['VALUE'][1]) ?>"><span>Сертификат дилера</span></a></p>
		<? } ?>

		<? if (isset($arResult['PROPERTIES']['VIDEO']['VALUE']) && strlen($arResult['PROPERTIES']['VIDEO']['VALUE'])>0) { ?>
			<h2>Видео</h2>
			<iframe width="400" height="235" src="<?= $arResult['PROPERTIES']['VIDEO']['VALUE'] ?>" frameborder="0" allowfullscreen></iframe>
		<? } ?>



        <h2>C этим товаром смотрят</h2>
<?php
/*echo "<!--";
print_r($arResult);
echo "-->";

$intSectionID = $APPLICATION->IncludeComponent(
    "malbur:catalog.section",
    "",
    array(
        "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
        "IBLOCK_ID" => $arParams["IBLOCK_ID"],
        "ELEMENT_SORT_FIELD" => $arParams["ELEMENT_SORT_FIELD"],
        "ELEMENT_SORT_ORDER" => $arParams["ELEMENT_SORT_ORDER"],
        "ELEMENT_SORT_FIELD2" => $arParams["ELEMENT_SORT_FIELD2"],
        "ELEMENT_SORT_ORDER2" => $arParams["ELEMENT_SORT_ORDER2"],
        "PROPERTY_CODE" => $arParams["LIST_PROPERTY_CODE"],
        "META_KEYWORDS" => $arParams["LIST_META_KEYWORDS"],
        "META_DESCRIPTION" => $arParams["LIST_META_DESCRIPTION"],
        "BROWSER_TITLE" => $arParams["LIST_BROWSER_TITLE"],
        "SET_LAST_MODIFIED" => $arParams["SET_LAST_MODIFIED"],
        "INCLUDE_SUBSECTIONS" => $arParams["INCLUDE_SUBSECTIONS"],
        "BASKET_URL" => $arParams["BASKET_URL"],
        "ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
        "PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
        "SECTION_ID_VARIABLE" => $arParams["SECTION_ID_VARIABLE"],
        "PRODUCT_QUANTITY_VARIABLE" => $arParams["PRODUCT_QUANTITY_VARIABLE"],
        "PRODUCT_PROPS_VARIABLE" => $arParams["PRODUCT_PROPS_VARIABLE"],
        "FILTER_NAME" => $arParams["FILTER_NAME"],
        "CACHE_TYPE" => $arParams["CACHE_TYPE"],
        "CACHE_TIME" => $arParams["CACHE_TIME"],
        "CACHE_FILTER" => $arParams["CACHE_FILTER"],
        "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
        "SET_TITLE" => $arParams["SET_TITLE"],
        "MESSAGE_404" => $arParams["MESSAGE_404"],
        "SET_STATUS_404" => $arParams["SET_STATUS_404"],
        "SHOW_404" => $arParams["SHOW_404"],
        "FILE_404" => $arParams["FILE_404"],
        "DISPLAY_COMPARE" => $arParams["USE_COMPARE"],
        "PAGE_ELEMENT_COUNT" => $arParams["PAGE_ELEMENT_COUNT"],
        "LINE_ELEMENT_COUNT" => $arParams["LINE_ELEMENT_COUNT"],
        "PRICE_CODE" => $arParams["PRICE_CODE"],
        "USE_PRICE_COUNT" => $arParams["USE_PRICE_COUNT"],
        "SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],

        "PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
        "USE_PRODUCT_QUANTITY" => $arParams['USE_PRODUCT_QUANTITY'],
        "ADD_PROPERTIES_TO_BASKET" => (isset($arParams["ADD_PROPERTIES_TO_BASKET"]) ? $arParams["ADD_PROPERTIES_TO_BASKET"] : ''),
        "PARTIAL_PRODUCT_PROPERTIES" => (isset($arParams["PARTIAL_PRODUCT_PROPERTIES"]) ? $arParams["PARTIAL_PRODUCT_PROPERTIES"] : ''),
        "PRODUCT_PROPERTIES" => $arParams["PRODUCT_PROPERTIES"],

        "DISPLAY_TOP_PAGER" => $arParams["DISPLAY_TOP_PAGER"],
        "DISPLAY_BOTTOM_PAGER" => $arParams["DISPLAY_BOTTOM_PAGER"],
        "PAGER_TITLE" => $arParams["PAGER_TITLE"],
        "PAGER_SHOW_ALWAYS" => $arParams["PAGER_SHOW_ALWAYS"],
        "PAGER_TEMPLATE" => $arParams["PAGER_TEMPLATE"],
        "PAGER_DESC_NUMBERING" => $arParams["PAGER_DESC_NUMBERING"],
        "PAGER_DESC_NUMBERING_CACHE_TIME" => $arParams["PAGER_DESC_NUMBERING_CACHE_TIME"],
        "PAGER_SHOW_ALL" => $arParams["PAGER_SHOW_ALL"],
        "PAGER_BASE_LINK_ENABLE" => $arParams["PAGER_BASE_LINK_ENABLE"],
        "PAGER_BASE_LINK" => $arParams["PAGER_BASE_LINK"],
        "PAGER_PARAMS_NAME" => $arParams["PAGER_PARAMS_NAME"],

        "OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"],
        "OFFERS_FIELD_CODE" => $arParams["LIST_OFFERS_FIELD_CODE"],
        "OFFERS_PROPERTY_CODE" => $arParams["LIST_OFFERS_PROPERTY_CODE"],
        "OFFERS_SORT_FIELD" => $arParams["OFFERS_SORT_FIELD"],
        "OFFERS_SORT_ORDER" => $arParams["OFFERS_SORT_ORDER"],
        "OFFERS_SORT_FIELD2" => $arParams["OFFERS_SORT_FIELD2"],
        "OFFERS_SORT_ORDER2" => $arParams["OFFERS_SORT_ORDER2"],
        "OFFERS_LIMIT" => $arParams["LIST_OFFERS_LIMIT"],

        "SECTION_ID" => $arResult["VARIABLES"]["SECTION_ID"],
        "SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
        "SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
        "DETAIL_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["element"],
        "USE_MAIN_ELEMENT_SECTION" => $arParams["USE_MAIN_ELEMENT_SECTION"],
        'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
        'CURRENCY_ID' => $arParams['CURRENCY_ID'],
        'HIDE_NOT_AVAILABLE' => $arParams["HIDE_NOT_AVAILABLE"],

        'LABEL_PROP' => $arParams['LABEL_PROP'],
        'ADD_PICT_PROP' => $arParams['ADD_PICT_PROP'],
        'PRODUCT_DISPLAY_MODE' => $arParams['PRODUCT_DISPLAY_MODE'],

        'OFFER_ADD_PICT_PROP' => $arParams['OFFER_ADD_PICT_PROP'],
        'OFFER_TREE_PROPS' => $arParams['OFFER_TREE_PROPS'],
        'PRODUCT_SUBSCRIPTION' => $arParams['PRODUCT_SUBSCRIPTION'],
        'SHOW_DISCOUNT_PERCENT' => $arParams['SHOW_DISCOUNT_PERCENT'],
        'SHOW_OLD_PRICE' => $arParams['SHOW_OLD_PRICE'],
        'MESS_BTN_BUY' => $arParams['MESS_BTN_BUY'],
        'MESS_BTN_ADD_TO_BASKET' => $arParams['MESS_BTN_ADD_TO_BASKET'],
        'MESS_BTN_SUBSCRIBE' => $arParams['MESS_BTN_SUBSCRIBE'],
        'MESS_BTN_DETAIL' => $arParams['MESS_BTN_DETAIL'],
        'MESS_NOT_AVAILABLE' => $arParams['MESS_NOT_AVAILABLE'],

        'TEMPLATE_THEME' => (isset($arParams['TEMPLATE_THEME']) ? $arParams['TEMPLATE_THEME'] : ''),
        "ADD_SECTIONS_CHAIN" => "Y", //"N",
        'ADD_TO_BASKET_ACTION' => $basketAction,
        'SHOW_CLOSE_POPUP' => isset($arParams['COMMON_SHOW_CLOSE_POPUP']) ? $arParams['COMMON_SHOW_CLOSE_POPUP'] : '',
        'COMPARE_PATH' => $arResult['FOLDER'].$arResult['URL_TEMPLATES']['compare']
    ),
    $component
); */

 ?>





		<div class="prod_items">

			<div class="cycle-slideshow" 
				 data-cycle-fx=carousel
				 data-cycle-timeout=10000
				 data-cycle-carousel-visible=4
				 data-cycle-next="#next"
				 data-cycle-prev="#prev"	
				 data-cycle-slides="> div.prod_item"	
				 data-allow-wrap=false		
				 style="position: relative; overflow: hidden; width: 704px;"		    
				 >				

				<a href=# id="prev" class="prev_ref"></a>
				<a href=# id="next" class="next_ref"></a>

				<?
				//список "С ЭТИМ ТОВАРОМ ТАКЖЕ СМОТРЯТ"
				//1472 - 2219
				$num = rand(10, 20);
				$favorites = array();
//				for ($i = 1; $i <= $num; $i++)
	//				$favorites[] = rand($arResult['TOVAR_ID_RANGE']['MIN'], $arResult['TOVAR_ID_RANGE']['MAX']);
					
				foreach ($arResult['PROPERTIES']['VIEW']['VALUE'] as $favor){
					$favorites[] = $favor;
				}
					
				global $arrFilter;
				$arrFilter = array();
				if (!empty($favorites)) {
					$arrFilter['ID'] = $favorites; //массив id товаров в списке закладок
					?>

					<?
					$APPLICATION->IncludeComponent(
						"malbur:catalog.section", "alsoviews", array(
						"IBLOCK_TYPE" => "1c_catalog",
						"IBLOCK_ID" => "18",
						"SECTION_ID" => "",
						"SECTION_CODE" => "",
						"SECTION_USER_FIELDS" => array(
							0 => "",
							1 => "",
						),
						"PAGE_ELEMENT_COUNT" => 100,
						"LINE_ELEMENT_COUNT" => "3",
						"ELEMENT_SORT_FIELD" => "sort",
						"ELEMENT_SORT_ORDER" => "asc",
						"ELEMENT_SORT_FIELD2" => "id",
						"ELEMENT_SORT_ORDER2" => "desc",
						"FILTER_NAME" => "arrFilter",
						"INCLUDE_SUBSECTIONS" => "Y",
						"SHOW_ALL_WO_SECTION" => "Y",
						"HIDE_NOT_AVAILABLE" => "N",
						"PROPERTY_CODE" => array(
							0 => "CML2_TRAITS",
							1 => "KHIT_PRODAZH",
							2 => "NOVINKA",
							3 => "ACTION",
							4 => "SIZE",
							5 => "MIN_ORDER",
							6 => "SHTUK_V_UPAKOVKE"
						),
						"OFFERS_LIMIT" => "5",
						"TEMPLATE_THEME" => "blue",
						"PRODUCT_SUBSCRIPTION" => "N",
						"SHOW_DISCOUNT_PERCENT" => "N",
						"SHOW_OLD_PRICE" => "N",
						"SHOW_CLOSE_POPUP" => "N",
						"MESS_BTN_BUY" => "Купить",
						"MESS_BTN_ADD_TO_BASKET" => "В корзину",
						"MESS_BTN_SUBSCRIBE" => "Подписаться",
						"MESS_BTN_DETAIL" => "Подробнее",
						"MESS_NOT_AVAILABLE" => "Нет в наличии",
						"SECTION_URL" => "",
						"DETAIL_URL" => "/catalog/#IBLOCK_CODE#/#SECTION_CODE_PATH#/#ELEMENT_CODE#/",
						"SECTION_ID_VARIABLE" => "",
						"SEF_MODE" => "Y",
						"SEF_FOLDER" => "/catalog/",
						"AJAX_MODE" => "N",
						"AJAX_OPTION_JUMP" => "N",
						"AJAX_OPTION_STYLE" => "Y",
						"AJAX_OPTION_HISTORY" => "N",
						"AJAX_OPTION_ADDITIONAL" => "",
						"CACHE_TYPE" => "N",
						"CACHE_TIME" => "36000000",
						"CACHE_GROUPS" => "Y",
						"SET_TITLE" => "Y",
						"SET_BROWSER_TITLE" => "Y",
						"BROWSER_TITLE" => "-",
						"SET_META_KEYWORDS" => "Y",
						"META_KEYWORDS" => "-",
						"SET_META_DESCRIPTION" => "Y",
						"META_DESCRIPTION" => "-",
						"SET_LAST_MODIFIED" => "N",
						"USE_MAIN_ELEMENT_SECTION" => "N",
						"ADD_SECTIONS_CHAIN" => "N",
						"CACHE_FILTER" => "N",
						"ACTION_VARIABLE" => "action",
						"PRODUCT_ID_VARIABLE" => "id",
						"PRICE_CODE" => array(
							0 => "Сайт",
						),
						"USE_PRICE_COUNT" => "N",
						"SHOW_PRICE_COUNT" => "1",
						"PRICE_VAT_INCLUDE" => "Y",
						"CONVERT_CURRENCY" => "N",
						"BASKET_URL" => "/personal/basket.php",
						"USE_PRODUCT_QUANTITY" => "N",
						"PRODUCT_QUANTITY_VARIABLE" => "",
						"ADD_PROPERTIES_TO_BASKET" => "Y",
						"PRODUCT_PROPS_VARIABLE" => "prop",
						"PARTIAL_PRODUCT_PROPERTIES" => "N",
						"PRODUCT_PROPERTIES" => array(
						),
						"ADD_TO_BASKET_ACTION" => "ADD",
						"PAGER_TEMPLATE" => "arrows",
						"DISPLAY_TOP_PAGER" => "N",
						"DISPLAY_BOTTOM_PAGER" => "N",
						"PAGER_TITLE" => "Товары",
						"PAGER_SHOW_ALWAYS" => "N",
						"PAGER_DESC_NUMBERING" => "N",
						"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
						"PAGER_SHOW_ALL" => "N",
						"PAGER_BASE_LINK_ENABLE" => "N",
						"SET_STATUS_404" => "N",
						"SHOW_404" => "N",
						"MESSAGE_404" => "",
						"ADD_PICT_PROP" => "-",
						"LABEL_PROP" => "-",
						"COMPONENT_TEMPLATE" => "alsoviews",
						"SEF_RULE" => "",
						"SECTION_CODE_PATH" => ""
						), false
					);
					?>

					<?
				} else {
					echo '<div class="empty_list">Список пуст</div>';
				}
				?>	

				<div class="clear"></div>		
			</div>			
		</div>	<!-- /prod_items-->


    </div><!--/ vk1-->

    <div class="vklada" id="vk2">


		<?
		global $arrFilter;
		$arrFilter = array();
		$arrFilter['PROPERTY_TOVARS'] = $arResult['ID'];
		?>
		<?
		$APPLICATION->IncludeComponent("bitrix:news.list", "reviews_in_cart", Array(
			"COMPONENT_TEMPLATE" => "reviews_in_cart",
			"IBLOCK_TYPE" => "structure", // Тип информационного блока (используется только для проверки)
			"IBLOCK_ID" => "19", // Код информационного блока
			"NEWS_COUNT" => "3", // Количество новостей на странице
			"SORT_BY1" => "ACTIVE_FROM", // Поле для первой сортировки новостей
			"SORT_ORDER1" => "DESC", // Направление для первой сортировки новостей
			"SORT_BY2" => "SORT", // Поле для второй сортировки новостей
			"SORT_ORDER2" => "ASC", // Направление для второй сортировки новостей
			"FILTER_NAME" => "arrFilter", // Фильтр
			"FIELD_CODE" => array(// Поля
				0 => "ID",
				1 => "NAME",
				2 => "PREVIEW_TEXT",
				3 => "DETAIL_TEXT",
				4 => "DATE_ACTIVE_FROM",
				5 => "",
			),
			"PROPERTY_CODE" => array(// Свойства
				0 => "FAILS",
				1 => "OCENKA",
				2 => "",
			),
			"CHECK_DATES" => "Y", // Показывать только активные на данный момент элементы
			"DETAIL_URL" => "", // URL страницы детального просмотра (по умолчанию - из настроек инфоблока)
			"AJAX_MODE" => "Y", // Включить режим AJAX
			"AJAX_OPTION_JUMP" => "N", // Включить прокрутку к началу компонента
			"AJAX_OPTION_STYLE" => "Y", // Включить подгрузку стилей
			"AJAX_OPTION_HISTORY" => "N", // Включить эмуляцию навигации браузера
			"AJAX_OPTION_ADDITIONAL" => "", // Дополнительный идентификатор
			"CACHE_TYPE" => "N", // Тип кеширования
			"CACHE_TIME" => "36000000", // Время кеширования (сек.)
			"CACHE_FILTER" => "N", // Кешировать при установленном фильтре
			"CACHE_GROUPS" => "Y", // Учитывать права доступа
			"PREVIEW_TRUNCATE_LEN" => "", // Максимальная длина анонса для вывода (только для типа текст)
			"ACTIVE_DATE_FORMAT" => "d.m.Y", // Формат показа даты
			"SET_TITLE" => "N", // Устанавливать заголовок страницы
			"SET_BROWSER_TITLE" => "N", // Устанавливать заголовок окна браузера
			"SET_META_KEYWORDS" => "N", // Устанавливать ключевые слова страницы
			"SET_META_DESCRIPTION" => "N", // Устанавливать описание страницы
			"SET_LAST_MODIFIED" => "N", // Устанавливать в заголовках ответа время модификации страницы
			"INCLUDE_IBLOCK_INTO_CHAIN" => "N", // Включать инфоблок в цепочку навигации
			"ADD_SECTIONS_CHAIN" => "N", // Включать раздел в цепочку навигации
			"HIDE_LINK_WHEN_NO_DETAIL" => "N", // Скрывать ссылку, если нет детального описания
			"PARENT_SECTION" => "", // ID раздела
			"PARENT_SECTION_CODE" => "", // Код раздела
			"INCLUDE_SUBSECTIONS" => "Y", // Показывать элементы подразделов раздела
			"DISPLAY_DATE" => "Y", // Выводить дату элемента
			"DISPLAY_NAME" => "Y", // Выводить название элемента
			"DISPLAY_PICTURE" => "N", // Выводить изображение для анонса
			"DISPLAY_PREVIEW_TEXT" => "Y", // Выводить текст анонса
			"PAGER_TEMPLATE" => "arrows", // Шаблон постраничной навигации
			"DISPLAY_TOP_PAGER" => "N", // Выводить над списком
			"DISPLAY_BOTTOM_PAGER" => "Y", // Выводить под списком
			"PAGER_TITLE" => "Новости", // Название категорий
			"PAGER_SHOW_ALWAYS" => "N", // Выводить всегда
			"PAGER_DESC_NUMBERING" => "N", // Использовать обратную навигацию
			"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000", // Время кеширования страниц для обратной навигации
			"PAGER_SHOW_ALL" => "N", // Показывать ссылку "Все"
			"PAGER_BASE_LINK_ENABLE" => "N", // Включить обработку ссылок
			"SET_STATUS_404" => "N", // Устанавливать статус 404
			"SHOW_404" => "N", // Показ специальной страницы
			"MESSAGE_404" => "", // Сообщение для показа (по умолчанию из компонента)
			), false
		);
		?>

		<?
		//генерация каптчи
		include_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/classes/general/captcha.php");
		$cpt = new CCaptcha();
		$captchaPass = COption::GetOptionString("main", "captcha_password", "");
		if (strlen($captchaPass) <= 0) {
			$captchaPass = randString(10);
			COption::SetOptionString("main", "captcha_password", $captchaPass);
		}
		$cpt->SetCodeCrypt($captchaPass);
		?>

        <div class="add_resp">
            <h2><span>Добавить отзыв</span></h2>
            <form method="" action="" id="add_review">
                <table>
                    <tbody><tr>
							<td>Оцените товар</td>
							<td>
								<ul class="rate">
									<li class="star "></li>
									<li class="star "></li>
									<li class="star "></li>
									<li class="star"></li>
									<li class="star"></li>
								</ul>
							</td>
						</tr>
						<tr>
							<td>Достоинства</td>
							<td><textarea class="txtar" name="dostoinstva"></textarea></td>
						</tr>
						<tr>
							<td>Недостатки</td>
							<td><textarea class="txtar" name="nedostatki"></textarea></td>
						</tr>
						<tr>
							<td>Общие впечатления</td>
							<td><textarea class="txtar" name="vpechatleniya"></textarea></td>
						</tr>
						<tr>
							<td>Имя</td>
							<td><input type="text" style="width:170px" class="inp" name="rev_fio" value=""></td>
						</tr>
						<tr class="opadd">
							<td>Введите код с картинки</td>
							<td><!--<input type="text" style="width:128px" class="inp" value="">-->
								<input id="captcha_word" name="captcha_word"  class="inp" type="text" style="width:128px"/>
							</td>
						</tr>
						<tr>
							<td><a id="captcha_refresh">обновить</a></td>
							<td> <!--<img src="/bitrix/templates/main/images/captcha.png">-->
								<input name="captcha_pass" value="<?= $captchaPass; ?>" type="hidden">
								<input name="captcha_code" value="<?= htmlspecialchars($cpt->GetCodeCrypt()); ?>" type="hidden">
								<img class="captcha_img" width="129" height="38" src="/bitrix/tools/captcha.php?captcha_code=<?= htmlspecialchars($cpt->GetCodeCrypt()); ?>">
							</td>                        
						</tr>
						<tr>
							<td></td>
							<td>
								<input type="hidden" name="ocenka" id="review_ocenka" value="0"/>
								<input type="hidden" name="product_id" value="<?= $arResult['ID']; ?>"/>
								<input type="hidden" name="product_name" value="<?= $arResult['NAME']; ?>"/>
								<input type="hidden" name="product_url" value="<?= $arResult['DETAIL_PAGE_URL']; ?>"/>
								<input type="submit" class="butt" value="отправить">
							</td>
						</tr>
                    </tbody></table>

            </form>
        </div>


    </div><!--/ vk2-->




	<div id="vk3" class="vklada">

<?
$arSortSel = get_site_param('arrSortBy');
$arItemsPerPage = get_site_param('arrPerPage');

$sort_field = "sort";
$sort_order = "asc";
$sort_select = isset($_REQUEST['sort']) ? (int) $_REQUEST['sort'] :
	( isset($_SESSION['sort_select']) ? (int) $_SESSION['sort_select'] : 1 );
if (($sort_select >= 1) || ($sort_select <= count($arSortSel))) {
	$_SESSION['sort_select'] = $sort_select;
	$sort_field = $arSortSel[$sort_select]['field'];
	$sort_order = $arSortSel[$sort_select]['order'];
}

$per_pages = isset($_REQUEST['perpage']) ? (int) $_REQUEST['perpage'] :
	( isset($_SESSION['perpage']) ? (int) $_SESSION['perpage'] : get_site_param('PerPageDefault') );
$_SESSION['perpage'] = $per_pages;
?>
		<?
		//список "РАСХОДНЫЕ МАТЕРИАЛЫ"
		$favorites = array(1745, 1479, 1484, 1485, 1854, 1855, 1856, 1857);
		global $arrFilter;
		$arrFilter = array();
		if (!empty($favorites)) {
			$arrFilter['ID'] = $favorites; //массив id товаров в списке закладок
			?>

			<?
			$APPLICATION->IncludeComponent(
				"bitrix:catalog.section", "favorites", array(
				"IBLOCK_TYPE" => "1c_catalog",
				"IBLOCK_ID" => "18",
				"SECTION_ID" => "",
				"SECTION_CODE" => "",
				"SECTION_USER_FIELDS" => array(
					0 => "",
					1 => "",
				),
				"PAGE_ELEMENT_COUNT" => $per_pages,
				"LINE_ELEMENT_COUNT" => "3",
				"ELEMENT_SORT_FIELD" => "$sort_field",
				"ELEMENT_SORT_ORDER" => "$sort_order",
				"ELEMENT_SORT_FIELD2" => "id",
				"ELEMENT_SORT_ORDER2" => "desc",
				"FILTER_NAME" => "arrFilter",
				"INCLUDE_SUBSECTIONS" => "Y",
				"SHOW_ALL_WO_SECTION" => "Y",
				"HIDE_NOT_AVAILABLE" => "N",
				"PROPERTY_CODE" => array(
					0 => "",
					1 => "",
				),
				"OFFERS_LIMIT" => "5",
				"TEMPLATE_THEME" => "blue",
				"PRODUCT_SUBSCRIPTION" => "N",
				"SHOW_DISCOUNT_PERCENT" => "N",
				"SHOW_OLD_PRICE" => "N",
				"SHOW_CLOSE_POPUP" => "N",
				"MESS_BTN_BUY" => "Купить",
				"MESS_BTN_ADD_TO_BASKET" => "В корзину",
				"MESS_BTN_SUBSCRIBE" => "Подписаться",
				"MESS_BTN_DETAIL" => "Подробнее",
				"MESS_NOT_AVAILABLE" => "Нет в наличии",
				"SECTION_URL" => "",
				"DETAIL_URL" => "/catalog/#IBLOCK_CODE#/#SECTION_CODE_PATH#/#ELEMENT_CODE#/",
				"SECTION_ID_VARIABLE" => "",
				"SEF_MODE" => "Y",
				"SEF_FOLDER" => "/catalog/",
				"AJAX_MODE" => "N",
				"AJAX_OPTION_JUMP" => "N",
				"AJAX_OPTION_STYLE" => "Y",
				"AJAX_OPTION_HISTORY" => "N",
				"AJAX_OPTION_ADDITIONAL" => "",
				"CACHE_TYPE" => "N",
				"CACHE_TIME" => "36000000",
				"CACHE_GROUPS" => "Y",
				"SET_TITLE" => "Y",
				"SET_BROWSER_TITLE" => "Y",
				"BROWSER_TITLE" => "-",
				"SET_META_KEYWORDS" => "Y",
				"META_KEYWORDS" => "-",
				"SET_META_DESCRIPTION" => "Y",
				"META_DESCRIPTION" => "-",
				"SET_LAST_MODIFIED" => "N",
				"USE_MAIN_ELEMENT_SECTION" => "N",
				"ADD_SECTIONS_CHAIN" => "N",
				"CACHE_FILTER" => "N",
				"ACTION_VARIABLE" => "action",
				"PRODUCT_ID_VARIABLE" => "id",
				"PRICE_CODE" => array(
					0 => "Сайт",
				),
				"USE_PRICE_COUNT" => "N",
				"SHOW_PRICE_COUNT" => "1",
				"PRICE_VAT_INCLUDE" => "Y",
				"CONVERT_CURRENCY" => "N",
				"BASKET_URL" => "/personal/cart/",
				"USE_PRODUCT_QUANTITY" => "N",
				"PRODUCT_QUANTITY_VARIABLE" => "",
				"ADD_PROPERTIES_TO_BASKET" => "Y",
				"PRODUCT_PROPS_VARIABLE" => "prop",
				"PARTIAL_PRODUCT_PROPERTIES" => "N",
				"PRODUCT_PROPERTIES" => array(
				),
				"ADD_TO_BASKET_ACTION" => "ADD",
				"PAGER_TEMPLATE" => "arrows",
				"DISPLAY_TOP_PAGER" => "N",
				"DISPLAY_BOTTOM_PAGER" => "Y",
				"PAGER_TITLE" => "Товары",
				"PAGER_SHOW_ALWAYS" => "N",
				"PAGER_DESC_NUMBERING" => "N",
				"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
				"PAGER_SHOW_ALL" => "N",
				"PAGER_BASE_LINK_ENABLE" => "N",
				"SET_STATUS_404" => "N",
				"SHOW_404" => "N",
				"MESSAGE_404" => "",
				"ADD_PICT_PROP" => "-",
				"LABEL_PROP" => "-",
				"COMPONENT_TEMPLATE" => "favorites",
				"SEF_RULE" => "",
				"SECTION_CODE_PATH" => ""
				), false
			);
			?>

			<?
		} else {
			echo '<div class="empty_list">Список пуст</div>';
		}
		?>





	</div><!--/ vk3-->			




	<div id="vk4" class="vklada">


<?
//список "АКСЕССУАРЫ"
$favorites = array(1742, 1469, 1434, 1475, 1824, 1825, 1836, 1837);
global $arrFilter;
$arrFilter = array();
if (!empty($favorites)) {
	$arrFilter['ID'] = $favorites; //массив id товаров в списке закладок
	?>

			<?
			$APPLICATION->IncludeComponent(
				"bitrix:catalog.section", "favorites", array(
				"IBLOCK_TYPE" => "1c_catalog",
				"IBLOCK_ID" => "18",
				"SECTION_ID" => "",
				"SECTION_CODE" => "",
				"SECTION_USER_FIELDS" => array(
					0 => "",
					1 => "",
				),
				"PAGE_ELEMENT_COUNT" => $per_pages,
				"LINE_ELEMENT_COUNT" => "3",
				"ELEMENT_SORT_FIELD" => "$sort_field",
				"ELEMENT_SORT_ORDER" => "$sort_order",
				"ELEMENT_SORT_FIELD2" => "id",
				"ELEMENT_SORT_ORDER2" => "desc",
				"FILTER_NAME" => "arrFilter",
				"INCLUDE_SUBSECTIONS" => "Y",
				"SHOW_ALL_WO_SECTION" => "Y",
				"HIDE_NOT_AVAILABLE" => "N",
				"PROPERTY_CODE" => array(
					0 => "",
					1 => "",
				),
				"OFFERS_LIMIT" => "5",
				"TEMPLATE_THEME" => "blue",
				"PRODUCT_SUBSCRIPTION" => "N",
				"SHOW_DISCOUNT_PERCENT" => "N",
				"SHOW_OLD_PRICE" => "N",
				"SHOW_CLOSE_POPUP" => "N",
				"MESS_BTN_BUY" => "Купить",
				"MESS_BTN_ADD_TO_BASKET" => "В корзину",
				"MESS_BTN_SUBSCRIBE" => "Подписаться",
				"MESS_BTN_DETAIL" => "Подробнее",
				"MESS_NOT_AVAILABLE" => "Нет в наличии",
				"SECTION_URL" => "",
				"DETAIL_URL" => "/catalog/#IBLOCK_CODE#/#SECTION_CODE_PATH#/#ELEMENT_CODE#/",
				"SECTION_ID_VARIABLE" => "",
				"SEF_MODE" => "Y",
				"SEF_FOLDER" => "/catalog/",
				"AJAX_MODE" => "N",
				"AJAX_OPTION_JUMP" => "N",
				"AJAX_OPTION_STYLE" => "Y",
				"AJAX_OPTION_HISTORY" => "N",
				"AJAX_OPTION_ADDITIONAL" => "",
				"CACHE_TYPE" => "N",
				"CACHE_TIME" => "36000000",
				"CACHE_GROUPS" => "Y",
				"SET_TITLE" => "Y",
				"SET_BROWSER_TITLE" => "Y",
				"BROWSER_TITLE" => "-",
				"SET_META_KEYWORDS" => "Y",
				"META_KEYWORDS" => "-",
				"SET_META_DESCRIPTION" => "Y",
				"META_DESCRIPTION" => "-",
				"SET_LAST_MODIFIED" => "N",
				"USE_MAIN_ELEMENT_SECTION" => "N",
				"ADD_SECTIONS_CHAIN" => "N",
				"CACHE_FILTER" => "N",
				"ACTION_VARIABLE" => "action",
				"PRODUCT_ID_VARIABLE" => "id",
				"PRICE_CODE" => array(
					0 => "Сайт",
				),
				"USE_PRICE_COUNT" => "N",
				"SHOW_PRICE_COUNT" => "1",
				"PRICE_VAT_INCLUDE" => "Y",
				"CONVERT_CURRENCY" => "N",
				"BASKET_URL" => "/personal/cart/",
				"USE_PRODUCT_QUANTITY" => "N",
				"PRODUCT_QUANTITY_VARIABLE" => "",
				"ADD_PROPERTIES_TO_BASKET" => "Y",
				"PRODUCT_PROPS_VARIABLE" => "prop",
				"PARTIAL_PRODUCT_PROPERTIES" => "N",
				"PRODUCT_PROPERTIES" => array(
				),
				"ADD_TO_BASKET_ACTION" => "ADD",
				"PAGER_TEMPLATE" => "arrows",
				"DISPLAY_TOP_PAGER" => "N",
				"DISPLAY_BOTTOM_PAGER" => "Y",
				"PAGER_TITLE" => "Товары",
				"PAGER_SHOW_ALWAYS" => "N",
				"PAGER_DESC_NUMBERING" => "N",
				"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
				"PAGER_SHOW_ALL" => "N",
				"PAGER_BASE_LINK_ENABLE" => "N",
				"SET_STATUS_404" => "N",
				"SHOW_404" => "N",
				"MESSAGE_404" => "",
				"ADD_PICT_PROP" => "-",
				"LABEL_PROP" => "-",
				"COMPONENT_TEMPLATE" => "favorites",
				"SEF_RULE" => "",
				"SECTION_CODE_PATH" => ""
				), false
			);
			?>

			<?
		} else {
			echo '<div class="empty_list">Список пуст</div>';
		}
		?>




	</div><!--/ vk4-->			

</div>
<script type="text/javascript">
	var <? echo $strObName; ?> = new JCCatalogElement(<? echo CUtil::PhpToJSObject($arJSParams, false, true); ?>);
	BX.message({
		ECONOMY_INFO_MESSAGE: '<? echo GetMessageJS('CT_BCE_CATALOG_ECONOMY_INFO'); ?>',
		BASIS_PRICE_MESSAGE: '<? echo GetMessageJS('CT_BCE_CATALOG_MESS_BASIS_PRICE') ?>',
		TITLE_ERROR: '<? echo GetMessageJS('CT_BCE_CATALOG_TITLE_ERROR') ?>',
		TITLE_BASKET_PROPS: '<? echo GetMessageJS('CT_BCE_CATALOG_TITLE_BASKET_PROPS') ?>',
		BASKET_UNKNOWN_ERROR: '<? echo GetMessageJS('CT_BCE_CATALOG_BASKET_UNKNOWN_ERROR') ?>',
		BTN_SEND_PROPS: '<? echo GetMessageJS('CT_BCE_CATALOG_BTN_SEND_PROPS'); ?>',
		BTN_MESSAGE_BASKET_REDIRECT: '<? echo GetMessageJS('CT_BCE_CATALOG_BTN_MESSAGE_BASKET_REDIRECT') ?>',
		BTN_MESSAGE_CLOSE: '<? echo GetMessageJS('CT_BCE_CATALOG_BTN_MESSAGE_CLOSE'); ?>',
		BTN_MESSAGE_CLOSE_POPUP: '<? echo GetMessageJS('CT_BCE_CATALOG_BTN_MESSAGE_CLOSE_POPUP'); ?>',
		TITLE_SUCCESSFUL: '<? echo GetMessageJS('CT_BCE_CATALOG_ADD_TO_BASKET_OK'); ?>',
		COMPARE_MESSAGE_OK: '<? echo GetMessageJS('CT_BCE_CATALOG_MESS_COMPARE_OK') ?>',
		COMPARE_UNKNOWN_ERROR: '<? echo GetMessageJS('CT_BCE_CATALOG_MESS_COMPARE_UNKNOWN_ERROR') ?>',
		COMPARE_TITLE: '<? echo GetMessageJS('CT_BCE_CATALOG_MESS_COMPARE_TITLE') ?>',
		BTN_MESSAGE_COMPARE_REDIRECT: '<? echo GetMessageJS('CT_BCE_CATALOG_BTN_MESSAGE_COMPARE_REDIRECT') ?>',
		SITE_ID: '<? echo SITE_ID; ?>'
	});
</script>

