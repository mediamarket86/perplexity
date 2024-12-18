<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$component = $this->getComponent();
$arParams = $component->applyTemplateModifications();

$arSetting = CElektroinstrument::GetFrontParametrsValues(SITE_ID);
$arResult["SETTING"] = $arSetting;

//USE_PRICE_RATIO//
$inPriceRatio = in_array("PRICE_RATIO", $arSetting["GENERAL_SETTINGS"]);
$inMinPrice = in_array("MIN_PRICE", $arSetting["PRODUCT_TABLE_VIEW"]);

//COLLECTION//
if($arParams["TYPE"] == "collections") {
	$collectionIds = $arValue = $arValueAll = array();	
	foreach($arResult["ITEMS"] as $arElement) {
		$collectionIds[] = $arElement["ID"];
	}
	unset($arElement);
	if(!empty($collectionIds)) {
		$arItems = CIBlockElement::GetList(array("SORT" => "ID"), array("PROPERTY_COLLECTION" => $collectionIds), false, false, array("ID", "IBLOCK_ID", "PROPERTY_COLLECTION"));
		while($arItem = $arItems->GetNext()) {
			$arValue[$arItem["PROPERTY_COLLECTION_VALUE"]][] = $arItem["ID"];
			$arValueAll[] = $arItem["ID"];
		}
	}
	if(!empty($arValue)){
		$arResult["COLLECTION"]["THIS"] = true;
		$arResult["COLLECTION"]["VALUE"] = $arValue;
		$arResult["COLLECTION"]["VALUE_ALL"] = $arValueAll; 
	}
	unset($collectionIds, $arValue, $arValueAll);
	
	$arConvertParams = array();
	if($arParams["CONVERT_CURRENCY"] == "Y") {
		if(!Bitrix\Main\Loader::includeModule("currency")) {
			$arParams["CONVERT_CURRENCY"] = "N";
			$arParams["CURRENCY_ID"] = "";
		} else {
			$arCurrencyInfo = CCurrency::GetByID($arParams["CURRENCY_ID"]);
			if(!(is_array($arCurrencyInfo) && !empty($arCurrencyInfo))) {
				$arParams["CONVERT_CURRENCY"] = "N";
				$arParams["CURRENCY_ID"] = "";
			} else {
				$arParams["CURRENCY_ID"] = $arCurrencyInfo["CURRENCY"];
				$arConvertParams["CURRENCY_ID"] = $arCurrencyInfo["CURRENCY"];
			}
		}
	}

	$arSelect = array("ID", "IBLOCK_ID");	
	$arr["PRICES"] = CIBlockPriceTools::GetCatalogPrices($arParams["IBLOCK_ID"], $arParams["PRICE_CODE"]);
	foreach($arr["PRICES"] as $key => $value) {
		if(!$value["CAN_VIEW"] && !$value["CAN_BUY"])
			continue;
		$arSelect[] = $value["SELECT"];
	}

	$ratioResult = Bitrix\Catalog\ProductTable::getCurrentRatioWithMeasure($arResult["COLLECTION"]["VALUE_ALL"], 1);
	$itemsList = array();
	$ratioItem = array();
	if(isset($arResult["COLLECTION"]["VALUE"]) && is_array($arResult["COLLECTION"]["VALUE"])) {
		foreach($arResult["COLLECTION"]["VALUE"] as $key => $arItem) {
			foreach($arItem as $itemID) {
				$itemsIterator = CIBlockElement::GetList(
					array(),
					array("ID" => $itemID, "ACTIVE" => "Y"),
					false,
					false,
					$arSelect
				);
				while($item = $itemsIterator->GetNext()) {
					$itemsList[$key][$item["ID"]] = $item;
					$ratioItem[$key][$item["ID"]] = $ratioResult[$item["ID"]]["RATIO"];
				}
			}
			unset($itemID);
		}
		unset($key, $arItem);
	}

	$arSumPrice = array();	
	foreach($itemsList as $key => $item) {
		foreach($item as $sectionItem) {
			$priceList = CIBlockPriceTools::GetItemPrices(
				$sectionItem["IBLOCK_ID"],
				$arr["PRICES"],
				$sectionItem,
				$arParams["PRICE_VAT_INCLUDE"],
				$arConvertParams
			);
			if(is_array($priceList) && !empty($priceList)) {
				foreach($priceList as $price) {
					if($price["MIN_PRICE"] == "Y" && $price["DISCOUNT_VALUE"] > 0) {
						if($inPriceRatio)
							$arSumPrice[$key][] = $price["DISCOUNT_VALUE"] * $ratioItem[$key][$sectionItem["ID"]];
						else
							$arSumPrice[$key][] = $price["DISCOUNT_VALUE"];
					}
				}
				unset($price);
			} else {
				$arOffers = CIBlockPriceTools::GetOffersArray(
					$sectionItem["IBLOCK_ID"],
					$sectionItem["ID"],
					array("SORT" => "ASC"),
					array(),
					array(),
					0,
					$arr["PRICES"],
					$arParams["PRICE_VAT_INCLUDE"],
					$arConvertParams
				);
				foreach($arOffers as $offer) {
					$ratioResultOffer = Bitrix\Catalog\ProductTable::getCurrentRatioWithMeasure($offer["ID"], 1);
					foreach($offer["PRICES"] as $key_p => $price) {
						if($price["MIN_PRICE"] == "Y" && $price["DISCOUNT_VALUE"] > 0) {
							if($inPriceRatio)
								$arSumPrice[$key][] = $price["DISCOUNT_VALUE"]*$ratioResultOffer[$offer["ID"]]["RATIO"];
							else
								$arSumPrice[$key][] = $price["DISCOUNT_VALUE"];
						}
					}
					unset($key_p, $price);
				}
				unset($offer);
			}
		}
		unset($sectionItem);
	}
	unset($key, $item);


	foreach($arResult["ITEMS"] as $key => $arElement) {
		$priceFormat = CCurrencyLang::GetCurrencyFormat($price["CURRENCY"], LANGUAGE_ID);
		if(empty($priceFormat["THOUSANDS_SEP"])):
			$priceFormat["THOUSANDS_SEP"] = " ";
		endif;					
		if($priceFormat["HIDE_ZERO"] == "Y"):
			if(round(min($arSumPrice[$key]), $priceFormat["DECIMALS"]) == round(min($arSumPrice[$key]), 0)):
				$priceFormat["DECIMALS"] = 0;
			endif;
		endif;
		$currency = str_replace("# ", " ", $priceFormat["FORMAT_STRING"]);

		foreach($arElement["ITEM_PRICES"] as $keyPrice => $itemPrice) {
			$arResult["ITEMS"][$key]["ITEM_PRICES"][$keyPrice]["PRINT_RATIO_PRICE"] = number_format(min($arSumPrice[$arElement["ID"]]),$priceFormat["DECIMALS"],$priceFormat["DEC_POINT"],$priceFormat["THOUSANDS_SEP"])." ".$currency;
			$arResult["ITEMS"][$key]["ITEM_PRICES"][$keyPrice]["RATIO_PRICE"] = min($arSumPrice[$arElement["ID"]]);
			$arResult["ITEMS"][$key]["ITEM_PRICES"][$keyPrice]["BASE_PRICE"] = min($arSumPrice[$arElement["ID"]]);
			$arResult["ITEMS"][$key]["ITEM_PRICES"][$keyPrice]["UNROUND_PRICE"] = min($arSumPrice[$arElement["ID"]]);
			$arResult["ITEMS"][$key]["ITEM_PRICES"][$keyPrice]["PRICE"] = min($arSumPrice[$arElement["ID"]]);
			$arResult["ITEMS"][$key]["ITEM_PRICES"][$keyPrice]["DISCOUNT"] = 0;
			$arResult["ITEMS"][$key]["ITEM_PRICES"][$keyPrice]["PERCENT"] = 0;
			$arResult["ITEMS"][$key]["ITEM_PRICES"][$keyPrice]["PRINT_BASE_PRICE"] = number_format(min($arSumPrice[$arElement["ID"]]),$priceFormat["DECIMALS"],$priceFormat["DEC_POINT"],$priceFormat["THOUSANDS_SEP"])." ".$currency;;
			$arResult["ITEMS"][$key]["ITEM_PRICES"][$keyPrice]["PRINT_PRICE"] = number_format(min($arSumPrice[$arElement["ID"]]),$priceFormat["DECIMALS"],$priceFormat["DEC_POINT"],$priceFormat["THOUSANDS_SEP"])." ".$currency;;
			$arResult["ITEMS"][$key]["ITEM_PRICES"][$keyPrice]["PRINT_DISCOUNT"] = 0;
		}
	}
	unset($key, $arElement);
}

