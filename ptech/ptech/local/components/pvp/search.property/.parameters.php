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
        'IBLOCK' => ['NAME' => GetMessage("IBLOCK_GROUP"), 'SORT' => 0],
    ],
    'PARAMETERS' => [
        "IBLOCK_TYPE" => array(
            "PARENT" => "IBLOCK",
            "NAME" => GetMessage("SELECT_IBLOCK_TYPE_ID"),
            "TYPE" => "LIST",
            //"ADDITIONAL_VALUES" => "Y",
            "VALUES" => $arIblockType,
            "REFRESH" => "Y"
        ),
    ],
];

if (0 < intval($arCurrentValues['IBLOCK_TYPE'])) {
    $arIblock = [];
    $rsIblock = CIBlock::GetList(array(),array('TYPE' => $arCurrentValues['IBLOCK_TYPE_ID']));
    while ($res = $rsIblock->Fetch()) {
        $arIblock[$res['ID']] = '[' . $res['ID'] . ']' . $res['NAME'];
    }

    $arComponentParameters['PARAMETERS']['IBLOCK_ID'] = array(
        "PARENT" => "IBLOCK",
        'NAME' => GetMessage('SELECT_IBLOCK_ID'),
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
        'NAME' => GetMessage('SELECT_PROPERTY_CODE'),
        'TYPE' => 'LIST',
        'VALUES' => $properties,
        'REFRESH' => 'Y',
    );
}