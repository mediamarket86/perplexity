<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
	die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
$templateData = array(
	'TEMPLATE_THEME' => $this->GetFolder() . '/themes/' . $arParams['TEMPLATE_THEME'] . '/colors.css',
	'TEMPLATE_CLASS' => 'bx_' . $arParams['TEMPLATE_THEME']
);


$is_catalog_root = ($APPLICATION->GetCurPage() == '/catalog/index.php' || $APPLICATION->GetCurPage() == '/catalog/');

//PR($arParams);
//PR($arResult['ITEMS']);
//PR($$arParams['FILTER_NAME']);
//PR($arResult);
//PR($_REQUEST);

$arSliderVals = array();
?>
<form name="<? echo $arResult["FILTER_NAME"] . "_form" ?>" action="<? echo $arResult["FORM_ACTION"] ?>" method="get">
	<div class="filter close">
		<? foreach ($arResult["HIDDEN"] as $arItem): ?>
			<input type="hidden" name="<? echo $arItem["CONTROL_NAME"] ?>" id="<? echo $arItem["CONTROL_ID"] ?>" value="<? echo $arItem["HTML_VALUE"] ?>" />
		<?
		endforeach;

		$arPROIZVODITEL = array('PROIZVODITEL', 'MANUFACTURER');
		//производитель
		foreach ($arResult["ITEMS"] as $key => $arItem) {
			//PR($arItem['NAME']." : ".$arItem['CODE']);
			if (in_array($arItem['CODE'], $arPROIZVODITEL) && !empty($arItem["VALUES"]) && ($arItem["DISPLAY_TYPE"] == "F")) {
				?>
				<div class="brands">
					<p class="title"><?= $arItem['NAME']; ?></p>
					<div class="list">
						<? $i = 0; ?>    
						<? foreach ($arItem["VALUES"] as $val => $ar): ?>
			<? if ($i++ == 5) echo '<div class="add">'; ?> 
							<label data-role="label_<?= $ar["CONTROL_ID"] ?>" class="checkbox <? echo $ar["DISABLED"] ? 'disabled' : '' ?>" for="<? echo $ar["CONTROL_ID"] ?>">
								<input
									type="checkbox"
									style="position: absolute; z-index: -1; opacity: 0; margin: 0px; padding: 0px;"
									value="<? echo $ar["HTML_VALUE"] ?>"
									name="<? echo $ar["CONTROL_NAME"] ?>"
									id="<? echo $ar["CONTROL_ID"] ?>"
			<? echo $ar["CHECKED"] ? 'checked="checked"' : '' ?>
									onclick="smartFilter.click(this)"
									>
								<span class="name" title="<?= $ar["VALUE"]; ?>"><?= $ar["VALUE"]; ?></span>
							</label>
						<? endforeach; ?>
		<? if ($i > 5) { ?>
						</div> <!-- add -->
						<a class="view" href="#">Показать все</a>
		<? } ?>
				</div>
			</div>

			<?
		}
	}

	//цена
	foreach ($arResult["ITEMS"] as $key => $arItem) {

		$key = $arItem["ENCODED_ID"];
		if (isset($arItem["PRICE"])):
			if ($arItem["VALUES"]["MAX"]["VALUE"] - $arItem["VALUES"]["MIN"]["VALUE"] <= 0)
				continue;
			$itm_id = 'slider_' . $arItem['ID'];
			?>

			<div class="price" id="<?= $itm_id; ?>">
				<p class="title">Цена</p>
				<ul class="range-slider-output spacer">
					<li class="left">От <label>
							<input
								type="text"
								class="price-lower-val"
								name="<? echo $arItem["VALUES"]["MIN"]["CONTROL_NAME"] ?>"
								id="<? echo $arItem["VALUES"]["MIN"]["CONTROL_ID"] ?>"
								value="<? echo $arItem["VALUES"]["MIN"]["HTML_VALUE"] ?>"
								size="5"
								onkeyup="smartFilter.keyup(this)"
								>
						</label>
					</li>
					<li class="right">до <label>
							<input
								type="text"
								class="price-upper-val"
								name="<? echo $arItem["VALUES"]["MAX"]["CONTROL_NAME"] ?>"
								id="<? echo $arItem["VALUES"]["MAX"]["CONTROL_ID"] ?>"
								value="<? echo $arItem["VALUES"]["MAX"]["HTML_VALUE"] ?>"
								size="5"
								onkeyup="smartFilter.keyup(this)"
								>
						</label>
						<?/*?><span> руб</span><?*/?>
					</li>
				</ul>
				<?
				$price1 = $arItem["VALUES"]["MIN"]["VALUE"];
				$price2 = $arItem["VALUES"]["MIN"]["VALUE"] + round(($arItem["VALUES"]["MAX"]["VALUE"] - $arItem["VALUES"]["MIN"]["VALUE"]) / 4);
				$price3 = $arItem["VALUES"]["MIN"]["VALUE"] + round(($arItem["VALUES"]["MAX"]["VALUE"] - $arItem["VALUES"]["MIN"]["VALUE"]) / 2);
				$price4 = $arItem["VALUES"]["MIN"]["VALUE"] + round((($arItem["VALUES"]["MAX"]["VALUE"] - $arItem["VALUES"]["MIN"]["VALUE"]) * 3) / 4);
				$price5 = $arItem["VALUES"]["MAX"]["VALUE"];

				$price6 = $price1;
				if (isset($_REQUEST[$arItem["VALUES"]["MIN"]["CONTROL_ID"]]) && !empty($_REQUEST[$arItem["VALUES"]["MIN"]["CONTROL_ID"]]))
					$price6 = (float) $_REQUEST[$arItem["VALUES"]["MIN"]["CONTROL_ID"]];
				$price7 = $price5;
				if (isset($_REQUEST[$arItem["VALUES"]["MAX"]["CONTROL_ID"]]) && !empty($_REQUEST[$arItem["VALUES"]["MAX"]["CONTROL_ID"]]))
					$price7 = (float) $_REQUEST[$arItem["VALUES"]["MAX"]["CONTROL_ID"]];

				for ($i = 1; $i <= 7; $i++) {
					$name = 'price' . $i;
					$$name = round($$name, 2);
				}
				$arSliderVals[] = array(
					'id' => $itm_id,
					'id_min' => $arItem["VALUES"]["MIN"]["CONTROL_ID"],
					'id_max' => $arItem["VALUES"]["MAX"]["CONTROL_ID"],
					'v_min' => $price6, //значене
					'v_max' => $price7,
					's_min' => $price1, //размерность слайдера
					's_max' => $price5,
					'step' => ($price5 - $price1) > 10 ? 1 : 0.1
				);
				?>
<?/*?>
				<div class="marker lower-val"><span><?= $price1 ?></span></div>
				<div class="marker middle-val"><span><?= $price3 ?></span></div>
				<div class="marker upper-val"><span><?= $price5 ?></span></div>
<?*/?>
				<!--
					<span class="lower-val"><?= $price1 ?></span>
					<span class="middle-val"><?= $price3 ?></span>
					<span class="upper-val"><?= $price5 ?></span>
				-->
				<div class="price-range-slider"></div>

			</div><!--price-->

		<?
		endif;
	}
	?>

	<div class="set-filter">
		<button class="off orange-btn" id="set_filter" name="set_filter" value="Y">подобрать</button>
		<a href="<?= $APPLICATION->GetCurPageParam("del_filter=Y", array("del_filter", "set_filter")) ?>" class="clear-ftr <?if(!$_GET['set_filter']):?>off<?endif?>" id="del_filter">очистить фильтр</a>