//USE_PRICE_RATIO//
if(!$inPriceRatio) {


	foreach($arResult["ITEMS"] as $key => $arElement) {	
		if(isset($arElement["OFFERS"]) && !empty($arElement["OFFERS"])) {
			foreach($arElement["OFFERS"] as $key_off => $arOffer) {
				foreach($arOffer["ITEM_PRICES"] as $keyPrice => $itemPrice) {
					$arResult["ITEMS"][$key]["OFFERS"][$key_off]["ITEM_PRICES"][$keyPrice]["RATIO_BASE_PRICE"] = $itemPrice["BASE_PRICE"];
					$arResult["ITEMS"][$key]["OFFERS"][$key_off]["ITEM_PRICES"][$keyPrice]["PRINT_RATIO_BASE_PRICE"] = $itemPrice["PRINT_BASE_PRICE"];
					$arResult["ITEMS"][$key]["OFFERS"][$key_off]["ITEM_PRICES"][$keyPrice]["RATIO_PRICE"] = $itemPrice["PRICE"];
					$arResult["ITEMS"][$key]["OFFERS"][$key_off]["ITEM_PRICES"][$keyPrice]["PRINT_RATIO_PRICE"] = $itemPrice["PRINT_PRICE"];
					$arResult["ITEMS"][$key]["OFFERS"][$key_off]["ITEM_PRICES"][$keyPrice]["PRINT_RATIO_DISCOUNT"] = $itemPrice["PRINT_DISCOUNT"];	
				}
				unset($keyPrice, $itemPrice);
			}
			unset($key_off, $arOffer);
		} else {

			foreach($arElement["ITEM_PRICES"] as $keyPrice => $itemPrice) {
				$arResult["ITEMS"][$key]["ITEM_PRICES"][$keyPrice]["RATIO_BASE_PRICE"] = $arElement["ITEM_PRICES"][$keyPrice]["BASE_PRICE"];
				$arResult["ITEMS"][$key]["ITEM_PRICES"][$keyPrice]["PRINT_RATIO_BASE_PRICE"] = $arElement["ITEM_PRICES"][$keyPrice]["PRINT_BASE_PRICE"];
				$arResult["ITEMS"][$key]["ITEM_PRICES"][$keyPrice]["RATIO_PRICE"] = $arElement["ITEM_PRICES"][$keyPrice]["PRICE"];
				$arResult["ITEMS"][$key]["ITEM_PRICES"][$keyPrice]["PRINT_RATIO_PRICE"] = $arElement["ITEM_PRICES"][$keyPrice]["PRINT_PRICE"];
				$arResult["ITEMS"][$key]["ITEM_PRICES"][$keyPrice]["RATIO_DISCOUNT"] = $arElement["ITEM_PRICES"][$keyPrice]["DISCOUNT"];
				$arResult["ITEMS"][$key]["ITEM_PRICES"][$keyPrice]["PRINT_RATIO_DISCOUNT"] = $arElement["ITEM_PRICES"][$keyPrice]["PRINT_DISCOUNT"];
			}
			unset($keyPrice, $itemPrice);
		}
	}
	unset($key, $arElement);
} else {
	foreach($arResult["ITEMS"] as $key => $arElement) {	
		if(isset($arElement["OFFERS"]) && !empty($arElement["OFFERS"])) {
			foreach($arElement["OFFERS"] as $key_off => $arOffer) {
				foreach($arOffer["ITEM_PRICES"] as $keyPrice => $itemPrice) {
					$arResult["ITEMS"][$key]["OFFERS"][$key_off]["ITEM_PRICES"][$keyPrice]["PRICE"] = $arOffer["CATALOG_MEASURE_RATIO"]*$arOffer["ITEM_PRICES"][$keyPrice]["PRICE"];
				}
				unset($keyPrice, $itemPrice);
			}
			unset($key_off, $arOffer);
		} else {
			foreach($arElement["ITEM_PRICES"] as $keyPrice => $itemPrice) {
				$arResult["ITEMS"][$key]["ITEM_PRICES"][$keyPrice]["PRICE"] =  $arElement["CATALOG_MEASURE_RATIO"]*$arElement["ITEM_PRICES"][$keyPrice]["PRICE"];
			}
			unset($keyPrice, $itemPrice);
		}
	}
	unset($key, $arElement);
}
//END_USE_PRICE_RATIO//

