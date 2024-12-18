<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$this->setFrameMode(true);

$ClientID = 'navigation_'.$arResult['NavNum'];

$arSetting = CElektroinstrument::GetFrontParametrsValues(SITE_ID);
$inGoogleAtr = in_array("GOOGLE_PREV_NEXT", $arSetting["GENERAL_SETTINGS"]);

if(!$arResult["NavShowAlways"]) {
	if($arResult["NavRecordCount"] == 0 || ($arResult["NavPageCount"] == 1 && $arResult["NavShowAll"] == false))
		return;
}?>

<div class="pagination">	
	<?$strNavQueryString = ($arResult["NavQueryString"] != "" ? $arResult["NavQueryString"]."&amp;" : "");
	$strNavQueryStringFull = ($arResult["NavQueryString"] != "" ? "?".$arResult["NavQueryString"] : "");
	
	if($arResult["bDescPageNumbering"] === true) {
		// to show always first and last pages
		$arResult["nStartPage"] = $arResult["NavPageCount"];
		$arResult["nEndPage"] = 1;

		$sPrevHref = '';
		if($arResult["NavPageNomer"] < $arResult["NavPageCount"]) {
			$bPrevDisabled = false;
			if($arResult["bSavePage"]) {
				$sPrevHref = $arResult["sUrlPath"].'?'.$strNavQueryString.'PAGEN_'.$arResult["NavNum"].'='.($arResult["NavPageNomer"]+1);
			} else {
				if($arResult["NavPageCount"] == ($arResult["NavPageNomer"]+1)) {
					$sPrevHref = $arResult["sUrlPath"].$strNavQueryStringFull;
				} else {
					$sPrevHref = $arResult["sUrlPath"].'?'.$strNavQueryString.'PAGEN_'.$arResult["NavNum"].'='.($arResult["NavPageNomer"]+1);
				}
			}
		} else {
			$bPrevDisabled = true;
		}
		
		$sNextHref = '';
		if($arResult["NavPageNomer"] > 1) {
			$bNextDisabled = false;
			$sNextHref = $arResult["sUrlPath"].'?'.$strNavQueryString.'PAGEN_'.$arResult["NavNum"].'='.($arResult["NavPageNomer"]-1);
		} else {
			$bNextDisabled = true;
		}?>
		
		<ul>
			<?if(!$bPrevDisabled):?>
				<li class="first">
					<a href="<?=$sPrevHref;?>" id="<?=$ClientID?>_previous_page"><?=GetMessage("PREVIOUS_PAGE")?></a>
				</li>		
			<?endif;
			$bFirst = true;
			$bPoints = false;
			
			do {
				$NavRecordGroupPrint = $arResult["NavPageCount"] - $arResult["nStartPage"] + 1;
				if($arResult["nStartPage"] <= 2 || $arResult["NavPageCount"]-$arResult["nStartPage"] <= 1 || abs($arResult['nStartPage']-$arResult["NavPageNomer"])<=2) {
					if($arResult["nStartPage"] == $arResult["NavPageNomer"]):?>
						<li class="active">
							<span class="nav-current-page"><?=$NavRecordGroupPrint?></span>
						</li>
					<?elseif($arResult["nStartPage"] == $arResult["NavPageCount"] && $arResult["bSavePage"] == false):?>
						<li>
							<a href="<?=$arResult["sUrlPath"]?><?=$strNavQueryStringFull?>"><?=$NavRecordGroupPrint?></a>
						</li>
					<?else:?>
						<li>
							<a href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=$arResult["nStartPage"]?>"><?=$NavRecordGroupPrint?></a>
						</li>
					<?endif;
					$bFirst = false;
					$bPoints = true;
				} else {
					if($bPoints) {?>
						<li class="points"><span>...</span></li>
						<?$bPoints = false;
					}
				}
				$arResult["nStartPage"]--;
			}
			while($arResult["nStartPage"] >= $arResult["nEndPage"]);
			
			if(!$bNextDisabled):?>
				<li class="last">
					<a href="<?=$sNextHref;?>" id="<?=$ClientID?>_next_page"><?=GetMessage("NEXT_PAGE")?></a>
				</li>
			<?endif;?>
		</ul>
	
	<?} else {
		// to show always first and last pages
		$arResult["nStartPage"] = 1;
		$arResult["nEndPage"] = $arResult["NavPageCount"];

		$sPrevHref = '';
		if($arResult["NavPageNomer"] > 1) {
			$bPrevDisabled = false;
			
			if($arResult["bSavePage"] || $arResult["NavPageNomer"] > 2) {
				$sPrevHref = $arResult["sUrlPath"].'?'.$strNavQueryString.'PAGEN_'.$arResult["NavNum"].'='.($arResult["NavPageNomer"]-1);
			} else {
				$sPrevHref = $arResult["sUrlPath"].$strNavQueryStringFull;
			}
		} else {
			$bPrevDisabled = true;
		}

		$sNextHref = '';
		if($arResult["NavPageNomer"] < $arResult["NavPageCount"]) {
			$bNextDisabled = false;
			$sNextHref = $arResult["sUrlPath"].'?'.$strNavQueryString.'PAGEN_'.$arResult["NavNum"].'='.($arResult["NavPageNomer"]+1);
		} else {
			$bNextDisabled = true;
		}?>

		<ul>
			<?if(!$bPrevDisabled):?>
				<li class="first">
					<a href="<?=$sPrevHref;?>" id="<?=$ClientID?>_previous_page"><?=GetMessage("PREVIOUS_PAGE")?></a>
				</li>		
			<?endif;
			$bFirst = true;
			$bPoints = false;
			
			do {
				if($arResult["nStartPage"] <= 2 || $arResult["nEndPage"]-$arResult["nStartPage"] <= 1 || abs($arResult['nStartPage']-$arResult["NavPageNomer"])<=2) {
					if($arResult["nStartPage"] == $arResult["NavPageNomer"]):?>
						<li class="active">
							<span class="nav-current-page"><?=$arResult["nStartPage"]?></span>
						</li>
					<?elseif($arResult["nStartPage"] == 1 && $arResult["bSavePage"] == false):?>
						<li>
							<a href="<?=$arResult["sUrlPath"]?><?=$strNavQueryStringFull?>"><?=$arResult["nStartPage"]?></a>
						</li>
					<?else:?>
						<li>
							<a href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=$arResult["nStartPage"]?>"><?=$arResult["nStartPage"]?></a>
						</li>
					<?endif;
					$bFirst = false;
					$bPoints = true;
				} else {
					if($bPoints) {?>
						<li class="points"><span>...</span></li>
						<?$bPoints = false;
					}
				}
				$arResult["nStartPage"]++;
			}
			while($arResult["nStartPage"] <= $arResult["nEndPage"]);
			
			if(!$bNextDisabled):?>
				<li class="last">
					<a href="<?=$sNextHref;?>" id="<?=$ClientID?>_next_page"><?=GetMessage("NEXT_PAGE")?></a>
				</li>
			<?endif;?>
		</ul>
	<?}?>
