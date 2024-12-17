<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$sectionIds = array_column($arResult['ITEMS'], 'IBLOCK_SECTION_ID');
$sectionIds = array_unique($sectionIds);

$dbItems = CIBlockSection::GetList(
    [],
    ['IBLOCK_ID' => $arParams['IBLOCK_ID'], 'ACTIVE' => 'Y', 'GLOBAL_ACTIVE' => 'Y', 'ID' => $sectionIds],
    false,
    ['ID', 'NAME', 'PICTURE', 'UF_LINK']
);
$sections = [];
while ($res = $dbItems->GetNext()) {
    if ((int)$res['PICTURE']) {
        $res['IMG'] = CFile::GetByID($res['PICTURE'])->Fetch();
    }

    $sections[] = $res;
}

$sections = array_column($sections, null, 'ID');

$arResult['SECTIONS'] = $sections;
//Чтобы не было ошибок при вставке в JS
foreach ($arResult['ITEMS'] as &$item) {
    $item['JS']['LONGITUDE'] = empty($item['PROPERTIES']['LONGITUDE']['VALUE']) ? 37.62209300 : (float)$item['PROPERTIES']['LONGITUDE']['VALUE'];
    $item['JS']['LATITUDE'] = empty($item['PROPERTIES']['LATITUDE']['VALUE']) ? 55.75399400 : (float)$item['PROPERTIES']['LATITUDE']['VALUE'];

    $item['JS']['PROP']['balloonContentHeader'] = empty($arResult['SECTIONS'][$item['IBLOCK_SECTION_ID']]['NAME']) ? '' : $sections[$item['IBLOCK_SECTION_ID']]['NAME'];
    $item['JS']['PROP']['clusterCaption'] = $item['JS']['PROP']['balloonContentHeader'];
    $item['JS']['PROP']['hintContent'] = empty($arResult['SECTIONS'][$item['IBLOCK_SECTION_ID']]['NAME']) ? $item['NAME'] : '<b>' . $sections[$item['IBLOCK_SECTION_ID']]['NAME'] . '</b><br>' . $item['NAME'];

    $footer = '<div>Адрес:<b> ' . $item['NAME'] . '</b></div>';
    if (! empty($item['PROPERTIES']['PHONE']['VALUE'])) {
        $footer .= '<div>Телефон:<b> ' . $item['PROPERTIES']['PHONE']['VALUE'] . '</b></div>';
    }
    if (! empty($item['PROPERTIES']['EMAIL']['VALUE'])) {
        $footer .= '<div>Email:<b> ' . $item['PROPERTIES']['EMAIL']['VALUE'] . '</b></div>';
    }

    $item['JS']['PROP']['balloonContentFooter'] = $footer;
    $item['JS']['PROP']['balloonContentBody'] =  $item['PREVIEW_TEXT'];

    if (isset($arResult['SECTIONS'][$item['IBLOCK_SECTION_ID']])) {
        $arResult['SECTIONS'][$item['IBLOCK_SECTION_ID']]['ITEMS'][] = &$item;
    }
}
?>
