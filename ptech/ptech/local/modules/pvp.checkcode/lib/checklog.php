<?php

namespace PVP\CheckCode;

class CheckLog
{
    public function add(string $orderId, bool $result)
    {
        return OrderChecksTable::add([
           OrderChecksTable::FIELD_ORDER_ID => $orderId,
           OrderChecksTable::FIELD_RESULT => (int)$result,
        ]);
    }
}