//MIN_QUANTITY//
foreach($arResult["ITEMS"] as $key => $arElement) {

    // Проверяем, является ли $arElement["PRICE_MATRIX"]["ROWS"] массивом
    if (is_array($arElement["PRICE_MATRIX"]["ROWS"]) && !array_key_exists("ZERO-INF", $arElement["PRICE_MATRIX"]["ROWS"])) {

        foreach($arElement["ITEM_PRICES"] as $keyPrice => $itemPrice) {
            if ($itemPrice["QUANTITY_FROM"]) {
                $arResult["ITEMS"][$key]["ITEM_PRICES"][$keyPrice]["MIN_QUANTITY"] =  $itemPrice["QUANTITY_FROM"];
            } else {
                $arResult["ITEMS"][$key]["ITEM_PRICES"][$keyPrice]["MIN_QUANTITY"] =  $arElement["CATALOG_MEASURE_RATIO"];
            }
        }
    }else{
        foreach($arElement["ITEM_PRICES"] as $keyPrice => $itemPrice) {
            $arResult["ITEMS"][$key]["ITEM_PRICES"][$keyPrice]["MIN_QUANTITY"] =  $arElement["CATALOG_MEASURE_RATIO"];
        }
    }
	unset($keyPrice, $itemPrice);
}
unset($key, $arElement);

