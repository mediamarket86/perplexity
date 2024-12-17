<?php

namespace PVP\CheckCode;

use PVP\Exchange\ErrorManager;

class CheckCode
{
    public function add(string $orderId, int $code)
    {
        return OrderCodesTable::add([
            OrderCodesTable::FIELD_ORDER_ID => $orderId,
            OrderCodesTable::FIELD_CODE => $code
        ]);
    }

    public function update(string $orderId, int $code)
    {
        $row = OrderCodesTable::getList([
            'select' => ['ID'],
            'filter' => [OrderCodesTable::FIELD_ORDER_ID => $orderId],
        ])->fetchAll();

        if (empty($row[0])) {
            ErrorManager::getInstance()->addError('Заказ не найден!');

            return false;
        }

        $rowId = $row[0]['ID'];

        return OrderCodesTable::update($rowId, [
            OrderCodesTable::FIELD_ORDER_ID => $orderId,
            OrderCodesTable::FIELD_CODE => $code
        ]);
    }

    public function check(string $orderId, int $code): bool
    {
        $result = (bool)OrderCodesTable::getCount([
            OrderCodesTable::FIELD_ORDER_ID => $orderId,
            OrderCodesTable::FIELD_CODE => $code,
        ]);

        $checkLog = new CheckLog();
        $checkLog->add($orderId, $result);

        return $result;
    }

    public function isOrderExists(string $orderId): bool
    {
        return (bool)OrderCodesTable::getCount([
            OrderCodesTable::FIELD_ORDER_ID => $orderId
        ]);
    }

}