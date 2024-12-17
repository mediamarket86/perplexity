<?
/** @var array $arResult */

use Bitrix\Sale\Fuser;

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

/** Товар в корзине/избранном */
$fuser = Fuser::getId();
$basket = \Bitrix\Sale\Basket::loadItemsForFUser($fuser, \Bitrix\Main\Context::getCurrent()->getSite());
$productsInBasket = [];
/** @var Bitrix\Sale\BasketItem $item */
foreach ($basket->getBasketItems() as $item) {
    $productsInBasket[$item->getProductId()] = $item->getQuantity();
}

$arResult['IN_BASKET'] = isset($productsInBasket[$arResult['ID']]);
$arResult['QUANTITY_IN_BASKET'] = empty($productsInBasket[$arResult['ID']]) ? 0 : $productsInBasket[$arResult['ID']];

$productsInFavorites = [];
if (Bitrix\Main\Loader::includeModule('pvp.favorites')) {
    $favorites = new PVP\Favorites\Favorites();
    $productsInFavorites = $favorites->getForUser($USER->GetID(), $arParams['IBLOCK_ID']);
}

$arResult['IN_FAVORITES'] = in_array($arResult['ID'], $productsInFavorites);

\PVP\Exchange\Response\Response::getInstance()->setResponseData($arResult);
