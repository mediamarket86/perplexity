<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
use Bitrix\Main\Type\Collection;

$arParams['TEMPLATE_THEME'] = (string)($arParams['TEMPLATE_THEME']);
if ($arParams['TEMPLATE_THEME'] != '')
{
	$arParams['TEMPLATE_THEME'] = preg_replace('/[^a-zA-Z0-9_\-\(\)\!]/', '', $arParams['TEMPLATE_THEME']);
	if ($arParams['TEMPLATE_THEME'] == 'site')
	{
		$arParams['TEMPLATE_THEME'] = COption::GetOptionString('main', 'wizard_eshop_adapt_theme_id', 'blue', SITE_ID);
	}
	if ($arParams['TEMPLATE_THEME'] != '')
	{
		if (!is_file($_SERVER['DOCUMENT_ROOT'].$this->GetFolder().'/themes/'.$arParams['TEMPLATE_THEME'].'/style.css'))
			$arParams['TEMPLATE_THEME'] = '';
	}
}
if ($arParams['TEMPLATE_THEME'] == '')
	$arParams['TEMPLATE_THEME'] = 'blue';

$arResult['ALL_FIELDS'] = array();
$existShow = !empty($arResult['SHOW_FIELDS']);
$existDelete = !empty($arResult['DELETED_FIELDS']);
if ($existShow || $existDelete)
{
	if ($existShow)
	{
		foreach ($arResult['SHOW_FIELDS'] as $propCode)
		{
			
			$arResult['SHOW_FIELDS'][$propCode] = array(
				'CODE' => $propCode,
				'IS_DELETED' => 'N',
				'ACTION_LINK' => str_replace('#CODE#', $propCode, $arResult['~DELETE_FEATURE_FIELD_TEMPLATE']),
				'SORT' => $arResult['FIELDS_SORT'][$propCode]
			);
		}
		unset($propCode);
		$arResult['ALL_FIELDS'] = $arResult['SHOW_FIELDS'];
	}
	if ($existDelete)
	{
		foreach ($arResult['DELETED_FIELDS'] as $propCode)
		{
			$arResult['ALL_FIELDS'][$propCode] = array(
				'CODE' => $propCode,
				'IS_DELETED' => 'Y',
				'ACTION_LINK' => str_replace('#CODE#', $propCode, $arResult['~ADD_FEATURE_FIELD_TEMPLATE']),
				'SORT' => $arResult['FIELDS_SORT'][$propCode]
			);
		}
		unset($propCode, $arResult['DELETED_FIELDS']);
	}
	Collection::sortByColumn($arResult['ALL_FIELDS'], array('SORT' => SORT_ASC));
}

$arResult['ALL_PROPERTIES'] = array();
$existShow = !empty($arResult['SHOW_PROPERTIES']);
$existDelete = !empty($arResult['DELETED_PROPERTIES']);
if ($existShow || $existDelete)
{
	if ($existShow)
	{
		foreach ($arResult['SHOW_PROPERTIES'] as $propCode => $arProp)
		{
			$arResult['SHOW_PROPERTIES'][$propCode]['IS_DELETED'] = 'N';
			$arResult['SHOW_PROPERTIES'][$propCode]['ACTION_LINK'] = str_replace('#CODE#', $propCode, $arResult['~DELETE_FEATURE_PROPERTY_TEMPLATE']);
		}
		$arResult['ALL_PROPERTIES'] = $arResult['SHOW_PROPERTIES'];
	}
	unset($arProp, $propCode);
	if ($existDelete)
	{
		foreach ($arResult['DELETED_PROPERTIES'] as $propCode => $arProp)
		{
			$arResult['DELETED_PROPERTIES'][$propCode]['IS_DELETED'] = 'Y';
			$arResult['DELETED_PROPERTIES'][$propCode]['ACTION_LINK'] = str_replace('#CODE#', $propCode, $arResult['~ADD_FEATURE_PROPERTY_TEMPLATE']);
			$arResult['ALL_PROPERTIES'][$propCode] = $arResult['DELETED_PROPERTIES'][$propCode];
		}
		unset($arProp, $propCode, $arResult['DELETED_PROPERTIES']);
	}
	Collection::sortByColumn($arResult["ALL_PROPERTIES"], array('SORT' => SORT_ASC, 'ID' => SORT_ASC));
}

$arResult["ALL_OFFER_FIELDS"] = array();
$existShow = !empty($arResult["SHOW_OFFER_FIELDS"]);
$existDelete = !empty($arResult["DELETED_OFFER_FIELDS"]);
if ($existShow || $existDelete)
{
	if ($existShow)
	{
		foreach ($arResult["SHOW_OFFER_FIELDS"] as $propCode)
		{
			$arResult["SHOW_OFFER_FIELDS"][$propCode] = array(
				"CODE" => $propCode,
				"IS_DELETED" => "N",
				"ACTION_LINK" => str_replace('#CODE#', $propCode, $arResult['~DELETE_FEATURE_OF_FIELD_TEMPLATE']),
				'SORT' => $arResult['FIELDS_SORT'][$propCode]
			);
		}
		unset($propCode);
		$arResult['ALL_OFFER_FIELDS'] = $arResult['SHOW_OFFER_FIELDS'];
	}
	if ($existDelete)
	{
		foreach ($arResult['DELETED_OFFER_FIELDS'] as $propCode)
		{
			$arResult['ALL_OFFER_FIELDS'][$propCode] = array(
				"CODE" => $propCode,
				"IS_DELETED" => "Y",
				"ACTION_LINK" => str_replace('#CODE#', $propCode, $arResult['~ADD_FEATURE_OF_FIELD_TEMPLATE']),
				'SORT' => $arResult['FIELDS_SORT'][$propCode]
			);
		}
		unset($propCode, $arResult['DELETED_OFFER_FIELDS']);
	}
	Collection::sortByColumn($arResult['ALL_OFFER_FIELDS'], array('SORT' => SORT_ASC));
}

