<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)	die();

if(!CModule::IncludeModule("iblock"))
	return;

$iblockXMLFile = WIZARD_SERVICE_RELATIVE_PATH."/xml/".LANGUAGE_ID."/slider.xml";
$iblockCode = "slider_".WIZARD_SITE_ID;
$iblockType = "content";

$rsIblock = CIBlock::GetList(array(), array("XML_ID" => $iblockCode, "TYPE" => $iblockType));
$iblockID = false; 

if($arIblock = $rsIblock->Fetch()) {
	$iblockID = $arIblock["ID"]; 
	if(WIZARD_INSTALL_DEMO_DATA) {
		CIBlock::Delete($arIblock["ID"]); 
		$iblockID = false; 
	}
}

if($iblockID == false) {
	$permissions = array(
		"1" => "X",
		"2" => "R"
	);

	$iblockID = WizardServices::ImportIBlockFromXML(
		$iblockXMLFile,
		$iblockCode,
		$iblockType,
		WIZARD_SITE_ID,
		$permissions
	);

	if($iblockID < 1)
		return;

	//IBlock fields
	$iblock = new CIBlock;
	$arFields = array(
		"ACTIVE" => "Y",
		"CODE" => $iblockCode, 
		"XML_ID" => $iblockCode,
	);
	$iblock->Update($iblockID, $arFields);
} else {
	$arSites = array(); 
	$db_res = CIBlock::GetSite($iblockID);
	while($res = $db_res->Fetch())
		$arSites[] = $res["LID"]; 
	if(!in_array(WIZARD_SITE_ID, $arSites)) {
		$arSites[] = WIZARD_SITE_ID;
		$iblock = new CIBlock;
		$iblock->Update($iblockID, array("LID" => $arSites));
	}
}

$arProp4Link = array(
	"PRODUCT_LINK" => "catalog"
);

$dbProp = CIBlockProperty::GetList(array(), array("IBLOCK_ID" => $iblockID));
while($arProp = $dbProp->Fetch()){
	if(!array_key_exists($arProp["CODE"], $arProp4Link)) continue;

	$rsIblock = CIBlock::GetList(array(), array("CODE" => $arProp4Link[$arProp["CODE"]]."_".WIZARD_SITE_ID, "XML_ID" => $arProp4Link[$arProp["CODE"]]."_".WIZARD_SITE_ID, "TYPE" => "catalog"));
   	if($arIblock = $rsIblock->Fetch()){
		$arFieldsUpdate = Array(
			"LINK_IBLOCK_ID" => $arIblock["ID"],
			"IBLOCK_ID" => $iblockID
		);

		$ibp = new CIBlockProperty;
		if(!$ibp->Update($arProp["ID"], $arFieldsUpdate))
			return;
	}
}

//iblock user fields
$arProperty = array();
$dbProperty = CIBlockProperty::GetList(array(), array("IBLOCK_ID" => $iblockID));
while($arProp = $dbProperty->Fetch()) {
	$arProperty[$arProp["CODE"]] = $arProp["ID"];
}

//list user options
CUserOptions::SetOption("list", "tbl_iblock_element_".md5($iblockType.".".$iblockID), array(
	"columns" => "NAME, PREVIEW_PICTURE, PROPERTY_".$arProperty["URL"].", ACTIVE, SORT, TIMESTAMP_X, ID",
	"by" => "timestamp_x",
	"order" => "desc",
	"page_size" => "20"
));

CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."/include/slider.php", array("SLIDER_IBLOCK_ID" => $iblockID));?>