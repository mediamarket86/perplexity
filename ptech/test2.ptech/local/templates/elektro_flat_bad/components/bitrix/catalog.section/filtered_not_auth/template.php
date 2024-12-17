<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$this->setFrameMode(true);

if(count($arResult["ITEMS"]) < 1)
	return;

$curPage = $APPLICATION->GetCurPage();

global $arSetting;
$inOldPrice = in_array("OLD_PRICE", $arSetting["PRODUCT_TABLE_VIEW"]["VALUE"]);
$inPercentPrice = in_array("PERCENT_PRICE", $arSetting["PRODUCT_TABLE_VIEW"]["VALUE"]);
$inArticle = in_array("ARTNUMBER", $arSetting["PRODUCT_TABLE_VIEW"]["VALUE"]);
$inRating = in_array("RATING", $arSetting["PRODUCT_TABLE_VIEW"]["VALUE"]);
$inPreviewText = in_array("PREVIEW_TEXT", $arSetting["PRODUCT_TABLE_VIEW"]["VALUE"]);
$inMinPrice = in_array("MIN_PRICE", $arSetting["PRODUCT_TABLE_VIEW"]["VALUE"]);
$inProductQnt = in_array("PRODUCT_QUANTITY", $arSetting["GENERAL_SETTINGS"]["VALUE"]);
$inPriceRatio = in_array("PRICE_RATIO", $arSetting["GENERAL_SETTINGS"]["VALUE"]);
$inQuickView = in_array("QUICK_VIEW", $arSetting["GENERAL_SETTINGS"]["VALUE"]);

