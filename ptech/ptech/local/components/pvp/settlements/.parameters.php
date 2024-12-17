<?php

use Bitrix\Sale\Internals\OrderPropsTable;

$orderProps = OrderPropsTable::getList([
    'select' => ['ID', 'CODE', 'REQUIRED', 'NAME', 'DEFAULT_VALUE', 'PERSON_TYPE_ID'],
    'filter' => [
        'ACTIVE' => 'Y',
    ],
])->fetchAll();

$props = [];
foreach ($orderProps as $prop) {
    $props[$prop['ID']] = $prop['NAME'] . '(TYPE_ID: ' . $prop['PERSON_TYPE_ID'] . ')';
}

$arComponentParameters = [
    "GROUPS" => [
        'ORDER_PROPS' => ['NAME' => GetMessage("SELECT_ORDER_PROPS_LINK"), 'SORT' => '1'],
    ],
    'PARAMETERS' => [
        "CURRENT_DEBT" => array(
            "PARENT" => "ORDER_PROPS",
            "NAME" => GetMessage("SELECT_CURRENT_DEBT_PROP"),
            "TYPE" => "LIST",
            "VALUES" => $props,
            "MULTIPLE" => 'Y',
        ),
        "PRODUCT_LIMIT" => array(
            "PARENT" => "ORDER_PROPS",
            "NAME" => GetMessage("SELECT_PRODUCT_LIMIT_PROP"),
            "TYPE" => "LIST",
            "VALUES" => $props,
            "MULTIPLE" => 'Y',
        ),
        "REMAINING_LIMIT" => array(
            "PARENT" => "ORDER_PROPS",
            "NAME" => GetMessage("SELECT_REMAINING_LIMIT_PROP"),
            "TYPE" => "LIST",
            "VALUES" => $props,
            "MULTIPLE" => 'Y',
        ),
        "OVERDUE_DEBT" => array(
            "PARENT" => "ORDER_PROPS",
            "NAME" => GetMessage("SELECT_OVERDUE_DEBT_PROP"),
            "TYPE" => "LIST",
            "VALUES" => $props,
            "MULTIPLE" => 'Y',
        ),
    ],
];



