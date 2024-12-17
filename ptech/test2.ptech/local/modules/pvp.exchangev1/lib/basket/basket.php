<?php

namespace PVP\ExchangeV1\Basket;

use Bitrix\Catalog\PriceTable;
use Bitrix\Currency\CurrencyManager;
use Bitrix\Main\Context;
use Bitrix\Sale;
use Bitrix\Iblock\ElementTable;
use Bitrix\Main\Loader;

class Basket
{
    protected $basket;


    public function __construct()
    {
        if (! Loader::includeModule('sale')) {
            Throw new \RuntimeException('Module sale not installed!');
        }

        if (! Loader::includeModule('iblock')) {
            Throw new \RuntimeException('Module iblock not installed!');
        }

        if (! Loader::includeModule('catalog')) {
            Throw new \RuntimeException('Module catalog not installed!');
        }

        $this->basket = $this->loadBasketForFUser();
    }

    public function syncMobileAppBasket(array $items)
    {
        $productsXmlIds = array_column($items, 'id');

        $products = ElementTable::getList([
            'select' => ['ID', 'XML_ID'],
            'filter' => [
//                'ACTIVE' => 'Y', //Мир еще не готов к синхронизации
                'XML_ID' => $productsXmlIds,
            ]
        ])->fetchAll();

        $externalProducts = array_column($items, null, 'id');

        $tmpAr = [];
        foreach ($products as $product) {
            $product['QUANTITY'] = $externalProducts[$product['XML_ID']]['count'];
            $product['EXT_PRICE'] = $externalProducts[$product['XML_ID']]['price'];

            $tmpAr[$product['ID']] =  $product;
        }

        $products = $tmpAr;
        unset($tmpAr);
        unset($externalProducts);

        //Sync basket with external product list
        foreach ($this->basket->getBasketItems() as $item) {
            if (empty($products[$item->getField('PRODUCT_ID')])) {
                $r = $item->delete();

                if (! $r->isSuccess()) {
                    AddMessage2Log('PRODUCT DELETE ERROR, PRODUCT_ID:' . $item->getField('PRODUCT_ID'));
                }
            } else {
                $item->setField('QUANTITY', $products[$item->getField('PRODUCT_ID')]['QUANTITY']);

                $basketPropertyCollection = $item->getPropertyCollection();

                $needMaPriceFlag = true;
                foreach ($basketPropertyCollection as $basketProperty) {
                    if ('MA_PRICE' == $basketProperty->getField('CODE')) {
                        $needMaPriceFlag = false;
                    }
                }

                if ($needMaPriceFlag) {
                    $basketProperty = $basketPropertyCollection->createItem();
                    $basketProperty->setFields([
                        'NAME' => 'Цена МП',
                        'CODE' => 'MA_PRICE',
                        'VALUE' => 'Y',
                    ]);
                }

                unset($products[$item->getField('PRODUCT_ID')]);
            }

        }

        //add new products
        foreach ($products as $product) {
                $item = $this->basket->createItem('catalog', $product['ID']);
                $item->setFields(array(
                    'QUANTITY' => $product['QUANTITY'],
                    'CURRENCY' => CurrencyManager::getBaseCurrency(),
                    'LID' => Context::getCurrent()->getSite(),
                    'PRODUCT_PROVIDER_CLASS' => 'CCatalogProductProvider',
                ));

            $basketPropertyCollection = $item->getPropertyCollection();
            $basketProperty = $basketPropertyCollection->createItem();
            $basketProperty->setFields([
                'NAME' => 'Цена МП',
                'CODE' => 'MA_PRICE',
                'VALUE' => 'Y',
            ]);

        }

        $this->basket->refresh();

        $this->basket->save();

        $this->basket = $this->loadBasketForFUser();
    }

    public function addCoupons(array $coupons)
    {
        Sale\DiscountCouponsManager::init();

        foreach ($coupons as $coupon) {
            Sale\DiscountCouponsManager::add($coupon);
        }
    }

    public function calculateDiscount()
    {
        $discounts = Sale\Discount::buildFromBasket($this->basket, new Sale\Discount\Context\Fuser($this->basket->getFUserId()));

        $discounts->calculate();

        $result = $discounts->getApplyResult(true);

        return $result;
    }

    public function getBasket()
    {
        return $this->basket;
    }

    protected function loadBasketForFUser()
    {
        return Sale\Basket::loadItemsForFUser(Sale\Fuser::getId(true), Context::getCurrent()->getSite());
    }
}