<!-- 		<a href="?del_filter=Y" class="clear-ftr off" id="del_filter">очистить фильтр</a> -->

		<div class="bx_filter_button_box active">
			<div class="bx_filter_block">
				<div class="bx_filter_parameters_box_container">
					<div class="bx_filter_popup_result left" id="modef" <? if (!isset($arResult["ELEMENT_COUNT"])) echo 'style="display:none"'; ?> style="display: inline-block;">
<? echo GetMessage("CT_BCSF_FILTER_COUNT", array("#ELEMENT_COUNT#" => '<span id="modef_num">' . intval($arResult["ELEMENT_COUNT"]) . '</span>')); ?>
						<span class="arrow"></span>
						<a href="<? echo $arResult["FILTER_URL"] ?>"><? echo GetMessage("CT_BCSF_FILTER_SHOW") ?></a>
					</div>
				</div>
			</div>
		</div> 
	</div><!--set-filter-->

	<?
	if (!$is_catalog_root) :
		//это только для некорневых разделов каталога, т.е. не для /catalog/
		//not prices and Производитель
		foreach ($arResult["ITEMS"] as $key => $arItem) {
			if (empty($arItem["VALUES"]) || isset($arItem["PRICE"]))
				continue;
			if (in_array($arItem['CODE'], $arPROIZVODITEL))
				continue;
			if ($arItem["DISPLAY_TYPE"] == "A" && ( $arItem["VALUES"]["MAX"]["VALUE"] - $arItem["VALUES"]["MIN"]["VALUE"] <= 0))
				continue;
			?>
			<?
			$arCur = current($arItem["VALUES"]);
			switch ($arItem["DISPLAY_TYPE"]) {
				case "A"://NUMBERS_WITH_SLIDER
					$itm_id = 'slider_' . $arItem['ID'];
					?>

					<div class="price rage" id="<?= $itm_id; ?>">
						<p class="title"><?= $arItem['NAME']; ?></p>
						<ul class="range-slider-output spacer">
							<li class="left">От <label>
									<input
										type="text"
										class="price-lower-val"
										name="<? echo $arItem["VALUES"]["MIN"]["CONTROL_NAME"] ?>"
										id="<? echo $arItem["VALUES"]["MIN"]["CONTROL_ID"] ?>"
										value="<? echo $arItem["VALUES"]["MIN"]["HTML_VALUE"] ?>"
										size="5"
										onkeyup="smartFilter.keyup(this)"
										>
								</label>
							</li>
							<li class="right">до <label>
									<input
										type="text"
										class="price-upper-val"
										name="<? echo $arItem["VALUES"]["MAX"]["CONTROL_NAME"] ?>"
										id="<? echo $arItem["VALUES"]["MAX"]["CONTROL_ID"] ?>"
										value="<? echo $arItem["VALUES"]["MAX"]["HTML_VALUE"] ?>"
										size="5"
										onkeyup="smartFilter.keyup(this)"
										>
								</label>
								<span> <? //Дж ?></span>
							</li>
						</ul>
						<?
						$price1 = $arItem["VALUES"]["MIN"]["VALUE"];
						$price2 = $arItem["VALUES"]["MIN"]["VALUE"] + ($arItem["VALUES"]["MAX"]["VALUE"] - $arItem["VALUES"]["MIN"]["VALUE"]) / 4;
						$price3 = $arItem["VALUES"]["MIN"]["VALUE"] + ($arItem["VALUES"]["MAX"]["VALUE"] - $arItem["VALUES"]["MIN"]["VALUE"]) / 2;
						$price4 = $arItem["VALUES"]["MIN"]["VALUE"] + (($arItem["VALUES"]["MAX"]["VALUE"] - $arItem["VALUES"]["MIN"]["VALUE"]) * 3) / 4;
						$price5 = $arItem["VALUES"]["MAX"]["VALUE"];

						$price6 = $price1;
						if (isset($_REQUEST[$arItem["VALUES"]["MIN"]["CONTROL_ID"]]) && !empty($_REQUEST[$arItem["VALUES"]["MIN"]["CONTROL_ID"]]))
							$price6 = (float) $_REQUEST[$arItem["VALUES"]["MIN"]["CONTROL_ID"]];
						$price7 = $price5;
						if (isset($_REQUEST[$arItem["VALUES"]["MAX"]["CONTROL_ID"]]) && !empty($_REQUEST[$arItem["VALUES"]["MAX"]["CONTROL_ID"]]))
							$price7 = (float) $_REQUEST[$arItem["VALUES"]["MAX"]["CONTROL_ID"]];

						$step = ( ($price5 - $price1) > 10) ? 1 : ( ( ($price5 - $price1) > 1 ) ? 0.1 : 0.01 );
						$decimals = ($step >= 1) ? 0 : 2;
						for ($i = 1; $i <= 7; $i++) {
							$name = 'price' . $i;
							$$name = round($$name, $decimals);
						}

						$arSliderVals[] = array(
							'id' => $itm_id,
							'id_min' => $arItem["VALUES"]["MIN"]["CONTROL_ID"],
							'id_max' => $arItem["VALUES"]["MAX"]["CONTROL_ID"],
							'v_min' => $price6, //значене
							'v_max' => $price7,
							's_min' => $price1, //размерность слайдера
							's_max' => $price5,
							'step' => $step
						);
						//PR($arSliderVals);
						//PR('p5='.$price5.', p1='.$price1.' d='.($price5-$price1).', decimals='.$decimals );
						?>
						<div class="marker lower-val"><span><?= $price1 ?></span></div>
						<div class="marker middle-val"><span><?= $price3 ?></span></div>
						<div class="marker upper-val"><span><?= $price5 ?></span></div>
						<!--
							<span class="lower-val"><?= $price1 ?></span>
							<span class="middle-val"><?= $price3 ?></span>
							<span class="upper-val"><?= $price5 ?></span>
						-->                                   
						<div class="price-range-slider"></div>

					</div><!--rage-->

					<?
					break;
				case "B"://NUMBERS
					?>
					<div class="bx_filter_parameters_box_container_block"><div class="bx_filter_input_container">
							<input
								class="min-price"
								type="text"
								name="<? echo $arItem["VALUES"]["MIN"]["CONTROL_NAME"] ?>"
								id="<? echo $arItem["VALUES"]["MIN"]["CONTROL_ID"] ?>"
								value="<? echo $arItem["VALUES"]["MIN"]["HTML_VALUE"] ?>"
								size="5"
								onkeyup="smartFilter.keyup(this)"
								/>
						</div></div>
					<div class="bx_filter_parameters_box_container_block"><div class="bx_filter_input_container">
							<input
								class="max-price"
								type="text"
								name="<? echo $arItem["VALUES"]["MAX"]["CONTROL_NAME"] ?>"
								id="<? echo $arItem["VALUES"]["MAX"]["CONTROL_ID"] ?>"
								value="<? echo $arItem["VALUES"]["MAX"]["HTML_VALUE"] ?>"
								size="5"
								onkeyup="smartFilter.keyup(this)"
								/>
						</div></div>
					<?
					break;
				case "G"://CHECKBOXES_WITH_PICTURES
					?>
				<? foreach ($arItem["VALUES"] as $val => $ar): ?>
						<input
							style="display: none"
							type="checkbox"
							name="<?= $ar["CONTROL_NAME"] ?>"
							id="<?= $ar["CONTROL_ID"] ?>"
							value="<?= $ar["HTML_VALUE"] ?>"
							<? echo $ar["CHECKED"] ? 'checked="checked"' : '' ?>
							/>
							<?
							$class = "";
							if ($ar["CHECKED"])
								$class.= " active";
							if ($ar["DISABLED"])
								$class.= " disabled";
							?>
						<label for="<?= $ar["CONTROL_ID"] ?>" data-role="label_<?= $ar["CONTROL_ID"] ?>" class="bx_filter_param_label dib<?= $class ?>" onclick="smartFilter.keyup(BX('<?= CUtil::JSEscape($ar["CONTROL_ID"]) ?>'));
																BX.toggleClass(this, 'active');">
							<span class="bx_filter_param_btn bx_color_sl">
								<? if (isset($ar["FILE"]) && !empty($ar["FILE"]["SRC"])): ?>
									<span class="bx_filter_btn_color_icon" style="background-image:url('<?= $ar["FILE"]["SRC"] ?>');"></span>
					<? endif ?>
							</span>
						</label>
					<? endforeach ?> 
					<?
					break;
				case "H"://CHECKBOXES_WITH_PICTURES_AND_LABELS
					?>
				<? foreach ($arItem["VALUES"] as $val => $ar): ?>
						<input
							style="display: none"
							type="checkbox"
							name="<?= $ar["CONTROL_NAME"] ?>"
							id="<?= $ar["CONTROL_ID"] ?>"
							value="<?= $ar["HTML_VALUE"] ?>"
							<? echo $ar["CHECKED"] ? 'checked="checked"' : '' ?>
							/>
							<?
							$class = "";
							if ($ar["CHECKED"])
								$class.= " active";
							if ($ar["DISABLED"])
								$class.= " disabled";
							?>
						<label for="<?= $ar["CONTROL_ID"] ?>" data-role="label_<?= $ar["CONTROL_ID"] ?>" class="bx_filter_param_label<?= $class ?>" onclick="smartFilter.keyup(BX('<?= CUtil::JSEscape($ar["CONTROL_ID"]) ?>'));
																BX.toggleClass(this, 'active');">
							<span class="bx_filter_param_btn bx_color_sl">
								<? if (isset($ar["FILE"]) && !empty($ar["FILE"]["SRC"])): ?>
									<span class="bx_filter_btn_color_icon" style="background-image:url('<?= $ar["FILE"]["SRC"] ?>');"></span>
					<? endif ?>
							</span>
							<span class="bx_filter_param_text" title="<?= $ar["VALUE"]; ?>"><?= $ar["VALUE"]; ?><?
								if ($arParams["DISPLAY_ELEMENT_COUNT"] !== "N" && isset($ar["ELEMENT_COUNT"])):
									?> (<span data-role="count_<?= $ar["CONTROL_ID"] ?>"><? echo $ar["ELEMENT_COUNT"]; ?></span>)<? endif;
								?></span>
						</label>
					<? endforeach ?>
					<?
					break;
				case "P"://DROPDOWN
					$checkedItemExist = false;
					?>
					<div class="bx_filter_select_container">
						<div class="bx_filter_select_block" onclick="smartFilter.showDropDownPopup(this, '<?= CUtil::JSEscape($key) ?>')">
							<div class="bx_filter_select_text" data-role="currentOption">
								<?
								foreach ($arItem["VALUES"] as $val => $ar) {
									if ($ar["CHECKED"]) {
										echo $ar["VALUE"];
										$checkedItemExist = true;
									}
								}
								if (!$checkedItemExist) {
									echo GetMessage("CT_BCSF_FILTER_ALL");
								}
								?>
							</div>
							<div class="bx_filter_select_arrow"></div>
							<input
								style="display: none"
								type="radio"
								name="<?= $arCur["CONTROL_NAME_ALT"] ?>"
								id="<? echo "all_" . $arCur["CONTROL_ID"] ?>"
								value=""
								/>
				<? foreach ($arItem["VALUES"] as $val => $ar): ?>
								<input
									style="display: none"
									type="radio"
									name="<?= $ar["CONTROL_NAME_ALT"] ?>"
									id="<?= $ar["CONTROL_ID"] ?>"
									value="<? echo $ar["HTML_VALUE_ALT"] ?>"
					<? echo $ar["CHECKED"] ? 'checked="checked"' : '' ?>
									/>
								<? endforeach ?>
							<div class="bx_filter_select_popup" data-role="dropdownContent" style="display: none;">
								<ul>
									<li>
										<label for="<?= "all_" . $arCur["CONTROL_ID"] ?>" class="bx_filter_param_label" data-role="label_<?= "all_" . $arCur["CONTROL_ID"] ?>" onclick="smartFilter.selectDropDownItem(this, '<?= CUtil::JSEscape("all_" . $arCur["CONTROL_ID"]) ?>')">
				<? echo GetMessage("CT_BCSF_FILTER_ALL"); ?>
										</label>
									</li>
				<?
				foreach ($arItem["VALUES"] as $val => $ar):
					$class = "";
					if ($ar["CHECKED"])
						$class.= " selected";
					if ($ar["DISABLED"])
						$class.= " disabled";
					?>
										<li>
											<label for="<?= $ar["CONTROL_ID"] ?>" class="bx_filter_param_label<?= $class ?>" data-role="label_<?= $ar["CONTROL_ID"] ?>" onclick="smartFilter.selectDropDownItem(this, '<?= CUtil::JSEscape($ar["CONTROL_ID"]) ?>')"><?= $ar["VALUE"] ?></label>
										</li>
				<? endforeach ?>
								</ul>
							</div>
						</div>
					</div>
				<?
				break;
			case "R"://DROPDOWN_WITH_PICTURES_AND_LABELS
				?>
					<div class="bx_filter_select_container">
						<div class="bx_filter_select_block" onclick="smartFilter.showDropDownPopup(this, '<?= CUtil::JSEscape($key) ?>')">
							<div class="bx_filter_select_text" data-role="currentOption">
				<?
				$checkedItemExist = false;
				foreach ($arItem["VALUES"] as $val => $ar):
					if ($ar["CHECKED"]) {
						?>
										<? if (isset($ar["FILE"]) && !empty($ar["FILE"]["SRC"])): ?>
											<span class="bx_filter_btn_color_icon" style="background-image:url('<?= $ar["FILE"]["SRC"] ?>');"></span>
										<? endif ?>
										<span class="bx_filter_param_text">
										<?= $ar["VALUE"] ?>
										</span>
											<?
											$checkedItemExist = true;
										}
									endforeach;
									if (!$checkedItemExist) {
										?><span class="bx_filter_btn_color_icon all"></span> <?
									echo GetMessage("CT_BCSF_FILTER_ALL");
								}
								?>
							</div>
							<div class="bx_filter_select_arrow"></div>
							<input
								style="display: none"
								type="radio"
								name="<?= $arCur["CONTROL_NAME_ALT"] ?>"
								id="<? echo "all_" . $arCur["CONTROL_ID"] ?>"
								value=""
								/>
				<? foreach ($arItem["VALUES"] as $val => $ar): ?>
								<input
									style="display: none"
									type="radio"
									name="<?= $ar["CONTROL_NAME_ALT"] ?>"
									id="<?= $ar["CONTROL_ID"] ?>"
									value="<?= $ar["HTML_VALUE_ALT"] ?>"
					<? echo $ar["CHECKED"] ? 'checked="checked"' : '' ?>
									/>
				<? endforeach ?>
							<div class="bx_filter_select_popup" data-role="dropdownContent" style="display: none">
								<ul>
									<li style="border-bottom: 1px solid #e5e5e5;padding-bottom: 5px;margin-bottom: 5px;">
										<label for="<?= "all_" . $arCur["CONTROL_ID"] ?>" class="bx_filter_param_label" data-role="label_<?= "all_" . $arCur["CONTROL_ID"] ?>" onclick="smartFilter.selectDropDownItem(this, '<?= CUtil::JSEscape("all_" . $arCur["CONTROL_ID"]) ?>')">
											<span class="bx_filter_btn_color_icon all"></span>
				<? echo GetMessage("CT_BCSF_FILTER_ALL"); ?>
										</label>
									</li>
											<?
											foreach ($arItem["VALUES"] as $val => $ar):
												$class = "";
												if ($ar["CHECKED"])
													$class.= " selected";
												if ($ar["DISABLED"])
													$class.= " disabled";
												?>
										<li>
											<label for="<?= $ar["CONTROL_ID"] ?>" data-role="label_<?= $ar["CONTROL_ID"] ?>" class="bx_filter_param_label<?= $class ?>" onclick="smartFilter.selectDropDownItem(this, '<?= CUtil::JSEscape($ar["CONTROL_ID"]) ?>')">
										<? if (isset($ar["FILE"]) && !empty($ar["FILE"]["SRC"])): ?>
													<span class="bx_filter_btn_color_icon" style="background-image:url('<?= $ar["FILE"]["SRC"] ?>');"></span>
					<? endif ?>
												<span class="bx_filter_param_text">
												<?= $ar["VALUE"] ?>
												</span>
											</label>
										</li>
												<? endforeach ?>
								</ul>
							</div>
						</div>
					</div>
				<?
				break;
			case "K"://RADIO_BUTTONS
				?>
					<label class="bx_filter_param_label" for="<? echo "all_" . $arCur["CONTROL_ID"] ?>">
						<span class="bx_filter_input_checkbox">
							<input
								type="radio"
								value=""
								name="<? echo $arCur["CONTROL_NAME_ALT"] ?>"
								id="<? echo "all_" . $arCur["CONTROL_ID"] ?>"
								onclick="smartFilter.click(this)"
								/>
							<span class="bx_filter_param_text"><? echo GetMessage("CT_BCSF_FILTER_ALL"); ?></span>
						</span>
					</label>
				<? foreach ($arItem["VALUES"] as $val => $ar): ?>
						<label data-role="label_<?= $ar["CONTROL_ID"] ?>" class="bx_filter_param_label" for="<? echo $ar["CONTROL_ID"] ?>">
							<span class="bx_filter_input_checkbox <? echo $ar["DISABLED"] ? 'disabled' : '' ?>">
								<input
									type="radio"
									value="<? echo $ar["HTML_VALUE_ALT"] ?>"
									name="<? echo $ar["CONTROL_NAME_ALT"] ?>"
									id="<? echo $ar["CONTROL_ID"] ?>"
					<? echo $ar["CHECKED"] ? 'checked="checked"' : '' ?>
									onclick="smartFilter.click(this)"
									/>
								<span class="bx_filter_param_text" title="<?= $ar["VALUE"]; ?>"><?= $ar["VALUE"]; ?><?
									if ($arParams["DISPLAY_ELEMENT_COUNT"] !== "N" && isset($ar["ELEMENT_COUNT"])):
										?> (<span data-role="count_<?= $ar["CONTROL_ID"] ?>"><? echo $ar["ELEMENT_COUNT"]; ?></span>)<? endif;
									?></span>
							</span>
						</label>
								<? endforeach; ?>
								<?
								break;
							case "U"://CALENDAR
								?>
					<div class="bx_filter_parameters_box_container_block"><div class="bx_filter_input_container bx_filter_calendar_container">
					<?
					$APPLICATION->IncludeComponent(
						'bitrix:main.calendar', '', array(
						'FORM_NAME' => $arResult["FILTER_NAME"] . "_form",
						'SHOW_INPUT' => 'Y',
						'INPUT_ADDITIONAL_ATTR' => 'class="calendar" placeholder="' . FormatDate("SHORT", $arItem["VALUES"]["MIN"]["VALUE"]) . '" onkeyup="smartFilter.keyup(this)" onchange="smartFilter.keyup(this)"',
						'INPUT_NAME' => $arItem["VALUES"]["MIN"]["CONTROL_NAME"],
						'INPUT_VALUE' => $arItem["VALUES"]["MIN"]["HTML_VALUE"],
						'SHOW_TIME' => 'N',
						'HIDE_TIMEBAR' => 'Y',
						), null, array('HIDE_ICONS' => 'Y')
					);
					?>
						</div></div>
					<div class="bx_filter_parameters_box_container_block"><div class="bx_filter_input_container bx_filter_calendar_container">
							<?
							$APPLICATION->IncludeComponent(
								'bitrix:main.calendar', '', array(
								'FORM_NAME' => $arResult["FILTER_NAME"] . "_form",
								'SHOW_INPUT' => 'Y',
								'INPUT_ADDITIONAL_ATTR' => 'class="calendar" placeholder="' . FormatDate("SHORT", $arItem["VALUES"]["MAX"]["VALUE"]) . '" onkeyup="smartFilter.keyup(this)" onchange="smartFilter.keyup(this)"',
								'INPUT_NAME' => $arItem["VALUES"]["MAX"]["CONTROL_NAME"],
								'INPUT_VALUE' => $arItem["VALUES"]["MAX"]["HTML_VALUE"],
								'SHOW_TIME' => 'N',
								'HIDE_TIMEBAR' => 'Y',
								), null, array('HIDE_ICONS' => 'Y')
							);
							?>
						</div></div>
							<?
							break;
						default://CHECKBOXES
							?>

					<div class="brands  rage">
						<p class="title"><?= $arItem['NAME']; ?></p>
						<div class="list">

					<? $i = 0; ?>    
					<? foreach ($arItem["VALUES"] as $val => $ar): ?>
						<? if ($i++ == 5) echo '<div class="add">'; ?> 
								<label data-role="label_<?= $ar["CONTROL_ID"] ?>" class="checkbox <? echo $ar["DISABLED"] ? 'disabled' : '' ?>" for="<? echo $ar["CONTROL_ID"] ?>">
									<input
										type="checkbox"
										style="position: absolute; z-index: -1; opacity: 0; margin: 0px; padding: 0px;"
										value="<? echo $ar["HTML_VALUE"] ?>"
										name="<? echo $ar["CONTROL_NAME"] ?>"
										id="<? echo $ar["CONTROL_ID"] ?>"
								<? echo $ar["CHECKED"] ? 'checked="checked"' : '' ?>
										onclick="smartFilter.click(this)"
										>
									<span class="name" title="<?= $ar["VALUE"]; ?>"><?= $ar["VALUE"]; ?></span>
								</label>
				<? endforeach; ?>
				<? if ($i >= 5) { ?>
							</div> <!-- add -->
							<a class="view" href="#">Показать все</a>
				<? } ?> 
					</div>
				</div>
						<?
					}
					?>
		<?
		}

	endif;