$arResult['ALL_OFFER_PROPERTIES'] = array();
$existShow = !empty($arResult["SHOW_OFFER_PROPERTIES"]);
$existDelete = !empty($arResult["DELETED_OFFER_PROPERTIES"]);
if ($existShow || $existDelete)
{
	if ($existShow)
	{
		foreach ($arResult['SHOW_OFFER_PROPERTIES'] as $propCode => $arProp)
		{
			$arResult["SHOW_OFFER_PROPERTIES"][$propCode]["IS_DELETED"] = "N";
			$arResult["SHOW_OFFER_PROPERTIES"][$propCode]["ACTION_LINK"] = str_replace('#CODE#', $propCode, $arResult['~DELETE_FEATURE_OF_PROPERTY_TEMPLATE']);
		}
		unset($arProp, $propCode);
		$arResult['ALL_OFFER_PROPERTIES'] = $arResult['SHOW_OFFER_PROPERTIES'];
	}
	if ($existDelete)
	{
		foreach ($arResult['DELETED_OFFER_PROPERTIES'] as $propCode => $arProp)
		{
			$arResult["DELETED_OFFER_PROPERTIES"][$propCode]["IS_DELETED"] = "Y";
			$arResult["DELETED_OFFER_PROPERTIES"][$propCode]["ACTION_LINK"] = str_replace('#CODE#', $propCode, $arResult['~ADD_FEATURE_OF_PROPERTY_TEMPLATE']);
			$arResult['ALL_OFFER_PROPERTIES'][$propCode] = $arResult["DELETED_OFFER_PROPERTIES"][$propCode];
		}
		unset($arProp, $propCode, $arResult['DELETED_OFFER_PROPERTIES']);
	}
	Collection::sortByColumn($arResult['ALL_OFFER_PROPERTIES'], array('SORT' => SORT_ASC, 'ID' => SORT_ASC));
}


$arResult["SECTIONS_INFO"] = array();
if (count($arResult["ITEMS"])>0) {
    $arCat_IDS = array();
    foreach ($arResult["ITEMS"] as $itm) {
        $arCat_IDS[] = $itm['IBLOCK_SECTION_ID'];
    }    
    $arCat_IDS = array_unique($arCat_IDS);
    
    foreach ($arCat_IDS as $cat_id) {
        $name = ''; 
        $nav = CIBlockSection::GetNavChain(false,$cat_id);
        while($arSectionPath = $nav->GetNext()){
            if ($name!='') $name .= '-';
            $name .= $arSectionPath["NAME"];
            //PR($arSectionPath);
        }
        $arResult["SECTIONS_INFO"][$cat_id] = array(
          'id' => $cat_id,
          'name' => $name
        );  
    }
}
//PR($arResult["SECTIONS_INFO"]);

//если выбрана категория
if (isset($_REQUEST['sel_cat'])) {
    $sel_cat = (int)$_REQUEST['sel_cat'];
    $arResult["SECTION_SELECTED"] = $sel_cat;
    //то удалить товары всех других категорий
    if ($sel_cat>0)
    foreach ($arResult["ITEMS"] as $k=>$itm) {
        //PR($itm['IBLOCK_SECTION_ID']);
        if ((int)$itm['IBLOCK_SECTION_ID']!=$sel_cat) {
            //PR('remove'.$k);    
            //remove   
            unset($arResult['ITEMS'][$k]);
        }  
    }
}


//убрать свойства со значениями имен файлов 
$exts = array('.jpg', '.png', '.gif', '.pdf', '.doc' );
$props_codes = array();

foreach ($arResult["SHOW_PROPERTIES"] as $code => $arProperty) 
if (!in_array($code, $props_codes)) 
{		
	$is_file = false;
	foreach($arResult["ITEMS"] as $k => &$arElement) {
		$val = $arElement["DISPLAY_PROPERTIES"][$code]["VALUE"];
		if (is_array($val))
		{
			sort($val);
			$val = implode(" / ", $val);
		}
		foreach ($exts as $ext)
			if (strpos($val, $ext)) {
				$is_file = true; 
				break;
			}
		if ($is_file) {
			if (!in_array($code, $props_codes)) {
				$props_codes[] = $code;
				break;
			}
		}
	}
	
	if ($is_file) 
		unset($arResult['SHOW_PROPERTIES'][$code]);
	/*
	foreach($arResult["ITEMS"] as $k => &$arElement) {
		$arElement["DISPLAY_PROPERTIES"][$code]["VALUE"] = '';
	}
	 */
	
}

//PR($props_codes);


?>