<?php

CModule::IncludeModule("iblock");

$dbIBlockType = CIBlockType::GetList(
    array("sort" => "asc"),
    array("ACTIVE" => "Y")
);

while ($arIBlockType = $dbIBlockType->Fetch())
{
    if ($arIBlockTypeLang = CIBlockType::GetByIDLang($arIBlockType["ID"], LANGUAGE_ID))
        $arIblockType[$arIBlockType["ID"]] = "[".$arIBlockType["ID"]."] ".$arIBlockTypeLang["NAME"];
}

$arComponentParameters = [
    "GROUPS" => [
        'DEFAULT' => ['NAME' => GetMessage("PVP_ORD_EXPRT_XLS_DEFAULT_GROUP"), 'SORT' => 0],
        'IBLOCK' => ['NAME' => GetMessage("PVP_ORD_EXPRT_XLS_IBLOCK_GROUP"), 'SORT' => 0],
    ],
    'PARAMETERS' => [
        'MODE' => array(
            'PARENT' => 'DEFAULT',
            'NAME' => GetMessage('PVP_ORD_EXPRT_XLS_MODE'),
            'TYPE' => 'LIST',
            'VALUES' => [
                'BASKET' => GetMessage('PVP_ORD_EXPRT_XLS_EXPORT_BASKER'),
                'ORDER' => GetMessage('PVP_ORD_EXPRT_XLS_EXPORT_ORDER'),
            ],
            'REFRESH' => 'Y',
        ),
        "IBLOCK_TYPE" => array(
            "PARENT" => "IBLOCK",
            "NAME" => GetMessage("PVP_ORD_EXPRT_SELECT_IBLOCK_TYPE"),
            "TYPE" => "LIST",
            //"ADDITIONAL_VALUES" => "Y",
            "VALUES" => $arIblockType,
            "REFRESH" => "Y"
        ),
    ],
];

/** @var array $arCurrentValues */
if ('ORDER' == $arCurrentValues['MODE']) {
    $arComponentParameters['PARAMETERS']['ORDER_ID'] = array(
        "PARENT" => "DEFAULT",
        'NAME' => GetMessage('PVP_ORD_EXPRT_XLS_ORDER_ID'),
        'TYPE' => 'STRING',
    );
}

if (! empty($arCurrentValues['IBLOCK_TYPE'])) {
    $arIblock = [];
    $rsIblock = CIBlock::GetList(array(),array('TYPE' => $arCurrentValues['IBLOCK_TYPE']));
    while ($res = $rsIblock->Fetch()) {
        $arIblock[$res['ID']] = '[' . $res['ID'] . ']' . $res['NAME'];
    }

    $arComponentParameters['PARAMETERS']['IBLOCK_ID'] = array(
        "PARENT" => "IBLOCK",
        'NAME' => GetMessage('PVP_ORD_EXPRT_SELECT_IBLOCK_ID'),
        'TYPE' => 'LIST',
        'VALUES' => $arIblock,
        'REFRESH' => 'Y',
    );
}

if (0 < intval($arCurrentValues['IBLOCK_ID'])) {
    $res = CIBlockProperty::GetList(
        [],
        ['IBLOCK_ID' => intval($arCurrentValues['IBLOCK_ID']), 'ACTIVE' => 'Y']
    );
    $properties = [];
    while ($prop = $res->GetNext()) {
        $properties[$prop['CODE']] = $prop['NAME'];
    }

    $arComponentParameters['PARAMETERS']['PROPERTY_CODE'] = array(
        "PARENT" => "IBLOCK",
        'NAME' => GetMessage('PVP_ORD_EXPRT_SELECT_PROPERTY_CODE'),
        'TYPE' => 'LIST',
        'VALUES' => $properties,
        'REFRESH' => 'Y',
    );
}