?>
</div>  
</form>

<? if (!$is_catalog_root) : ?>
<div class="view-all-ftr">
	<span>все параметры</span>
	<i></i>
</div>
<? endif;?>

<script>
	var smartFilter = new JCSmartFilter('<? echo CUtil::JSEscape($arResult["FORM_ACTION"]) ?>', 'horizontal');
</script>
<script>
<? if (!empty($arSliderVals)) { ?>
	<? foreach ($arSliderVals as $sl) { ?>
			//console.log('#<?= $sl['id']; ?>');
			$('#<?= $sl['id']; ?> .price-range-slider').noUiSlider({
				start: [<?= $sl['v_min'] ?>, <?= $sl['v_max'] ?>],
				range: {
					'min': [<?= $sl['s_min'] ?>],
					'max': [<?= $sl['s_max'] ?>]
				},
				step: <?= $sl['step'] ?>,
				connect: true,
				format: wNumb({
					decimals: <?= $sl['step'] >= 1 ? 0 : 2; ?>,
					thousand: ''
				})
			});
			$('#<?= $sl['id']; ?> .price-range-slider').Link('lower').to($('#<?= $sl['id']; ?> .price-lower-val'));
			$('#<?= $sl['id']; ?> .price-range-slider').Link('upper').to($('#<?= $sl['id']; ?> .price-upper-val'));
	<? } ?>

		var sliders_ranges = [
	<? foreach ($arSliderVals as $sl) { ?>
				{'id_min': '<?= $sl['id_min'] ?>', 'id_max': '<?= $sl['id_max'] ?>', 'rmin':<?= $sl['s_min'] ?>, 'rmax':<?= $sl['s_max'] ?>},
	<? } ?>
		];
		//console.log(sliders_ranges);

		$('#set_filter').click(function () {
			//если значение min = min_range, или max = max_range, то их инпуты обнулить, чтобы они не добавились в условия фильтра
			for (i = 0; i < sliders_ranges.length; i++) {
				elm = sliders_ranges[i];
				var vmin = $('#' + elm['id_min']).val();
				if (vmin == elm['rmin'])
					$('#' + elm['id_min']).val('');
				var vmax = $('#' + elm['id_max']).val();
				if (vmax == elm['rmax'])
					$('#' + elm['id_max']).val('');
				//console.log(elm['id'], vmin, vmax);
			}
			//alert('click');
		});

<? } ?>


	$('input[type="radio"], input[type="checkbox"], select').styler({
		selectSearch: true
	});

	function removeOff() {
		$('.filter .set-filter .off').removeClass('off');
	}
	$('.price .price-range-slider').on('set', removeOff);
	$('.rage .price-range-slider').on('set', removeOff);

	$('.filter .checkbox, .filter label .jq-checkbox').mouseup('click', removeOff);


	$('.brands a.view').click(function () {
		if ($(this).hasClass('open2')) {
			console.log('click1');
			$(this).removeClass('open2');
			$(this).parents('.brands').find('.add').css('display', 'none');
			$(this).text('Показать все');
		} else {
			console.log('click2');
			console.log($(this));
			$(this).addClass('open2');
			$(this).parents('.brands').find('.add').css('display', 'inline');
			$(this).text('Популярные');
		}
		return false;
	});

	//показ-скрытие всех свойств фильтра
	$('.view-all-ftr').on('click', function () {
		if ($(this).hasClass('open')) {
			$(this).removeClass('open');
			$('.filter .rage').css('display', 'none');
			$('.filter .protection').css('display', 'none');
			$(this).find('span').text('все параметры');
			$(this).find('i').css('background-position', '0 0');
		} else {
			console.log('open all');
			$(this).addClass('open');
			$('.filter .rage').css('display', 'inline-block');
			$('.filter .protection').css('display', 'inline-block');
			$(this).find('span').text('Показаны все параметры');
			$(this).find('i').css('display', 'none');
			$('.view-all-ftr').off('click')
		}
	});



</script>                    


