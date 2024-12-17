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
        'MODE' => ['NAME' => GetMessage("MODE_GROUP"), 'SORT' => 1],
        'PATH' => ['NAME' => GetMessage("PATH_GROUP"), 'SORT' => 1],

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
        'MODE' => array(
            'PARENT' => 'MODE',
            'NAME' => GetMessage('PARAM_MODE'),
            'TYPE' => 'LIST',
            'VALUES' => [
                'CONTROLLER' => 'Управление кнопками каталога',
                'FAVORITE' => 'Отображение избранного',
                'AJAX' => 'Ajax mode',
            ],
        ),
    ],
];

if (0 < intval($arCurrentValues['IBLOCK_TYPE']))
{
    $arIblock = [];
    $rsIblock = CIBlock::GetList(array(),array('TYPE' => $arCurrentValues['IBLOCK_TYPE']));
    while ($res = $rsIblock->Fetch()) {
        $arIblock[$res['ID']] = '[' . $res['ID'] . ']' . $res['NAME'];
    }

    $arComponentParameters['PARAMETERS']['IBLOCK_ID'] = array(
        "PARENT" => "IBLOCK",
        'NAME' => GetMessage('SELECT_IBLOCK_ID'),
        'TYPE' => 'LIST',
        'VALUES' => $arIblock,
    );
}
