<?php
if ($USER->IsAuthorized()) {
    $arResult['ITEM']['MIN_PRICE']['RATIO_PRICE'] = round($arResult['ITEM']['MIN_PRICE']['RATIO_PRICE'], 2);
    $minPriceID = \Bitrix\Catalog\GroupTable::getList([
        'filter' => [
            'NAME' => 'Минимальная'
        ],
        'select' => [
            'ID'
        ]
    ])->fetchAll()[0]['ID'];
    $elementMinPrice = (float)\Bitrix\Catalog\PriceTable::getList([
        'filter' => [
            'PRODUCT_ID'       => $arResult['ITEM']['ID'],
            'CATALOG_GROUP_ID' => $minPriceID
        ],
        'select' => [
            'PRICE'
        ]
    ])->fetchAll()[0]['PRICE'];
    if ($elementMinPrice && $arResult['ITEM']['ITEM_PRICES'][$arResult['ITEM']['ITEM_PRICE_SELECTED']]['PRICE'] < $elementMinPrice) {
        $arResult['ITEM']['MIN_PRICE']['RATIO_PRICE'] = round($elementMinPrice, 2);
        $arResult['ITEM']['MIN_PRICE']['PRINT_RATIO_DISCOUNT'] = CurrencyFormat($arResult['ITEM']['ITEM_PRICES'][$arResult['ITEM']['ITEM_PRICE_SELECTED']]['BASE_PRICE'] - $elementMinPrice, $arResult['ITEM']['ITEM_PRICES'][$arResult['ITEM']['ITEM_PRICE_SELECTED']]['CURRENCY']);
        $arResult['ITEM']['MIN_PRICE']['PRINT_RATIO_PRICE'] = CurrencyFormat($elementMinPrice, $arResult['ITEM']['ITEM_PRICES'][$arResult['ITEM']['ITEM_PRICE_SELECTED']]['CURRENCY']);
    }
}