//ITEMS//?>
<div class="filtered-items not-auth-view">
	<?if(!empty($arParams["PAGER_TITLE"])) {?>
		<div class="h3"><?=$arParams["PAGER_TITLE"]?></div>
	<?}?>
	<div class="catalog-item-cards">
		<?foreach($arResult["ITEMS"] as $key => $arElement) {				
			$arItemIDs = array(
				"ID" => $arElement["STR_MAIN_ID"],
				"PRICE_RANGES_BTN" => $arElement["STR_MAIN_ID"]."_price_ranges_btn",
				"POPUP_BTN" => $arElement["STR_MAIN_ID"]."_popup_btn",
				"PROPS_BTN" => $arElement["STR_MAIN_ID"]."_props_btn",
				"BTN_BUY" => $arElement["STR_MAIN_ID"]."_btn_buy",
				"PRICE_MATRIX_BTN" => $arElement["STR_MAIN_ID"]."_price_ranges_btn",
				'QUICK_VIEW'=>$arElement["STR_MAIN_ID"].'_quick_view',
			);

			//CURRENCY_FORMAT//
			$arCurFormat = $currency = false;
			if(isset($arElement["OFFERS"]) && !empty($arElement["OFFERS"])) {
				$arCurFormat = CCurrencyLang::GetCurrencyFormat($arElement["TOTAL_OFFERS"]["MIN_PRICE"]["CURRENCY"], LANGUAGE_ID);
				if($arCurFormat["HIDE_ZERO"] == "Y")
					if(round($arElement["TOTAL_OFFERS"]["MIN_PRICE"]["RATIO_PRICE"], $arCurFormat["DECIMALS"]) == round($arElement["TOTAL_OFFERS"]["MIN_PRICE"]["RATIO_PRICE"], 0))
						$arCurFormat["DECIMALS"] = 0;
			} else {
				$arCurFormat = CCurrencyLang::GetCurrencyFormat($arElement["MIN_PRICE"]["CURRENCY"], LANGUAGE_ID);
				if($arCurFormat["HIDE_ZERO"] == "Y")
					if(round($arElement["MIN_PRICE"]["RATIO_PRICE"], $arCurFormat["DECIMALS"]) == round($arElement["MIN_PRICE"]["RATIO_PRICE"], 0))
						$arCurFormat["DECIMALS"] = 0;
			}
			if(empty($arCurFormat["THOUSANDS_SEP"]))
				$arCurFormat["THOUSANDS_SEP"] = " ";
			$currency = str_replace("# ", " ", $arCurFormat["FORMAT_STRING"]);
			
			//NEW_HIT_DISCOUNT_TIME_BUY//
			$sticker = "";
			$timeBuy = "";
			$class = "";
			if(array_key_exists("PROPERTIES", $arElement) && is_array($arElement["PROPERTIES"])) {
				//NEW//
				if(array_key_exists("NEWPRODUCT", $arElement["PROPERTIES"]) && !$arElement["PROPERTIES"]["NEWPRODUCT"]["VALUE"] == false)
					$sticker .= "<span class='new'>".GetMessage("CATALOG_ELEMENT_NEWPRODUCT")."</span>";
				//HIT//
				if(array_key_exists("SALELEADER", $arElement["PROPERTIES"]) && !$arElement["PROPERTIES"]["SALELEADER"]["VALUE"] == false)
					$sticker .= "<span class='hit'>".GetMessage("CATALOG_ELEMENT_SALELEADER")."</span>";
				//DISCOUNT//				
				if(isset($arElement["OFFERS"]) && !empty($arElement["OFFERS"])) {						
					if($arElement["TOTAL_OFFERS"]["MIN_PRICE"]["PERCENT"] > 0)
						$sticker .= "<span class='discount'>-".$arElement["TOTAL_OFFERS"]["MIN_PRICE"]["PERCENT"]."%</span>";
					else
						if(array_key_exists("DISCOUNT", $arElement["PROPERTIES"]) && !$arElement["PROPERTIES"]["DISCOUNT"]["VALUE"] == false)
							$sticker .= "<span class='discount'>%</span>";
				} else {
					if($arElement["MIN_PRICE"]["PERCENT"] > 0)
						$sticker .= "<span class='discount'>-".$arElement["MIN_PRICE"]["PERCENT"]."%</span>";
					else
						if(array_key_exists("DISCOUNT", $arElement["PROPERTIES"]) && !$arElement["PROPERTIES"]["DISCOUNT"]["VALUE"] == false)
							$sticker .= "<span class='discount'>%</span>";
				}
				//TIME_BUY//
				if(array_key_exists("TIME_BUY", $arElement["PROPERTIES"]) && !$arElement["PROPERTIES"]["TIME_BUY"]["VALUE"] == false) {
					if(!empty($arElement["CURRENT_DISCOUNT"]["ACTIVE_TO"])) {						
						if(isset($arElement["OFFERS"]) && !empty($arElement["OFFERS"])) {
							$class = " item-tb";
							$timeBuy = "<div class='time_buy_sticker'><span class='time_buy_figure'></span><span class='time_buy_text'>".GetMessage("CATALOG_ELEMENT_TIME_BUY")."</span></div>";
						} else {
							if($arElement["CAN_BUY"]) {
								$class = " item-tb";
								$timeBuy = "<div class='time_buy_sticker'><span class='time_buy_figure'></span><span class='time_buy_text'>".GetMessage("CATALOG_ELEMENT_TIME_BUY")."</span></div>";
							}
						}
					}
				}
			}
			
			//PRICE_MATRIX//
			if(is_array($arElement["PRICE_MATRIX_SHOW"]["COLS"]) && count($arElement["PRICE_MATRIX_SHOW"]["COLS"]) > 1 && empty($arElement["CURRENT_DISCOUNT"]["ACTIVE_TO"])) {
				$class = " item-pm";
			}
			if(isset($arElement["OFFERS"]) && !empty($arElement["OFFERS"])) {
				if(count($arResult["ITEMS"][$key]["TOTAL_OFFERS"]["PRICE_MATRIX_SHOW"]["COLS"]) && empty($arElement["CURRENT_DISCOUNT"]["ACTIVE_TO"])) {
					$class = " item-pm";
				}
			}
			
			//PREVIEW_PICTURE_ALT//
			$strAlt = (isset($arElement["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_ALT"]) && $arElement["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_ALT"] != "" ? $arElement["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_ALT"] : $arElement["NAME"]);

			//PREVIEW_PICTURE_TITLE//
			$strTitle = (isset($arElement["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_TITLE"]) && $arElement["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_TITLE"] != "" ? $arElement["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_TITLE"] : $arElement["NAME"]);
			
			//ITEM//?>				
			<div class="catalog-item-card<?=$class?>">
				<div class="catalog-item-info">
					<?//ITEM_PREVIEW_PICTURE//?>
					<div class="item-image-cont">
						<div class="item-image">
							<a href="<?=$arElement['DETAIL_PAGE_URL']?>">
								<?if(is_array($arElement["PREVIEW_PICTURE"])) {?>
									<img class="item_img" src="<?=$arElement['PREVIEW_PICTURE']['SRC']?>" width="<?=$arElement['PREVIEW_PICTURE']['WIDTH']?>" height="<?=$arElement['PREVIEW_PICTURE']['HEIGHT']?>" alt="<?=$strAlt?>" title="<?=$strTitle?>" />
								<?} else {?>
									<img class="item_img" src="<?=SITE_TEMPLATE_PATH?>/images/no-photo.jpg" width="150" height="150" alt="<?=$strAlt?>" title="<?=$strTitle?>" />
								<?}?>
								<?=$timeBuy?>									
								<span class="sticker">
									<?=$sticker?>
								</span>
								<?if(is_array($arElement["PROPERTIES"]["MANUFACTURER"]["PREVIEW_PICTURE"])) {?>
									<img class="manufacturer" src="<?=$arElement['PROPERTIES']['MANUFACTURER']['PREVIEW_PICTURE']['SRC']?>" width="<?=$arElement['PROPERTIES']['MANUFACTURER']['PREVIEW_PICTURE']['WIDTH']?>" height="<?=$arElement['PROPERTIES']['MANUFACTURER']['PREVIEW_PICTURE']['HEIGHT']?>" alt="<?=$arElement['PROPERTIES']['MANUFACTURER']['NAME']?>" title="<?=$arElement['PROPERTIES']['MANUFACTURER']['NAME']?>" />
								<?}?>
							</a>							
						</div>
					</div>
                    <div class="catalog-item-params">
                        <?//ITEM_TITLE//?>
                            <div class="item-all-title">
                                <a class="item-title" href="<?=$arElement['DETAIL_PAGE_URL']?>" title="<?=$arElement['NAME']?>">
                                    <?=$arElement['NAME']?>
                                </a>
                            </div>
                            <div class="article-compare-block">
                                <?//ARTICLE//
                                if($inArticle) {?>
                                    <div class="article">
                                        <?=GetMessage("CATALOG_ELEMENT_ARTNUMBER")?><?=!empty($arElement["PROPERTIES"]["CML2_ARTICLE"]["VALUE"]) ? $arElement["PROPERTIES"]["CML2_ARTICLE"]["VALUE"] : "-";?>
                                    </div>
                                <?}?>
                            </div>


                            <div class="need-auth-block">
                                <a class="auth-btn show-pvp-auth-block" href="javascript:void(0)" rel="nofollow"><i class="fa fa-user-o"></i><span>Авторизоваться</span></a>

                            </div>

                        <?//ITEM_PREVIEW_TEXT//
                        if($inPreviewText) {?>
                            <div class="item-desc">
                                <?=strip_tags($arElement["PREVIEW_TEXT"]);?>
                            </div>
                        <?}?>
                    </div>
				</div>
			</div>			
		<?}?>
	</div>
	<div class="clr"></div>
