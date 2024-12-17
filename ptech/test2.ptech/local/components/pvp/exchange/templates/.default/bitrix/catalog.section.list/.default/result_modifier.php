<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$formatFunction = function (array $section) {
    $result = [];
    $result['ID'] = $section['ID'];
    $result['XML_ID'] = $section['XML_ID'];
    $result['NAME'] = $section['NAME'];
    $result['SORT'] = $section['SORT'];
    $result['DESCRIPTION'] = $section['DESCRIPTION'];
    $result['IBLOCK_SECTION_ID'] = $section['IBLOCK_SECTION_ID'];
	$result['IMG'] = ($section['DETAIL_PICTURE']) ? \CFile::GetPath($section['DETAIL_PICTURE']) : null;

    if ((int)$section['UF_MOBILE_APP_IMG']) {
        $imgData = \CFile::GetByID((int)$section['UF_MOBILE_APP_IMG'])->Fetch();
        if (! empty($imgData['SRC'])) {
            $result['IMG'] = $imgData['SRC'];
        }
    }

    return $result;
};

//USE PARENT SECTION
if (isset($arResult['SECTION']['ID'])) {
    $arResult['SECTION'] = $formatFunction($arResult['SECTION']);
    $arResult['SECTION']['PARENT_XML_ID'] = null;
    if ((int)$arResult['SECTION']['IBLOCK_SECTION_ID']) {
        $parentSection = \Bitrix\Iblock\SectionTable::getById((int)$arResult['SECTION']['IBLOCK_SECTION_ID'])->fetch();
        if ($parentSection) {
            $arResult['SECTION']['PARENT_XML_ID'] = $parentSection['XML_ID'];
        }
    }
}

$tmpSectionList = [];
foreach ($arResult['SECTIONS'] as $section) {

    $tmpSectionList[$section['ID']] = $formatFunction($section);
}

$arResult['SECTIONS'] = [];
foreach ($tmpSectionList as $section) {
    $section['PARENT_XML_ID'] = null;

    if ($section['IBLOCK_SECTION_ID']) {
        if (isset($tmpSectionList[$section['IBLOCK_SECTION_ID']])) {
            $section['PARENT_XML_ID'] = $tmpSectionList[$section['IBLOCK_SECTION_ID']]['XML_ID'];
        } elseif (isset($arResult['SECTION']['ID']) && ($section['IBLOCK_SECTION_ID'] == $arResult['SECTION']['ID'])) {
            $section['PARENT_XML_ID'] = $arResult['SECTION']['XML_ID'];
        }
    }

    $arResult['SECTIONS'][] = $section;
}

//CACHE_KEYS//
$this->__component->SetResultCacheKeys(
    array_keys($arResult)
);?>