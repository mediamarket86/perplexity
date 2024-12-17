<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if(!empty($arResult["NAV_RESULT"])) {
    $navParams =  array(
        "ITEM_COUNT" => $arResult["NAV_RESULT"]->NavRecordCount,
        "PAGE_COUNT" => $arResult["NAV_RESULT"]->NavPageCount,
        "PAGE_NUMBER" => $arResult["NAV_RESULT"]->NavPageNomer,
        "NavNum" => $arResult["NAV_RESULT"]->NavNum
    );
}


$resultItems = [];
foreach ($arResult['ITEMS'] as $item) {
    $resultItem = [];

    $resultItem['imageUrl'] = $item['PREVIEW_PICTURE'] ? $item['PREVIEW_PICTURE']['SRC'] : '';
    $resultItem['title'] = $item['NAME'];
    $resultItem['description'] = $item['PREVIEW_TEXT'];
    $resultItem['endpoint'] = empty($item['PROPERTIES']['ENDPOINT']['VALUE']) ? '' : $item['PROPERTIES']['ENDPOINT']['VALUE'];

    $resultItems[] = $resultItem;
}

if ($resultItems) {
    $arResult['RESPONSE']['ITEMS'] = $resultItems;
    $arResult['RESPONSE']['NAV'] = $navParams;

    $this->getComponent()->SetResultCacheKeys(array(
        "RESPONSE",
    ));
}