//ELEMENTS//
foreach($arResult["ITEMS"] as $key => $arElement) {
	//PRICE_MATRIX//
	$arPriceMatrix = false;
	$arPriceMatrix = $arElement["PRICE_MATRIX"]["MATRIX"];
	if(isset($arPriceMatrix) && is_array($arPriceMatrix)) foreach($arPriceMatrix as $key_matrix => $item) {
		foreach($item as $key2 => $item2) {
			$arPriceMatrix[$key_matrix][$key2]["QUANTITY_FROM"] = $arElement["PRICE_MATRIX"]["ROWS"][$key2]["QUANTITY_FROM"];
			$arPriceMatrix[$key_matrix][$key2]["QUANTITY_TO"] = ($arElement["PRICE_MATRIX"]["ROWS"][$key2]["QUANTITY_TO"] != 0 ? $arElement["PRICE_MATRIX"]["ROWS"][$key2]["QUANTITY_TO"] : INF);
			$arCurFormat = CCurrencyLang::GetCurrencyFormat($arPriceMatrix[$key_matrix][$key2]["CURRENCY"], LANGUAGE_ID);
			$currency = str_replace("# ", " ", $arCurFormat["FORMAT_STRING"]);
			$arPriceMatrix[$key_matrix][$key2]["PRINT_CURRENCY"] = $currency;
			if($inPriceRatio) {
				$arPriceMatrix[$key_matrix][$key2]["DISCOUNT_PRICE"] = $arElement["CATALOG_MEASURE_RATIO"]*$arElement["PRICE_MATRIX"]["MATRIX"][$key_matrix][$key2]["DISCOUNT_PRICE"];
			}
		}
		unset($key2, $item2);
	}
	$arResult["ITEMS"][$key]["PRICE_MATRIX_SHOW"]["COLS"] = $arResult["ITEMS"][$key]["PRICE_MATRIX"]["COLS"];
	$arResult["ITEMS"][$key]["PRICE_MATRIX_SHOW"]["MATRIX"] = $arPriceMatrix;

	//CURRENT_DISCOUNT//
	$arPrice = array();
	$arResult["ITEMS"][$key]["CURRENT_DISCOUNT"] = array();	

	if(isset($arElement["OFFERS"]) && !empty($arElement["OFFERS"])) {
		$minId = false;
		$minRatioPrice = false;
		foreach($arElement["OFFERS"] as $key_off => $arOffer) {
			$arOffer["MIN_PRICE"] = $arOffer["ITEM_PRICES"][$arOffer["ITEM_PRICE_SELECTED"]];
			if($arOffer["MIN_PRICE"]["RATIO_PRICE"] == 0)
				continue;			
			if($minRatioPrice === false || $minRatioPrice > $arOffer["MIN_PRICE"]["RATIO_PRICE"]) {			
				$minId = $arOffer["ID"];
				$minRatioPrice = $arOffer["MIN_PRICE"]["RATIO_PRICE"];
			}
		}
		unset($key_off, $arOffer);
		if($minId > 0) {
			$arDiscounts = CCatalogDiscount::GetDiscountByProduct($minId, $USER->GetUserGroupArray(), "N", array(), SITE_ID);
			$arResult["ITEMS"][$key]["CURRENT_DISCOUNT"] = current($arDiscounts);
		}
	} else {
		$arDiscounts = CCatalogDiscount::GetDiscountByProduct($arElement["ID"], $USER->GetUserGroupArray(), "N", array(), SITE_ID);
		$arResult["ITEMS"][$key]["CURRENT_DISCOUNT"] = current($arDiscounts);
	}

	//PREVIEW_PICTURE//
	if($arParams["TYPE"] != "collections" && is_array($arElement["PREVIEW_PICTURE"])) {
		if($arElement["PREVIEW_PICTURE"]["WIDTH"] > $arParams["DISPLAY_IMG_WIDTH"] || $arElement["PREVIEW_PICTURE"]["HEIGHT"] > $arParams["DISPLAY_IMG_HEIGHT"]) {
			$arFileTmp = CFile::ResizeImageGet(
				CFile::GetFileArray($arElement["PREVIEW_PICTURE"]["ID"]),
				array("width" => $arParams["DISPLAY_IMG_WIDTH"], "height" => $arParams["DISPLAY_IMG_HEIGHT"]),
				BX_RESIZE_IMAGE_PROPORTIONAL,
				true
			);
			$arResult["ITEMS"][$key]["PREVIEW_PICTURE"] = array(
				"SRC" => $arFileTmp["src"],
				"WIDTH" => $arFileTmp["width"],
				"HEIGHT" => $arFileTmp["height"]
			);
			unset($arFileTmp);
		}
	} elseif(is_array($arElement["DETAIL_PICTURE"])) {
		if($arElement["DETAIL_PICTURE"]["WIDTH"] > $arParams["DISPLAY_IMG_WIDTH"] || $arElement["DETAIL_PICTURE"]["HEIGHT"] > $arParams["DISPLAY_IMG_HEIGHT"]) {
			$arFileTmp = CFile::ResizeImageGet(
				$arElement["DETAIL_PICTURE"],
				array("width" => $arParams["DISPLAY_IMG_WIDTH"], "height" => $arParams["DISPLAY_IMG_HEIGHT"]),
				BX_RESIZE_IMAGE_PROPORTIONAL,
				true
			);
			$arResult["ITEMS"][$key]["PREVIEW_PICTURE"] = array(
				"SRC" => $arFileTmp["src"],
				"WIDTH" => $arFileTmp["width"],
				"HEIGHT" => $arFileTmp["height"]
			);
			unset($arFileTmp);
		} else {
			$arResult["ITEMS"][$key]["PREVIEW_PICTURE"] = $arElement["DETAIL_PICTURE"];
		}
	}

	//MANUFACTURER//
	$vendorId = intval($arElement["PROPERTIES"]["MANUFACTURER"]["VALUE"]);
	if($vendorId > 0)
		$vendorIds[] = $vendorId;

	//VERSIONS_PERFORMANCE//
	if(!empty($arElement["PROPERTIES"]["VERSIONS_PERFORMANCE"]["VALUE"])) {
		$obElColorCollection = CIBlockElement::GetList(
			array("SORT" => "ASC"),
			array(
				"ID" => $arElement["PROPERTIES"]["VERSIONS_PERFORMANCE"]["VALUE"],
				"ACTIVE" => "Y",
				"IBLOCK_ID" => $arElement["PROPERTIES"]["VERSIONS_PERFORMANCE"]["LINK_IBLOCK_ID"]
			),
			false,
			false,
			array("ID", "CODE", "NAME", "PROPERTY_HEX", "PROPERTY_PICT")
		);		
		while($arElColorCollection = $obElColorCollection->GetNext()) {
			$arResult["ITEMS"][$key]["VERSIONS_PERFORMANCE"]["ITEMS"][$arElColorCollection["ID"]] = $arElColorCollection;
			
			if($arElColorCollection["PROPERTY_PICT_VALUE"] > 0) {
				$arFile = CFile::GetFileArray($arElColorCollection["PROPERTY_PICT_VALUE"]);
				if($arFile["WIDTH"] > 24 || $arFile["HEIGHT"] > 24) {
					$arFileTmp = CFile::ResizeImageGet(
						$arFile,
						array("width" => 24, "height" => 24),
						BX_RESIZE_IMAGE_EXACT,
						true
					);
					$arResult["ITEMS"][$key]["VERSIONS_PERFORMANCE"]["ITEMS"][$arElColorCollection["ID"]]["PICTURE"] = array(
						"SRC" => $arFileTmp["src"],
						"WIDTH" => $arFileTmp["width"],
						"HEIGHT" => $arFileTmp["height"],
					);
				} else {
					$arResult["ITEMS"][$key]["VERSIONS_PERFORMANCE"]["ITEMS"][$arElColorCollection["ID"]]["PICTURE"] = $arFile;
				}
			}
		}
	}

	//MIN_PRICE//
	if(count($arElement["ITEM_QUANTITY_RANGES"]) > 1 && $inMinPrice) {
		$minPrice = false;
		foreach($arElement["ITEM_PRICES"] as $itemPrice) {
			if($itemPrice["RATIO_PRICE"] == 0)
				continue;
			if($minPrice === false || $minPrice > $itemPrice["RATIO_PRICE"]) {								
				$minPrice = $itemPrice["RATIO_PRICE"];					
				$arResult["ITEMS"][$key]["MIN_PRICE"] = array(		
					"RATIO_BASE_PRICE" => $itemPrice["RATIO_BASE_PRICE"],
					"PRINT_RATIO_BASE_PRICE" => $itemPrice["PRINT_RATIO_BASE_PRICE"],
					"RATIO_PRICE" => $minPrice,						
					"PRINT_RATIO_DISCOUNT" => $itemPrice["PRINT_RATIO_DISCOUNT"],
					"PERCENT" => $itemPrice["PERCENT"],
					"CURRENCY" => $itemPrice["CURRENCY"],					
					"MIN_QUANTITY" => $arElement["ITEM_PRICES"][$arElement["ITEM_PRICE_SELECTED"]]["MIN_QUANTITY"]
				);
			}
		}
		unset($itemPrice);
		if($minPrice === false) {
			$arResult["ITEMS"][$key]["MIN_PRICE"] = array(
				"RATIO_PRICE" => "0",
				"CURRENCY" => $arElement["ITEM_PRICES"][$arElement["ITEM_PRICE_SELECTED"]]["CURRENCY"]
			);
		}
	} else {
		$arResult["ITEMS"][$key]["MIN_PRICE"] = $arElement["ITEM_PRICES"][$arElement["ITEM_PRICE_SELECTED"]];
	}

	//CHECK_QUANTITY//
	$arResult["ITEMS"][$key]["CHECK_QUANTITY"] = $arElement["CATALOG_QUANTITY_TRACE"] == "Y" && $arElement["CATALOG_CAN_BUY_ZERO"] == "N";
	
	//SELECT_PROPS//
	if(is_array($arParams["PROPERTY_CODE_MOD"]) && !empty($arParams["PROPERTY_CODE_MOD"])) {
		$arResult["ITEMS"][$key]["SELECT_PROPS"] = array();
		foreach($arParams["PROPERTY_CODE_MOD"] as $pid) {
			if(!isset($arElement["PROPERTIES"][$pid]))
				continue;
			$prop = &$arElement["PROPERTIES"][$pid];
			$boolArr = is_array($prop["VALUE"]);
			if($prop["MULTIPLE"] == "Y" && $boolArr && !empty($prop["VALUE"])) {
				$arResult["ITEMS"][$key]["SELECT_PROPS"][$pid] = CIBlockFormatProperties::GetDisplayValue($arElement, $prop, "catalog_out");
				if(!is_array($arResult["ITEMS"][$key]["SELECT_PROPS"][$pid]["DISPLAY_VALUE"]) && !empty($arResult["ITEMS"][$key]["SELECT_PROPS"][$pid]["DISPLAY_VALUE"])) {
					$arTmp = $arResult["ITEMS"][$key]["SELECT_PROPS"][$pid]["DISPLAY_VALUE"];
					unset($arResult["ITEMS"][$key]["SELECT_PROPS"][$pid]["DISPLAY_VALUE"]);
					$arResult["ITEMS"][$key]["SELECT_PROPS"][$pid]["DISPLAY_VALUE"][0] = $arTmp;
				}
			} elseif($prop["MULTIPLE"] == "N" && !$boolArr) {
				if($prop["PROPERTY_TYPE"] == "L") {
					$arResult["ITEMS"][$key]["SELECT_PROPS"][$pid] = $prop;
					$property_enums = CIBlockPropertyEnum::GetList(Array("SORT" => "ASC"), Array("IBLOCK_ID" => $arParams["IBLOCK_ID"], "CODE" => $pid));
					while($enum_fields = $property_enums->GetNext()) {
						$arResult["ITEMS"][$key]["SELECT_PROPS"][$pid]["DISPLAY_VALUE"][] = $enum_fields["VALUE"];
					}
				}
			}
		}
		unset($pid);
	}
	
	//OFFERS//
	if(isset($arElement["OFFERS"]) && !empty($arElement["OFFERS"])) {
		//TOTAL_OFFERS//	
		$totalQnt = false;
		$minPrice = false;
		$totalPrices = false;

		foreach($arElement["OFFERS"] as $key_off => $arOffer) {

		    $totalQnt += $arOffer["CATALOG_QUANTITY"];
			foreach($arOffer["ITEM_PRICES"] as $itemPrice) {


				if($itemPrice["RATIO_PRICE"] == 0)
					continue;						
				if($minPrice === false || $minPrice > $itemPrice["RATIO_PRICE"]) {


					$minPrice = $itemPrice["RATIO_PRICE"];
					$arResult["ITEMS"][$key]["TOTAL_OFFERS"]["MIN_PRICE"] = array(		
						"ID" => $arOffer["ID"],						
						"RATIO_BASE_PRICE" => $itemPrice["RATIO_BASE_PRICE"],
						"PRINT_RATIO_BASE_PRICE" => $itemPrice["PRINT_RATIO_BASE_PRICE"],
						"RATIO_PRICE" => $minPrice,						
						"PRINT_RATIO_DISCOUNT" => $itemPrice["PRINT_RATIO_DISCOUNT"],
						"PERCENT" => $itemPrice["PERCENT"],
						"CURRENCY" => $itemPrice["CURRENCY"],
						"CATALOG_MEASURE_RATIO" => $arOffer["CATALOG_MEASURE_RATIO"],
						"CATALOG_MEASURE_NAME" => $arOffer["CATALOG_MEASURE_NAME"],
						"ITEM_PRICE_MODE" => $arOffer["ITEM_PRICE_MODE"],
						"ITEM_PRICES" => $arOffer["ITEM_PRICES"],
						"ITEM_PRICE_SELECTED" => $arOffer["ITEM_PRICE_SELECTED"],
						"ITEM_QUANTITY_RANGES" => $arOffer["ITEM_QUANTITY_RANGES"],
						"MIN_QUANTITY" =>$arOffer["ITEM_PRICES"][$arOffer["ITEM_PRICE_SELECTED"]]["MIN_QUANTITY"],
						"CHECK_QUANTITY" => $arOffer["CHECK_QUANTITY"],
						"CATALOG_QUANTITY" => $arOffer["CATALOG_QUANTITY"],
						"CAN_BUY" => $arOffer["CAN_BUY"],
						"PROPERTIES" => $arOffer["PROPERTIES"],
						"DISPLAY_PROPERTIES" => $arOffer["DISPLAY_PROPERTIES"],
                        "QUANTITY_FROM"=>$arOffer["ITEM_PRICES"][0]["QUANTITY_FROM"],
					);
					//PRICE_MATRIX//
					$arResultPrices = CIBlockPriceTools::GetCatalogPrices($arParams['IBLOCK_ID'], $arParams['PRICE_CODE']);
					foreach($arResultPrices as $value) {
						$arPriceTypeID[] = $value['ID'];
					}
					if(isset($value))
						unset($value);
				  
					$arOffer['PRICE_MATRIX'] = CatalogGetPriceTableEx($arOffer['ID'], 0, $arPriceTypeID, 'Y');
				
					$arMatrix;
					$arPriceMatrix = false;
					if(true) {
						$arPriceMatrix = $arOffer["PRICE_MATRIX"]["MATRIX"];
						foreach($arPriceMatrix as $key_matrix => $item) {
							foreach($item as $key2 => $item2) {
								$arPriceMatrix[$key_matrix][$key2]["QUANTITY_FROM"] = $arOffer["PRICE_MATRIX"]["ROWS"][$key2]["QUANTITY_FROM"];
								$arPriceMatrix[$key_matrix][$key2]["QUANTITY_TO"] = ($arOffer["PRICE_MATRIX"]["ROWS"][$key2]["QUANTITY_TO"] != 0? $arOffer["PRICE_MATRIX"]["ROWS"][$key2]["QUANTITY_TO"]: INF);
								$arCurFormat = CCurrencyLang::GetCurrencyFormat($arPriceMatrix[$key_matrix][$key2]["CURRENCY"], LANGUAGE_ID);
								$currency = str_replace("# ", " ", $arCurFormat["FORMAT_STRING"]);
								$arPriceMatrix[$key_matrix][$key2]["PRINT_CURRENCY"] = $currency;
								if($inPriceRatio) {
									$arPriceMatrix[$key_matrix][$key2]["DISCOUNT_PRICE"] = $arOffer["CATALOG_MEASURE_RATIO"]*$arOffer["PRICE_MATRIX"]["MATRIX"][$key_matrix][$key2]["DISCOUNT_PRICE"];
								}
							}
							unset($key2, $item2);
						}
						unset($key_matrix, $item);
					}
					$arResult["ITEMS"][$key]["TOTAL_OFFERS"]["PRICE_MATRIX_SHOW"]["COLS"] = $arOffer["PRICE_MATRIX"]["COLS"];
					$arResult["ITEMS"][$key]["TOTAL_OFFERS"]["PRICE_MATRIX_SHOW"]["MATRIX"] = $arPriceMatrix;
				}			
				$totalPrices[] = $itemPrice["RATIO_PRICE"];
			}
			unset($itemPrice);
		}
		unset($key_off, $arOffer);


		if($minPrice === false) {
			$arResult["ITEMS"][$key]["TOTAL_OFFERS"]["MIN_PRICE"] = array(
				"RATIO_PRICE" => "0",
				"CURRENCY" => $arElement["OFFERS"][0]["ITEM_PRICES"][$arElement["OFFERS"][0]["ITEM_PRICE_SELECTED"]]["CURRENCY"],	
				"CATALOG_MEASURE_RATIO" => $arElement["OFFERS"][0]["CATALOG_MEASURE_RATIO"],
				"CATALOG_MEASURE_NAME" => $arElement["OFFERS"][0]["CATALOG_MEASURE_NAME"]
			);
		}
		$arResult["ITEMS"][$key]["TOTAL_OFFERS"]["QUANTITY"] = $totalQnt;	
		if(count(array_unique($totalPrices)) > 1) {
			$arResult["ITEMS"][$key]["TOTAL_OFFERS"]["FROM"] = "Y";
		} else {
			$arResult["ITEMS"][$key]["TOTAL_OFFERS"]["FROM"] = "N";
		}	
		//END_TOTAL_OFFERS//
	}
	//END_OFFERS//	
}
unset($key, $arElement);