</div>

<?$signer = new \Bitrix\Main\Security\Sign\Signer;
$signedParams = $signer->sign(base64_encode(serialize($arResult["ORIGINAL_PARAMETERS"])), "catalog.section");

//JS//?>
<script type="text/javascript">
	BX.ready(function() {
		BX.message({			
			FILTERED_ELEMENT_FROM: "<?=GetMessageJS('CATALOG_ELEMENT_FROM')?>",
			FILTERED_ADDITEMINCART_ADDED: "<?=GetMessageJS('CATALOG_ELEMENT_ADDED')?>",
			FILTERED_POPUP_WINDOW_TITLE: "<?=GetMessageJS('CATALOG_ELEMENT_ADDITEMINCART_TITLE')?>",			
			FILTERED_POPUP_WINDOW_BTN_CLOSE: "<?=GetMessageJS('CATALOG_ELEMENT_ADDITEMINCART_BTN_CLOSE')?>",
			FILTERED_POPUP_WINDOW_BTN_ORDER: "<?=GetMessageJS('CATALOG_ELEMENT_ADDITEMINCART_BTN_ORDER')?>",
			FILTERED_SITE_DIR: "<?=SITE_DIR?>",
			FILTERED_POPUP_WINDOW_MORE_OPTIONS: "<?=GetMessageJS('CATALOG_ELEMENT_MORE_OPTIONS')?>",			
			FILTERED_COMPONENT_TEMPLATE: "<?=$this->GetFolder();?>",
			FILTERED_OFFERS_VIEW: "<?=$arSetting['OFFERS_VIEW']['VALUE']?>",
			FILTERED_COMPONENT_PARAMS: "<?=CUtil::JSEscape($signedParams)?>"
		});	
		<?foreach($arResult["ITEMS"] as $key => $arElement) {
			if((isset($arElement["OFFERS"]) && !empty($arElement["OFFERS"])) || $arElement["SELECT_PROPS"]) {				
				$arJSParams = array(					
					"VISUAL" => array(
						"ID" => $arElement["STR_MAIN_ID"],
						"PRICE_RANGES_BTN_ID" => $arElement["STR_MAIN_ID"]."_price_ranges_btn",
						"PROPS_BTN_ID" => $arElement["STR_MAIN_ID"]."_props_btn",
						"PRICE_MATRIX_BTN_ID" => is_array($arElement["ID_PRICE_MATRIX_BTN"]) ? $arElement["ID_PRICE_MATRIX_BTN"] : "",
                        "ADD2BASKET_WINDOW"=>in_array("ADD2BASKET_WINDOW", $arSetting["GENERAL_SETTINGS"]["VALUE"])? "Y" : "",
						"QUICK_VIEW"=>$arElement["STR_MAIN_ID"].'_quick_view',
					),
					"PRODUCT" => array(
						"ID" => $arElement["ID"],
						"ITEM_PRICE_MODE" => isset($arElement["OFFERS"]) && !empty($arElement["OFFERS"]) ? $arElement["TOTAL_OFFERS"]["MIN_PRICE"]["ITEM_PRICE_MODE"] : $arElement["ITEM_PRICE_MODE"],
						"ITEM_PRICES" => isset($arElement["OFFERS"]) && !empty($arElement["OFFERS"]) ? $arElement["TOTAL_OFFERS"]["MIN_PRICE"]["ITEM_PRICES"] : $arElement["ITEM_PRICES"],
						"ITEM_PRICE_SELECTED" => isset($arElement["OFFERS"]) && !empty($arElement["OFFERS"]) ? $arElement["TOTAL_OFFERS"]["MIN_PRICE"]["ITEM_PRICE_SELECTED"] : $arElement["ITEM_PRICE_SELECTED"],
						"ITEM_QUANTITY_RANGES" => isset($arElement["OFFERS"]) && !empty($arElement["OFFERS"]) ? $arElement["TOTAL_OFFERS"]["MIN_PRICE"]["ITEM_QUANTITY_RANGES"] : $arElement["ITEM_QUANTITY_RANGES"],
						"CHECK_QUANTITY" => isset($arElement["OFFERS"]) && !empty($arElement["OFFERS"]) ? $arElement["TOTAL_OFFERS"]["MIN_PRICE"]["CHECK_QUANTITY"] : $arElement["CHECK_QUANTITY"],
						"QUANTITY_FLOAT" => isset($arElement["OFFERS"]) && !empty($arElement["OFFERS"]) ? is_double($arElement["TOTAL_OFFERS"]["MIN_PRICE"]["CATALOG_MEASURE_RATIO"]) : is_double($arElement["CATALOG_MEASURE_RATIO"]),
						"MAX_QUANTITY" => isset($arElement["OFFERS"]) && !empty($arElement["OFFERS"]) ? $arElement["TOTAL_OFFERS"]["MIN_PRICE"]["CATALOG_QUANTITY"] : $arElement["CATALOG_QUANTITY"],
						"STEP_QUANTITY" => isset($arElement["OFFERS"]) && !empty($arElement["OFFERS"]) ? $arElement["TOTAL_OFFERS"]["MIN_PRICE"]["CATALOG_MEASURE_RATIO"] : $arElement["CATALOG_MEASURE_RATIO"],
						"PRICE_MATRIX" =>  isset($arElement["OFFERS"]) && !empty($arElement["OFFERS"]) ? $arElement["TOTAL_OFFERS"]["PRICE_MATRIX_SHOW"]["MATRIX"] : $arElement["PRICE_MATRIX_SHOW"]["MATRIX"],
						"PRINT_CURRENCY" => $currency
					)
				);
				if(isset($arElement["OFFERS"]) && !empty($arElement["OFFERS"]))
					$arJSParams["OFFER"]["ID"] = $arElement["TOTAL_OFFERS"]["MIN_PRICE"]["ID"];
				if($arElement["SELECT_PROPS"])
					$arJSParams["VISUAL"]["POPUP_BTN_ID"] = $arElement["STR_MAIN_ID"]."_popup_btn";
			} else {
				$arJSParams = array(					
					"VISUAL" => array(
						"ID" => $arElement["STR_MAIN_ID"],
						"PRICE_RANGES_BTN_ID" => $arElement["STR_MAIN_ID"]."_price_ranges_btn",
						"POPUP_BTN_ID" => $arElement["STR_MAIN_ID"]."_popup_btn",
						"BTN_BUY_ID" => $arElement["STR_MAIN_ID"]."_btn_buy",
						"PRICE_MATRIX_BTN_ID" => is_array($arElement["ID_PRICE_MATRIX_BTN"]) ? $arElement["ID_PRICE_MATRIX_BTN"] : "",
                        "ADD2BASKET_WINDOW"=>in_array("ADD2BASKET_WINDOW", $arSetting["GENERAL_SETTINGS"]["VALUE"])? "Y" : "",
						"QUICK_VIEW"=>$arElement["STR_MAIN_ID"].'_quick_view',
					),
					"PRODUCT" => array(
						"ID" => $arElement["ID"],
						"NAME" => $arElement["NAME"],
						"PICT" => is_array($arElement["PREVIEW_PICTURE"]) ? $arElement["PREVIEW_PICTURE"] : array("SRC" => SITE_TEMPLATE_PATH."/images/no-photo.jpg", "WIDTH" => 150, "HEIGHT" => 150),
						"ITEM_PRICE_MODE" => $arElement["ITEM_PRICE_MODE"],
						"ITEM_PRICES" => $arElement["ITEM_PRICES"],
						"ITEM_PRICE_SELECTED" => $arElement["ITEM_PRICE_SELECTED"],
						"ITEM_QUANTITY_RANGES" => $arElement["ITEM_QUANTITY_RANGES"],
						"CHECK_QUANTITY" => $arElement["CHECK_QUANTITY"],						
						"QUANTITY_FLOAT" => is_double($arElement["CATALOG_MEASURE_RATIO"]),
						"MAX_QUANTITY" => $arElement["CATALOG_QUANTITY"],
						"STEP_QUANTITY" => $arElement["CATALOG_MEASURE_RATIO"],
						"PRICE_MATRIX" => $arElement["PRICE_MATRIX_SHOW"]["MATRIX"],
						"PRINT_CURRENCY" => $currency
					)
				);
			}
            $arJSParams['arItemIDs'] = array(
                "ID" => $arElement["STR_MAIN_ID"],
                "PRICE_RANGES_BTN" => $arElement["STR_MAIN_ID"]."_price_ranges_btn",
                "POPUP_BTN" => $arElement["STR_MAIN_ID"]."_popup_btn",
                "PROPS_BTN" => $arElement["STR_MAIN_ID"]."_props_btn",
                "BTN_BUY" => $arElement["STR_MAIN_ID"]."_btn_buy",
                "PRICE_MATRIX_BTN" => $arElement["STR_MAIN_ID"]."_price_ranges_btn",
                'QUICK_VIEW'=>$arElement["STR_MAIN_ID"].'_quick_view',
            );
			$strObName = "ob".preg_replace("/[^a-zA-Z0-9_]/", "x", $arElement["STR_MAIN_ID"]);?>
			var <?=$strObName?> = new JCCatalogFilterProducts(<?=CUtil::PhpToJSObject($arJSParams, false, true);?>);
		<?}?>
	});
</script>
