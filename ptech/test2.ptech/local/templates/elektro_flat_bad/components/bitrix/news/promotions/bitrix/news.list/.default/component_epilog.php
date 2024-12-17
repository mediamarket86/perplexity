<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if(count($arResult["ITEMS"]) < 1)
	return;

use \Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);
$itemIds = array_column($arResult['ITEMS'], 'ID');

$res = CIBlockElement::GetList(
    [],
    ['IBLOCK_ID' => $arParams['IBLOCK_ID'], 'ID' => $itemIds],
    false,
    false,
    ['ID', 'ACTIVE_TO']
);

$itemsActiveTo = [];
while ($row = $res->GetNext()) {
    $itemsActiveTo[] = $row;
}
$itemsActiveTo = array_column($itemsActiveTo, null, 'ID');
?>

<div class="promotions-list">
	<?  global $USER;
		foreach($arResult["ITEMS"] as $arItem):
        $sectionId = isset($arItem['DISPLAY_PROPERTIES']['ACTION_SECTION']['VALUE']) ? $arItem['DISPLAY_PROPERTIES']['ACTION_SECTION']['VALUE'] : false;
$sectionLink = $USER->GetID() ? $arItem['PROPERTIES']['SALE_FLUYER_URL']['VALUE'] : '/personal/private';

        if (! empty($itemsActiveTo[$arItem['ID']])) {
            $arItem["ACTIVE_TO"] = $itemsActiveTo[$arItem['ID']]['ACTIVE_TO'];

            $activeToDateTime = new \Bitrix\Main\Type\DateTime($arItem["ACTIVE_TO"]);

            $arItem["DISPLAY_ACTIVE_TO"] = $activeToDateTime->format('d.m.Y');
        }

		$arCompareDates = 1;
		if(!empty($arItem["ACTIVE_TO"])):
			$displayActiveToDate = $arItem["ACTIVE_TO"];
			$displayCurrentDate = ConvertTimeStamp(false, "FULL");
			$arCompareDates = $DB->CompareDates($displayActiveToDate, $displayCurrentDate);
		endif;?>		
		<a target="_blank" class="promotions__item<?=($arCompareDates <= 0 ? ' completed' : '');?>" href="<?=$sectionLink ? : '#'?>" >
            <?php if (! empty($arItem['PROPERTIES']['DISCOUNT']['VALUE'])) :?>
                <span class="discount-sticker">
                    <span class="sticker-wrap">
                        <span class="flag"></span>
                        <span class="value">скидки до <?=$arItem['PROPERTIES']['DISCOUNT']['VALUE']?>%</span>
                    </span>
                </span>
            <?php endif; ?>
            <span class="promotions__item-image-wrap">
				<span class="promotions__item-image"<?=(is_array($arItem["PREVIEW_PICTURE"]) ? " style=\"background-image:url('".$arItem["PREVIEW_PICTURE"]["SRC"]."');\"" : "");?>></span>
				<?if($arItem["PROPERTIES"]["TIMER"]["VALUE"] != false && !empty($arItem["ACTIVE_TO"])):
					$new_date = ParseDateTime($arItem["ACTIVE_TO"], FORMAT_DATETIME);
					if(!$new_date["HH"])
						$new_date["HH"] = 00;
					if(!$new_date["MI"])
						$new_date["MI"] = 00;?>
					<script type="text/javascript">												
						$(function() {														
							$("#time_buy_timer_<?=$arItem['ID']?>").countdown({
								until: new Date(<?=$new_date["YYYY"]?>, <?=$new_date["MM"]?> - 1, <?=$new_date["DD"]?>, <?=$new_date["HH"]?>, <?=$new_date["MI"]?>),
								format: "DHMS",
								expiryText: "<span class='over'><?=GetMessage('PROMOTIONS_TIME_BUY_EXPIRY')?></span>",
								alwaysExpire: true
							});
						});												
					</script>
					<span class="time_buy_cont">
						<span class="time_buy_clock"><i class="fa fa-clock-o"></i></span>
						<span class="time_buy_timer" id="time_buy_timer_<?=$arItem['ID']?>"></span>
					</span>
				<?endif;?>
			</span>
			<span class="promotions__item-block">
				<span class="promotions__item-date-wrap">
					<span class="promotions__item-date">
						<?if($arCompareDates <= 0):
							echo Loc::getMessage("PROMOTIONS_ENDED")." ".$arItem["DISPLAY_ACTIVE_TO"];
						else:
							echo Loc::getMessage("PROMOTIONS_RUNNING")." ".(isset($arItem["DISPLAY_ACTIVE_TO"]) && !empty($arItem["DISPLAY_ACTIVE_TO"]) ? Loc::getMessage("PROMOTIONS_UNTIL")." ".$arItem["DISPLAY_ACTIVE_TO"] : Loc::getMessage("PROMOTIONS_ALWAYS"));
						endif;?>
					</span>
				</span>
				<span class="promotions__item-name-wrap-wrap">
					<span class="promotions__item-name-wrap">
						<span class="promotions__item-name"><?=$arItem["NAME"]?></span>
					</span>
				</span>
			</span>
		</a>
	<?endforeach;?>