//END_ELEMENTS//

//MANUFACTURER//
if(is_array($vendorIds) && count($vendorIds) > 0) {
	$arVendor = array();
	$rsElements = CIBlockElement::GetList(
		array(),
		array(
			"ID" => array_unique($vendorIds)
		),
		false,
		false,
		array("ID", "IBLOCK_ID", "NAME", "PREVIEW_PICTURE")
	);
	while($arElement = $rsElements->GetNext()) {
		$arVendor[$arElement["ID"]]["NAME"] = $arElement["NAME"];
		if($arElement["PREVIEW_PICTURE"] > 0) {
			$arFile = CFile::GetFileArray($arElement["PREVIEW_PICTURE"]);		
			if($arFile["WIDTH"] > 69 || $arFile["HEIGHT"] > 24) {
				$arFileTmp = CFile::ResizeImageGet(
					$arFile,
					array("width" => 69, "height" => 24),
					BX_RESIZE_IMAGE_PROPORTIONAL,
					true
				);
				$arVendor[$arElement["ID"]]["PREVIEW_PICTURE"] = array(
					"SRC" => $arFileTmp["src"],
					"WIDTH" => $arFileTmp["width"],
					"HEIGHT" => $arFileTmp["height"],
				);
				unset($arFileTmp);
			} else {
				 $arVendor[$arElement["ID"]]["PREVIEW_PICTURE"] = $arFile;
				 unset($arFile);
			}
		}
	}
	
	//ELEMENTS//
	foreach($arResult["ITEMS"] as $key => $arElement) {
		//MANUFACTURER//
		$vendorId = intval($arElement["PROPERTIES"]["MANUFACTURER"]["VALUE"]);
		if($vendorId > 0 && isset($arVendor[$vendorId])) {
			$arResult["ITEMS"][$key]["PROPERTIES"]["MANUFACTURER"]["NAME"] = $arVendor[$vendorId]["NAME"];
			$arResult["ITEMS"][$key]["PROPERTIES"]["MANUFACTURER"]["PREVIEW_PICTURE"] = $arVendor[$vendorId]["PREVIEW_PICTURE"];
		}
		unset($vendorId);
	}
	unset($key, $arElement, $arVendor);
}
unset($vendorIds);