</div>

<?CJSCore::Init();?>
<script type="text/javascript">
	//<![CDATA[
	BX.bind(document, "keydown", function (event) {
		event = event || window.event;
		if(!event.ctrlKey)
			return;

		var target = event.target || event.srcElement;
		if(target && target.nodeName && (target.nodeName.toUpperCase() == "INPUT" || target.nodeName.toUpperCase() == "TEXTAREA"))
			return;

		var key = (event.keyCode ? event.keyCode : (event.which ? event.which : null));
		if(!key)
			return;

		var link = null;
		if(key == 39)
			link = BX('<?=$ClientID?>_next_page');
		else if(key == 37)
			link = BX('<?=$ClientID?>_previous_page');

		if(link && link.href)
			document.location = link.href;
	});
	//]]>
</script>

<?  //GOOGLE PREV NEXT
	if($inGoogleAtr){
	    if($arResult["NavPageNomer"] > 1)
	         $prev = '<link rel="prev" href="'.$arResult["sUrlPath"].$strNavQueryString.'?PAGEN_'.$arResult["NavNum"].'='.($arResult["NavPageNomer"]-1).'" />';

	    if($arResult["NavPageNomer"] < $arResult["NavPageCount"])
	        $next = '<link rel="next" href="'.$arResult["sUrlPath"].$strNavQueryString.'?PAGEN_'.$arResult["NavNum"].'='.($arResult["NavPageNomer"]+1).'" />';

	    $APPLICATION->SetPageProperty("google_prev_next", $prev.$next);	
	}
	?>