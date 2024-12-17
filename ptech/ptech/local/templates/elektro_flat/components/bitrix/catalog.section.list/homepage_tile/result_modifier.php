<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if (! empty($arResult['SECTIONS'])) {
    foreach ($arResult['SECTIONS'] as $key => $section) {
        if ((int)$section['UF_SECTION_ICON']) {
            $arResult['SECTIONS'][$key]['ICON'] = CFile::GetFileArray($section['UF_SECTION_ICON']);
        }
    }
}
