<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

foreach ($arResult['ITEMS'] as $key => $item) {
    if (empty($item['PROPERTIES']['BACKGROUND']['VALUE'])) {
        continue;
    }

    $arResult['ITEMS'][$key]['BACKGROUND_IMG'] = CFile::GetByID($item['PROPERTIES']['BACKGROUND']['VALUE'])->Fetch();
}
