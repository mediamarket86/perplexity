<?

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
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
//global $arResult;
//$arResult["VARIABLES"]["SECTION_ID"] = 0;
//PR($arResult);
//PR($arParams);
//include "section.php";


$this->setFrameMode(true);
//$this->addExternalCss("/bitrix/css/main/bootstrap.css");
?>
<?/*  $APPLICATION->IncludeComponent(
  "bitrix:catalog.section.list",
  "image-a",
  array(
  "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
  "IBLOCK_ID" => $arParams["IBLOCK_ID"],
  "CACHE_TYPE" => $arParams["CACHE_TYPE"],
  "CACHE_TIME" => $arParams["CACHE_TIME"],
  "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
  "COUNT_ELEMENTS" => $arParams["SECTION_COUNT_ELEMENTS"],
  "TOP_DEPTH" => $arParams["SECTION_TOP_DEPTH"],
  "SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
  "VIEW_MODE" => "TILE",
  "SHOW_PARENT_NAME" => 'N',
  "HIDE_SECTION_NAME" => (isset($arParams["SECTIONS_HIDE_SECTION_NAME"]) ? $arParams["SECTIONS_HIDE_SECTION_NAME"] : "N"),
  "ADD_SECTIONS_CHAIN" => "N"
  ),
  $component,
  array("HIDE_ICONS" => "Y")
  );*/ 
?>

<?

/*
  if($arParams["USE_COMPARE"]=="Y" && false)
  {
  ?><?$APPLICATION->IncludeComponent(
  "bitrix:catalog.compare.list",
  "",
  array(
  "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
  "IBLOCK_ID" => $arParams["IBLOCK_ID"],
  "NAME" => $arParams["COMPARE_NAME"],
  "DETAIL_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["element"],
  "COMPARE_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["compare"],
  "ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
  "PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
  'POSITION_FIXED' => isset($arParams['COMPARE_POSITION_FIXED']) ? $arParams['COMPARE_POSITION_FIXED'] : '',
  'POSITION' => isset($arParams['COMPARE_POSITION']) ? $arParams['COMPARE_POSITION'] : ''
  ),
  $component,
  array("HIDE_ICONS" => "Y")
  );?><?
  }
 */

//фильтр
$arFilter = array(
	"ACTIVE" => "Y",
	"GLOBAL_ACTIVE" => "Y",
	"IBLOCK_ID" => $arParams["IBLOCK_ID"],
	//"=CODE" => 
);
if (strlen($arResult["VARIABLES"]["SECTION_CODE"]) > 0) {
	$arFilter["=CODE"] = $arResult["VARIABLES"]["SECTION_CODE"];
} elseif ($arResult["VARIABLES"]["SECTION_ID"] > 0) {
	$arFilter["ID"] = $arResult["VARIABLES"]["SECTION_ID"];
}

$obCache = new CPHPCache;
if ($obCache->InitCache(36000, serialize($arFilter), "/iblock/catalog")) {
	$arCurSection = $obCache->GetVars();
} else {
	$arCurSection = array();
	$dbRes = CIBlockSection::GetList(array(), $arFilter, false, array("ID"));
	$dbRes = new CIBlockResult($dbRes);

	if (defined("BX_COMP_MANAGED_CACHE")) {
		global $CACHE_MANAGER;
		$CACHE_MANAGER->StartTagCache("/iblock/catalog");

		if ($arCurSection = $dbRes->GetNext()) {
			$CACHE_MANAGER->RegisterTag("iblock_id_" . $arParams["IBLOCK_ID"]);
		}
		$CACHE_MANAGER->EndTagCache();
	} else {
		if (!$arCurSection = $dbRes->GetNext())
			$arCurSection = array();
	}

	$obCache->EndDataCache($arCurSection);
}


$APPLICATION->IncludeComponent(
	"bitrix:catalog.smart.filter", "visual_horizontal", array(
	"COMPONENT_TEMPLATE" => "visual_horizontal",
	"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
	"IBLOCK_ID" => $arParams["IBLOCK_ID"],
	"SECTION_ID" => $arResult["VARIABLES"]["SECTION_ID"],
	"SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
	"INCLUDE_SUBSECTIONS" => $arParams["INCLUDE_SUBSECTIONS"],
	"FILTER_NAME" => "arrFilter",
	"HIDE_NOT_AVAILABLE" => "N",
	"TEMPLATE_THEME" => "blue",
	"FILTER_VIEW_MODE" => "horizontal",
	"DISPLAY_ELEMENT_COUNT" => "Y",
	"SEF_MODE" => "Y",
	"CACHE_TYPE" => "N",
	"CACHE_TIME" => "36000000",
	"CACHE_GROUPS" => "Y",
	"SAVE_IN_SESSION" => "N",
	"INSTANT_RELOAD" => "N",
	"PAGER_PARAMS_NAME" => "arrPager",
	"PRICE_CODE" => array(
		0 => "Сайт",
	),
	"CONVERT_CURRENCY" => "Y",
	"XML_EXPORT" => "N",
	"SECTION_TITLE" => "-",
	"SECTION_DESCRIPTION" => "-",
	"POPUP_POSITION" => "left",
	"SEF_RULE" => "/catalog/#SECTION_CODE_PATH#/filter/#SMART_FILTER_PATH#/apply/",
	"SECTION_CODE_PATH" => "",
	"SMART_FILTER_PATH" => $_REQUEST["SMART_FILTER_PATH"],
	"CURRENCY_ID" => "RUB"
	), false
);


//список всех товаров
$arSortSel = get_site_param('arrSortBy');
$arItemsPerPage = get_site_param('arrPerPage');

$sort_field = "CATALOG_QUANTITY";
$sort_order = "desc";
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

