<?php use Bitrix\Sale\Fuser;
/** @var array $arResult */
/** @var array $arParams */
/** @var \CUser $USER */
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/** Товар в корзине/избранном */
$fuser = Fuser::getId();
$basket = \Bitrix\Sale\Basket::loadItemsForFUser($fuser, \Bitrix\Main\Context::getCurrent()->getSite());
$productsInBasket = [];
/** @var Bitrix\Sale\BasketItem $item */
foreach ($basket->getBasketItems() as $item) {
    $productsInBasket[$item->getProductId()] = $item->getQuantity();
}

$productsInFavorites = [];
if (Bitrix\Main\Loader::includeModule('pvp.favorites')) {
    $favorites = new PVP\Favorites\Favorites();
    $productsInFavorites = $favorites->getForUser($USER->GetID(), $arParams['IBLOCK_ID']);
}

foreach ($arResult['RESPONSE']['ITEMS'] as $key => $item) {
    $arResult['RESPONSE']['ITEMS'][$key]['IN_BASKET'] = isset($productsInBasket[$item['ID']]);
    $arResult['RESPONSE']['ITEMS'][$key]['QUANTITY_IN_BASKET'] = empty($productsInBasket[$item['ID']]) ? 0 : $productsInBasket[$item['ID']];

    $arResult['RESPONSE']['ITEMS'][$key]['IN_FAVORITES'] = in_array($item['ID'], $productsInFavorites);
}

$arResult['RESPONSE']['COUNT'] = is_array($arResult['RESPONSE']['ITEMS']) ? count($arResult['RESPONSE']['ITEMS']) : 0;

if (isset($arResult['RESPONSE'])) {
    \PVP\Exchange\Response\Response::getInstance()->setResponseData($arResult['RESPONSE']);
}