</div>

<?if($arParams["DISPLAY_BOTTOM_PAGER"]):
	echo $arResult["NAV_STRING"];
endif;?>

<?$APPLICATION->IncludeComponent(
    "bitrix:news.list",
    "promotion_flyer",
    Array(
        "ACTIVE_DATE_FORMAT" => "d.m.Y",
        "ADD_SECTIONS_CHAIN" => "N",
        "AJAX_MODE" => "N",
        "AJAX_OPTION_ADDITIONAL" => "",
        "AJAX_OPTION_HISTORY" => "N",
        "AJAX_OPTION_JUMP" => "N",
        "AJAX_OPTION_STYLE" => "Y",
        "CACHE_FILTER" => "N",
        "CACHE_GROUPS" => "Y",
        "CACHE_TIME" => "36000000",
        "CACHE_TYPE" => "A",
        "CHECK_DATES" => "Y",
        "COMPOSITE_FRAME_MODE" => "A",
        "COMPOSITE_FRAME_TYPE" => "AUTO",
        "DETAIL_URL" => "",
        "DISPLAY_BOTTOM_PAGER" => "N",
        "DISPLAY_DATE" => "N",
        "DISPLAY_NAME" => "N",
        "DISPLAY_PICTURE" => "N",
        "DISPLAY_PREVIEW_TEXT" => "N",
        "DISPLAY_TOP_PAGER" => "N",
        "FIELD_CODE" => array("",""),
        "FILTER_NAME" => "",
        "HIDE_LINK_WHEN_NO_DETAIL" => "N",
        "IBLOCK_ID" => "59",
        "IBLOCK_TYPE" => "content",
        "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
        "INCLUDE_SUBSECTIONS" => "N",
        "MESSAGE_404" => "",
        "NEWS_COUNT" => "10",
        "PAGER_BASE_LINK_ENABLE" => "N",
        "PAGER_DESC_NUMBERING" => "N",
        "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
        "PAGER_SHOW_ALL" => "N",
        "PAGER_SHOW_ALWAYS" => "N",
        "PAGER_TEMPLATE" => ".default",
        "PAGER_TITLE" => "",
        "PARENT_SECTION" => "",
        "PARENT_SECTION_CODE" => "",
        "PREVIEW_TRUNCATE_LEN" => "",
        "PROPERTY_CODE" => array("","FILE",""),
        "SET_BROWSER_TITLE" => "N",
        "SET_LAST_MODIFIED" => "N",
        "SET_META_DESCRIPTION" => "N",
        "SET_META_KEYWORDS" => "N",
        "SET_STATUS_404" => "N",
        "SET_TITLE" => "N",
        "SHOW_404" => "N",
        "SORT_BY1" => "ACTIVE_FROM",
        "SORT_BY2" => "SORT",
        "SORT_ORDER1" => "DESC",
        "SORT_ORDER2" => "ASC",
        "STRICT_SECTION_CHECK" => "N"
    )
);?>