global $arrFilter;
if ( isset($arParams['MY_PARAM_NOVINKI'])  &&  $arParams['MY_PARAM_NOVINKI']=='Y' ) 
	$arrFilter['=PROPERTY_SKIDKA_VALUE'] = 'Да';
//PR($arrFilter);
?>

<?
//PR('aaa');

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
	"ELEMENT_SORT_FIELD" => $sort_field,
	"ELEMENT_SORT_ORDER" => $sort_order,
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
	"DETAIL_URL" => "/product/#ELEMENT_CODE#/",
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

/*
  if  ($arParams["SHOW_TOP_ELEMENTS"]!="N")
  {
  if (isset($arParams['USE_COMMON_SETTINGS_BASKET_POPUP']) && $arParams['USE_COMMON_SETTINGS_BASKET_POPUP'] == 'Y')
  {
  $basketAction = (isset($arParams['COMMON_ADD_TO_BASKET_ACTION']) ? $arParams['COMMON_ADD_TO_BASKET_ACTION'] : '');
  }
  else
  {
  $basketAction = (isset($arParams['TOP_ADD_TO_BASKET_ACTION']) ? $arParams['TOP_ADD_TO_BASKET_ACTION'] : '');
  }

  ?><?$APPLICATION->IncludeComponent(
  "bitrix:catalog.top",
  "",
  array(
  "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
  "IBLOCK_ID" => $arParams["IBLOCK_ID"],
  "ELEMENT_SORT_FIELD" => $arParams["TOP_ELEMENT_SORT_FIELD"],
  "ELEMENT_SORT_ORDER" => $arParams["TOP_ELEMENT_SORT_ORDER"],
  "ELEMENT_SORT_FIELD2" => $arParams["TOP_ELEMENT_SORT_FIELD2"],
  "ELEMENT_SORT_ORDER2" => $arParams["TOP_ELEMENT_SORT_ORDER2"],
  "SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
  "DETAIL_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["element"],
  "BASKET_URL" => $arParams["BASKET_URL"],
  "ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
  "PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
  "SECTION_ID_VARIABLE" => $arParams["SECTION_ID_VARIABLE"],
  "PRODUCT_QUANTITY_VARIABLE" => $arParams["PRODUCT_QUANTITY_VARIABLE"],
  "PRODUCT_PROPS_VARIABLE" => $arParams["PRODUCT_PROPS_VARIABLE"],
  "DISPLAY_COMPARE" => $arParams["USE_COMPARE"],
  "ELEMENT_COUNT" => $arParams["TOP_ELEMENT_COUNT"],
  "LINE_ELEMENT_COUNT" => $arParams["TOP_LINE_ELEMENT_COUNT"],
  "PROPERTY_CODE" => $arParams["TOP_PROPERTY_CODE"],
  "PRICE_CODE" => $arParams["PRICE_CODE"],
  "USE_PRICE_COUNT" => $arParams["USE_PRICE_COUNT"],
  "SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],
  "PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
  "PRICE_VAT_SHOW_VALUE" => $arParams["PRICE_VAT_SHOW_VALUE"],
  "USE_PRODUCT_QUANTITY" => $arParams['USE_PRODUCT_QUANTITY'],
  "ADD_PROPERTIES_TO_BASKET" => (isset($arParams["ADD_PROPERTIES_TO_BASKET"]) ? $arParams["ADD_PROPERTIES_TO_BASKET"] : ''),
  "PARTIAL_PRODUCT_PROPERTIES" => (isset($arParams["PARTIAL_PRODUCT_PROPERTIES"]) ? $arParams["PARTIAL_PRODUCT_PROPERTIES"] : ''),
  "PRODUCT_PROPERTIES" => $arParams["PRODUCT_PROPERTIES"],
  "CACHE_TYPE" => $arParams["CACHE_TYPE"],
  "CACHE_TIME" => $arParams["CACHE_TIME"],
  "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
  "OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"],
  "OFFERS_FIELD_CODE" => $arParams["TOP_OFFERS_FIELD_CODE"],
  "OFFERS_PROPERTY_CODE" => $arParams["TOP_OFFERS_PROPERTY_CODE"],
  "OFFERS_SORT_FIELD" => $arParams["OFFERS_SORT_FIELD"],
  "OFFERS_SORT_ORDER" => $arParams["OFFERS_SORT_ORDER"],
  "OFFERS_SORT_FIELD2" => $arParams["OFFERS_SORT_FIELD2"],
  "OFFERS_SORT_ORDER2" => $arParams["OFFERS_SORT_ORDER2"],
  "OFFERS_LIMIT" => $arParams["TOP_OFFERS_LIMIT"],
  'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
  'CURRENCY_ID' => $arParams['CURRENCY_ID'],
  'HIDE_NOT_AVAILABLE' => $arParams['HIDE_NOT_AVAILABLE'],
  'VIEW_MODE' => (isset($arParams['TOP_VIEW_MODE']) ? $arParams['TOP_VIEW_MODE'] : ''),
  'ROTATE_TIMER' => (isset($arParams['TOP_ROTATE_TIMER']) ? $arParams['TOP_ROTATE_TIMER'] : ''),
  'TEMPLATE_THEME' => (isset($arParams['TEMPLATE_THEME']) ? $arParams['TEMPLATE_THEME'] : ''),
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
  'ADD_TO_BASKET_ACTION' => $basketAction,
  'SHOW_CLOSE_POPUP' => isset($arParams['COMMON_SHOW_CLOSE_POPUP']) ? $arParams['COMMON_SHOW_CLOSE_POPUP'] : '',
  'COMPARE_PATH' => $arResult['FOLDER'].$arResult['URL_TEMPLATES']['compare']
  ),
  $component
  );?><?
  unset($basketAction);
  }

 */
?>

