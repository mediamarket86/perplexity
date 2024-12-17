<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?php
    $APPLICATION->restartBuffer();
?>
    <?php if (count($arResult['ELEMENT_IDS'])) :?>

<?php
    /**
     * Настройки загружаются в header.php, он здесь не выполняется, подгружаем сами
     */
    global $arSetting;
    $arSetting = $APPLICATION->IncludeComponent("altop:settings", "", array(), false, array("HIDE_ICONS" => "Y"));
?>

    <?php
        global $arrFilter;
        $arrFilter['ID'] = $arResult['ELEMENT_IDS'];
    ?>
    <?$APPLICATION->IncludeComponent(
        "bitrix:catalog.section",
        'filtered',
        array(
            "IBLOCK_TYPE" => $arParams['IBLOCK_TYPE'],
            "IBLOCK_ID" => $arParams['IBLOCK_ID'],
            "ELEMENT_SORT_FIELD" => "CATALOG_AVAILABLE",
            "ELEMENT_SORT_ORDER" => "DESC",
            "ELEMENT_SORT_FIELD2" => "SORT",
            "ELEMENT_SORT_ORDER2" => "ASC",
            "PROPERTY_CODE" => array(
                0 => "CML2_ARTICLE",
                1 => "NEWPRODUCT",
                2 => "DISCOUNT",
                3 => "SALELEADER",
                4 => "",
            ),
            "SET_META_KEYWORDS" => "N",
            "SET_META_DESCRIPTION" => "N",
            "SET_BROWSER_TITLE" => "N",
            "SET_LAST_MODIFIED" => "N",
            "INCLUDE_SUBSECTIONS" => "Y",
            "SHOW_ALL_WO_SECTION" => "Y",
            "BASKET_URL" => "/personal/cart/",
            "ACTION_VARIABLE" => "action",
            "PRODUCT_ID_VARIABLE" => "id",
            "SECTION_ID_VARIABLE" => "",
            "PRODUCT_QUANTITY_VARIABLE" => "quantity",
            "PRODUCT_PROPS_VARIABLE" => "prop",
            "FILTER_NAME" => "arrFilter",
            "CACHE_TYPE" => "A",
            "CACHE_TIME" => "36000000",
            "CACHE_FILTER" => "Y",
            "CACHE_GROUPS" => "Y",
            "SET_TITLE" => "N",
            "MESSAGE_404" => "",
            "SET_STATUS_404" => "N",
            "SHOW_404" => "N",
            "FILE_404" => "",
            "DISPLAY_COMPARE" => "Y",
            "LINE_ELEMENT_COUNT" => "4",
            "PRICE_CODE" => array(
                0 => "Базовая",
            ),
            "USE_PRICE_COUNT" => "Y",
            "SHOW_PRICE_COUNT" => "1",
            "PRICE_VAT_INCLUDE" => "Y",
            "USE_PRODUCT_QUANTITY" => "Y",
            "ADD_PROPERTIES_TO_BASKET" => "N",
            "PARTIAL_PRODUCT_PROPERTIES" => "N",
            "PRODUCT_PROPERTIES" => array(
            ),
            "DISPLAY_TOP_PAGER" => "N",
            "DISPLAY_BOTTOM_PAGER" => "Y",
            "PAGER_BASE_LINK" => "",
            "PAGER_BASE_LINK_ENABLE" => "Y",
            "PAGER_DESC_NUMBERING" => "N",
            "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000000",
            "PAGER_PARAMS_NAME" => "arrPager",
            "PAGER_SHOW_ALL" => "Y",
            "PAGER_SHOW_ALWAYS" => "Y",
            "PAGER_TEMPLATE" => ".default",
            "PAGER_TITLE" => "",
            "PAGE_ELEMENT_COUNT" => "8",
            "OFFERS_CART_PROPERTIES" => array(
            ),
            "OFFERS_FIELD_CODE" => array(
                0 => "NAME",
                1 => "PREVIEW_PICTURE",
                2 => "DETAIL_PICTURE",
                3 => "",
            ),
            "OFFERS_PROPERTY_CODE" => array(
                0 => "",
                1 => "COLOR",
                2 => "PROP2",
                3 => "PROP3",
                4 => "",
            ),
            "OFFERS_SORT_FIELD" => "SORT",
            "OFFERS_SORT_ORDER" => "ASC",
            "OFFERS_SORT_FIELD2" => "ID",
            "OFFERS_SORT_ORDER2" => "ASC",
            "OFFERS_LIMIT" => "100",
            "SECTION_ID" => "",
            "SECTION_CODE" => "",
            "SECTION_URL" => "",
            "DETAIL_URL" => "",
            "USE_MAIN_ELEMENT_SECTION" => "N",
            "CONVERT_CURRENCY" => "N",
            "CURRENCY_ID" => "",
            "HIDE_NOT_AVAILABLE" => "Y",
            "ADD_SECTIONS_CHAIN" => "Y",
            "COMPARE_PATH" => "/catalog/compare/",
            "BACKGROUND_IMAGE" => "",
            "DISABLE_INIT_JS_IN_COMPONENT" => "N",
            "DISPLAY_IMG_WIDTH" => "178",
            "DISPLAY_IMG_HEIGHT" => "178",
            "PROPERTY_CODE_MOD" => array(
                0 => "",
                1 => "GUARANTEE",
                2 => "",
            ),
            "SHOW_MAX_QUANTITY" => "M",
            "MESS_SHOW_MAX_QUANTITY" => "В наличии",
            "RELATIVE_QUANTITY_FACTOR" => "5",
            "MESS_RELATIVE_QUANTITY_MANY" => "много",
            "MESS_RELATIVE_QUANTITY_FEW" => "мало",
            "BUTTON_PAYMENTS_HREF" => "/payments/",
            "BUTTON_CREDIT_HREF" => "/credit/",
            "BUTTON_DELIVERY_HREF" => "/delivery/",
            "COMPONENT_TEMPLATE" => "sections",
            "SECTION_USER_FIELDS" => array(
                0 => "",
                1 => "",
            ),
            "CUSTOM_FILTER" => "",
            "HIDE_NOT_AVAILABLE_OFFERS" => "Y",
            "HIDE_SECTION" => "Y",
            "SEF_MODE" => "N",
            "AJAX_MODE" => "N",
            "AJAX_OPTION_JUMP" => "N",
            "AJAX_OPTION_STYLE" => "Y",
            "AJAX_OPTION_HISTORY" => "Y",
            "AJAX_OPTION_ADDITIONAL" => "",
            "BROWSER_TITLE" => "-",
            "META_KEYWORDS" => "-",
            "META_DESCRIPTION" => "-",
            "COMPOSITE_FRAME_MODE" => "A",
            "COMPOSITE_FRAME_TYPE" => "AUTO",
            "COMPATIBLE_MODE" => "Y",
            "TYPE" => 'table',
        ),
        $component
    );?>
<?php else : ?>
    <div class="alertMsg info">
        Товары не найдены.
    </div>
    <?php endif ?>
<?php
    die();
?>