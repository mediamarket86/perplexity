<?php

namespace PVP\Exchange\Import\Catalog;

use Bitrix\Catalog\Model\Product;
use Bitrix\Catalog\ProductTable;
use PVP\Exchange\ErrorManager;
use PVP\Exchange\Import\Iblock\Element;

class CatalogProduct
{
    public function add(array $data)
    {
        if (empty($data['IBLOCK_ID'])) {
            ErrorManager::getInstance()->addError('Не найден ID Инфоблока');

            return;
        }

        if (empty($data['FIELDS']['ID']) && isset($data['XML_ID'])) {
            $data['FIELDS']['ID'] = Element::xmlIdToId($data['IBLOCK_ID'], $data['XML_ID'], true);
        }

        if (empty($data['FIELDS']['ID'])) {
            ErrorManager::getInstance()->addError('Не найден ID элемента');
        }

        //FIX - Повтора создания товара, коллизия очереди позволяющая добавить несколько заданий на создание одного товара
        if (ProductTable::getCount(['ID' => $data['FIELDS']['ID']])) {
            ErrorManager::getInstance()->addError("Товар с ID: " . $data['FIELDS']['ID'] . " уже существует!");

            return;
        }

        $result = Product::add($data['FIELDS']);

        if (! $result->isSuccess()) {
            ErrorManager::getInstance()->addError($result->getErrorMessages());
        }
    }

    public function update(array $data)
    {
        if (empty($data['ID'])) {
            ErrorManager::getInstance()->addError('Не найден ID элемента');
        }

        $result = Product::update($data['ID'], $data['FIELDS']);

        if (! $result->isSuccess()) {
            ErrorManager::getInstance()->addError($result->getErrorMessages());
        }
    }
}