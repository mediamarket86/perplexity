<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Page\Asset;
Loc::loadMessages(__FILE__);?>
<!DOCTYPE html>
<html lang="<?=LANGUAGE_ID?>">
<head>	
	<link rel="shortcut icon" type="image/x-icon" href="<?=SITE_TEMPLATE_PATH?>/favicon.ico" />
	<link rel="apple-touch-icon" sizes="57x57" href="<?=SITE_TEMPLATE_PATH?>/images/apple-touch-icon-114.png" />
	<link rel="apple-touch-icon" sizes="114x114" href="<?=SITE_TEMPLATE_PATH?>/images/apple-touch-icon-114.png" />
	<link rel="apple-touch-icon" sizes="72x72" href="<?=SITE_TEMPLATE_PATH?>/images/apple-touch-icon-144.png" />
	<link rel="apple-touch-icon" sizes="144x144" href="<?=SITE_TEMPLATE_PATH?>/images/apple-touch-icon-144.png" />
	<meta name='viewport' content='width=device-width, initial-scale=1.0' />
	<title><?$APPLICATION->ShowTitle()?></title>
	<meta property="og:title" content="<?=$APPLICATION->ShowTitle();?>"/>
    <meta property="og:description" content="<?=$APPLICATION->ShowProperty("description");?>"/>
    <meta property="og:type" content="<?=$APPLICATION->ShowProperty("ogtype");?>"/>
    <meta property="og:url" content= "<?=(CMain::IsHTTPS()? 'https' : 'http')."://".SITE_SERVER_NAME.$APPLICATION->GetCurPage();?>" />
    <meta property="og:image" content="<?=$APPLICATION->ShowProperty("ogimage");?>">
	<meta property='og:image:width' content="<?=$APPLICATION->ShowProperty("ogimagewidth");?>" />
	<meta property='og:image:height' content="<?=$APPLICATION->ShowProperty("ogimageheight");?>" />
	<link rel='image_src' href="<?=$APPLICATION->ShowProperty("ogimage")?>" />
	<?$APPLICATION->SetPageProperty("ogtype", "website");
	$APPLICATION->ShowProperty('google_prev_next');
	$APPLICATION->SetPageProperty("ogimage", (CMain::IsHTTPS()? 'https' : 'http')."://".SITE_SERVER_NAME.SITE_TEMPLATE_PATH."/images/apple-touch-icon-144.png");
	$APPLICATION->SetPageProperty("ogimagewidth", "144");
	$APPLICATION->SetPageProperty("ogimageheight", "144");
	Asset::getInstance()->addCss("https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css");
	if(!CModule::IncludeModule("altop.elastofont"))
	Asset::getInstance()->addCss("https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=latin,cyrillic-ext");
	Asset::getInstance()->addCss(SITE_TEMPLATE_PATH."/colors.css");

	Asset::getInstance()->addCss(SITE_TEMPLATE_PATH."/js/anythingslider/slider.css");
	Asset::getInstance()->addCss(SITE_TEMPLATE_PATH."/js/custom-forms/custom-forms.css");
	Asset::getInstance()->addCss(SITE_TEMPLATE_PATH."/js/fancybox2/jquery.fancybox.css");
	Asset::getInstance()->addCss(SITE_TEMPLATE_PATH."/js/spectrum/spectrum.css");
	CJSCore::Init(array("jquery", "popup"));
	Asset::getInstance()->addJs(SITE_TEMPLATE_PATH."/js/jquery.cookie.js");
	Asset::getInstance()->addJs(SITE_TEMPLATE_PATH."/js/moremenu.js");
    Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . "/js/jquery.inputmask.min.js");

	Asset::getInstance()->addJs(SITE_TEMPLATE_PATH."/js/anythingslider/jquery.easing.1.2.js");
	Asset::getInstance()->addJs(SITE_TEMPLATE_PATH."/js/anythingslider/jquery.anythingslider.min.js");
	Asset::getInstance()->addJs(SITE_TEMPLATE_PATH."/js/custom-forms/jquery.custom-forms.js");
	Asset::getInstance()->addJs(SITE_TEMPLATE_PATH."/js/fancybox2/jquery.fancybox.pack.js");
	Asset::getInstance()->addJs(SITE_TEMPLATE_PATH."/js/spectrum/spectrum.js");
	Asset::getInstance()->addJs(SITE_TEMPLATE_PATH."/js/countUp.min.js");
	Asset::getInstance()->addJs(SITE_TEMPLATE_PATH."/js/countdown/jquery.plugin.js");
	Asset::getInstance()->addJs(SITE_TEMPLATE_PATH."/js/countdown/jquery.countdown.js");
    Asset::getInstance()->addJs(SITE_TEMPLATE_PATH."/js/TweenMax.min.js");
	Asset::getInstance()->addString("
		<script type='text/javascript'>
			$(function() {
				$.countdown.regionalOptions['ru'] = {
					labels: ['".Loc::getMessage("COUNTDOWN_REGIONAL_LABELS_YEAR")."', '".Loc::getMessage("COUNTDOWN_REGIONAL_LABELS_MONTH")."', '".Loc::getMessage("COUNTDOWN_REGIONAL_LABELS_WEEK")."', '".Loc::getMessage("COUNTDOWN_REGIONAL_LABELS_DAY")."', '".Loc::getMessage("COUNTDOWN_REGIONAL_LABELS_HOUR")."', '".Loc::getMessage("COUNTDOWN_REGIONAL_LABELS_MIN")."', '".Loc::getMessage("COUNTDOWN_REGIONAL_LABELS_SEC")."'],
					labels1: ['".Loc::getMessage("COUNTDOWN_REGIONAL_LABELS1_YEAR")."', '".Loc::getMessage("COUNTDOWN_REGIONAL_LABELS1_MONTH")."', '".Loc::getMessage("COUNTDOWN_REGIONAL_LABELS1_WEEK")."', '".Loc::getMessage("COUNTDOWN_REGIONAL_LABELS1_DAY")."', '".Loc::getMessage("COUNTDOWN_REGIONAL_LABELS1_HOUR")."', '".Loc::getMessage("COUNTDOWN_REGIONAL_LABELS_MIN")."', '".Loc::getMessage("COUNTDOWN_REGIONAL_LABELS_SEC")."'],
					labels2: ['".Loc::getMessage("COUNTDOWN_REGIONAL_LABELS2_YEAR")."', '".Loc::getMessage("COUNTDOWN_REGIONAL_LABELS2_MONTH")."', '".Loc::getMessage("COUNTDOWN_REGIONAL_LABELS2_WEEK")."', '".Loc::getMessage("COUNTDOWN_REGIONAL_LABELS2_DAY")."', '".Loc::getMessage("COUNTDOWN_REGIONAL_LABELS2_HOUR")."', '".Loc::getMessage("COUNTDOWN_REGIONAL_LABELS_MIN")."', '".Loc::getMessage("COUNTDOWN_REGIONAL_LABELS_SEC")."'],
					compactLabels: ['".Loc::getMessage("COUNTDOWN_REGIONAL_COMPACT_LABELS_YEAR")."', '".Loc::getMessage("COUNTDOWN_REGIONAL_COMPACT_LABELS_MONTH")."', '".Loc::getMessage("COUNTDOWN_REGIONAL_COMPACT_LABELS_WEEK")."', '".Loc::getMessage("COUNTDOWN_REGIONAL_COMPACT_LABELS_DAY")."'],
					compactLabels1: ['".Loc::getMessage("COUNTDOWN_REGIONAL_COMPACT_LABELS1_YEAR")."', '".Loc::getMessage("COUNTDOWN_REGIONAL_COMPACT_LABELS_MONTH")."', '".Loc::getMessage("COUNTDOWN_REGIONAL_COMPACT_LABELS_WEEK")."', '".Loc::getMessage("COUNTDOWN_REGIONAL_COMPACT_LABELS_DAY")."'],
					whichLabels: function(amount) {
						var units = amount % 10;
						var tens = Math.floor((amount % 100) / 10);
						return (amount == 1 ? 1 : (units >= 2 && units <= 4 && tens != 1 ? 2 : (units == 1 && tens != 1 ? 1 : 0)));
					},
					digits: ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'],
					timeSeparator: ':',
					isRTL: false
				};
				$.countdown.setDefaults($.countdown.regionalOptions['ru']);
			});
		</script>
	");
	Asset::getInstance()->addJs(SITE_TEMPLATE_PATH."/js/main.js");
	Asset::getInstance()->addJs(SITE_TEMPLATE_PATH."/script.js");
	$APPLICATION->ShowHead();?>

	<?if(CModule::IncludeModule("altop.elektroinstrument")) {
	    CElektroinstrument::getBackground(SITE_ID);
        CElektroinstrument::SetCannonicalURL($APPLICATION->GetCurPageParam());
    }?>

<!-- Begin Talk-Me {literal} 
<script type='text/javascript'>
	(function(d, w, m) {
		window.supportAPIMethod = m;
		var s = d.createElement('script');
		s.type ='text/javascript'; s.id = 'supportScript'; s.charset = 'utf-8';
		s.async = true;
		var id = '2fbac86b82d1e46ab13989ad475879f4';
		s.src = 'https://lcab.talk-me.ru/support/support.js?h='+id;
		var sc = d.getElementsByTagName('script')[0];
		w[m] = w[m] || function() { (w[m].q = w[m].q || []).push(arguments); };
		if (sc) sc.parentNode.insertBefore(s, sc); 
		else d.documentElement.firstChild.appendChild(s);
	})(document, window, 'TalkMe');
</script>
{/literal} End Talk-Me -->
</head>
<body <?=$APPLICATION->ShowProperty("bgClass")?><?=$APPLICATION->ShowProperty("backgroundColor")?><?=$APPLICATION->ShowProperty("backgroundImage")?>>
    <div class="mobile-body mobile-catalog back-shadow">&nbsp;</div>

<?global $arSetting;?>
	<?$arSetting = $APPLICATION->IncludeComponent("altop:settings", "", array(), false, array("HIDE_ICONS" => "Y"));?>

<?if($arSetting["GENERAL_SETTINGS"]["LIST"]["BREADCRUMB"]["CURRENT"]=="Y") {
    Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . "/bread.css");
}
?>
	<div class="bx-panel<?=($arSetting['CART_LOCATION']['VALUE'] == 'TOP') ? ' clvt' : ''?>">
		<?$APPLICATION->ShowPanel();?>
	</div>
	<div class="bx-include-empty">
		<?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => ""), false);?>
	</div>
	<div class="body<?=($arSetting['CATALOG_LOCATION']['VALUE'] == 'HEADER') ? ' clvh' : ''?><?=($arSetting['CART_LOCATION']['VALUE'] == 'TOP') ? ' clvt' : ''?><?=($arSetting['CART_LOCATION']['VALUE'] == 'RIGHT') ? ' clvr' : ''?><?=($arSetting['CART_LOCATION']['VALUE'] == 'LEFT') ? ' clvl' : ''?>">
		<div class="page-wrapper">
			<?if($arSetting["SITE_BACKGROUND"]["VALUE"] == "Y"):?>
				<div class="center outer">
			<?endif;
			if($arSetting["CATALOG_LOCATION"]["VALUE"] == "HEADER"):?>
				<div class="top-menu">
					<div class="center<?=($arSetting['SITE_BACKGROUND']['VALUE'] == 'Y' ? ' inner' : '');?>">
						<?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/top_menu.php"), false, array("HIDE_ICONS" => "Y"));?>
					</div>
				</div>
			<?endif;?>
			<header>
				<div class="center<?=($arSetting['SITE_BACKGROUND']['VALUE'] == 'Y' ? ' inner' : '');?>">
					<div class="header_1 header-control">
						<div class="logo">
							<?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/company_logo.php"), false);?>
						</div>
					</div>
					<div class="header_2 header-control">
						<?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/header_search.php"), false, array("HIDE_ICONS" => "Y"));?>
					</div>
					<div class="header_3 header-control">
						<div class="schedule">
							<?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/schedule.php"), false);?>
						</div>
					</div>
					<div class="header_4 header-control">
                        <div class="header-auth">
                            <?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/header_auth.php"), false);?>
                        </div>
					</div>

                    <div class="header_5 header-control">
                        <div class="header-email">
                            <?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/header_email.php"), false);?>
                        </div>
                    </div>
				</div>
			</header>

<div class="top_panel">
				<div class="center<?=($arSetting['SITE_BACKGROUND']['VALUE'] == 'Y' ? ' inner' : '');?>">
                    <div class="panel_1 panel-control">
                         <?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/sections.php"), false, array("HIDE_ICONS" => "Y"));?>
                    </div>
                    <div class="panel_0 panel-control">
                        <a href="<?=SITE_DIR?>"><img alt="Политех-Инструмент" src="/upload/polytech-01.svg" style="margin-top:2px; margin-left:7px;" title="Политех-Инструмент"  height="42"></a>
                    </div>

					<div class="panel_2 panel-control">
                        <?$APPLICATION->IncludeComponent("bitrix:menu", "panel",
                            array(
                                "ROOT_MENU_TYPE" => "top",
                                "MENU_CACHE_TYPE" => "A",
                                "MENU_CACHE_TIME" => "36000000",
                                "MENU_CACHE_USE_GROUPS" => "Y",
                                "MENU_CACHE_GET_VARS" => array(),
                                "MAX_LEVEL" => "3",
                                "CHILD_MENU_TYPE" => "topchild",
                                "USE_EXT" => "N",
                                "ALLOW_MULTI_SELECT" => "N",
                                "CACHE_SELECTED_ITEMS" => "N"
                            ),
                            false
                        );?>

					</div>
                    <div class="panel_5 panel-control">
                        <a class="show-email" href="javascript:void(0)"><i class="fa fa-email"></i></a>
                    </div>
					<div class="panel_3 panel-control">
						<ul class="contacts-vertical">
							<li>
                                <a id="callbackAnch" class="showcontacts" href="javascript:void(0)"><i class="fa fa-phone"></i></a>
								<?php /* Выпадающие контакты <a class="showcontacts" href="javascript:void(0)"><i class="fa fa-phone"></i></a>
 */?>
							</li>
						</ul>
					</div>
					<div class="panel_4 panel-control">
						<ul class="search-vertical">
							<li>
								<a class="showsearch" href="javascript:void(0)"><i class="fa fa-search"></i></a>
							</li>
						</ul>
					</div>
				</div>
			</div>
			<div class="content-wrapper">
				<div class="center<?=($arSetting['SITE_BACKGROUND']['VALUE'] == 'Y' ? ' inner' : '');?>">
					<div class="content">
						<?$inOrderPage = CSite::InDir("/personal/order/make/");
						if(!$inOrderPage):?>
							<div class="left-column">
								<?if($APPLICATION->GetDirProperty("PERSONAL_SECTION")):?>
									<div class="h3"><?=Loc::getMessage("PERSONAL_HEADER");?></div>
									<?$APPLICATION->IncludeComponent("bitrix:menu", "my_calculation",
										array(
											"ROOT_MENU_TYPE" => "my_calculation",
											"MENU_CACHE_TYPE" => "A",
											"MENU_CACHE_TIME" => "36000000",
											"MENU_CACHE_USE_GROUPS" => "Y",
											"MENU_CACHE_GET_VARS" => array(),
											"MAX_LEVEL" => "1",
											"CHILD_MENU_TYPE" => "personal",
											"USE_EXT" => "Y",
											"DELAY" => "N",
											"ALLOW_MULTI_SELECT" => "N",
											"CACHE_SELECTED_ITEMS" => "N",
										),
										false
									);?>

                                        <?$APPLICATION->IncludeComponent("bitrix:menu", "personal_with_header",
                                            array(
                                                "ROOT_MENU_TYPE" => "my_orders",
                                                "MENU_CACHE_TYPE" => "A",
                                                "MENU_CACHE_TIME" => "36000000",
                                                "MENU_CACHE_USE_GROUPS" => "Y",
                                                "MENU_CACHE_GET_VARS" => array(),
                                                "MAX_LEVEL" => "1",
                                                "CHILD_MENU_TYPE" => "personal",
                                                "USE_EXT" => "Y",
                                                "DELAY" => "N",
                                                "ALLOW_MULTI_SELECT" => "N",
                                                "CACHE_SELECTED_ITEMS" => "N",
                                                "HEADER" => "Мои заказы",
                                            ),
                                            false
                                        );?>

                                    <?$APPLICATION->IncludeComponent("bitrix:menu", "personal_with_header",
                                        array(
                                            "ROOT_MENU_TYPE" => "products",
                                            "MENU_CACHE_TYPE" => "A",
                                            "MENU_CACHE_TIME" => "36000000",
                                            "MENU_CACHE_USE_GROUPS" => "Y",
                                            "MENU_CACHE_GET_VARS" => array(),
                                            "MAX_LEVEL" => "1",
                                            "CHILD_MENU_TYPE" => "personal",
                                            "USE_EXT" => "Y",
                                            "DELAY" => "N",
                                            "ALLOW_MULTI_SELECT" => "N",
                                            "CACHE_SELECTED_ITEMS" => "N",
                                            "HEADER" => "Товары",
                                        ),
                                        false
                                    );?>

                                    <?$APPLICATION->IncludeComponent("bitrix:menu", "personal_with_header",
                                        array(
                                            "ROOT_MENU_TYPE" => "profile",
                                            "MENU_CACHE_TYPE" => "A",
                                            "MENU_CACHE_TIME" => "36000000",
                                            "MENU_CACHE_USE_GROUPS" => "Y",
                                            "MENU_CACHE_GET_VARS" => array(),
                                            "MAX_LEVEL" => "1",
                                            "CHILD_MENU_TYPE" => "personal",
                                            "USE_EXT" => "Y",
                                            "DELAY" => "N",
                                            "ALLOW_MULTI_SELECT" => "N",
                                            "CACHE_SELECTED_ITEMS" => "N",
                                            "HEADER" => "Профиль",
                                        ),
                                        false
                                    );?>

								<?php else:
									if($arSetting["CATALOG_LOCATION"]["VALUE"] == "LEFT"):?>
                                        <? //  echo"<pre>"; print_r($arSetting["BLOCK_LEFT"]["LIST"]["CATALOG_MENU_LEFT"]); echo "</pre>";  ?>
                                        <a class="h33" href="https://ptech.ru/upload/iblock/0c2/zhqnaaymc26uvi9s5c7ld2suyztra7e5.pdf" target="_blank">
											Скачать каталог
											<i class="fa fa-arrow-down"></i>

										</a>
                                        <div  <?=$arSetting["BLOCK_LEFT"]["LIST"]["CATALOG_MENU_LEFT"]["CURRENT"]=="Y" ? 'id="catalog_wrap"': ""?> >
										<?$APPLICATION->IncludeComponent("bitrix:menu", $arSetting["CATALOG_VIEW"]["VALUE"] == "FOUR_LEVELS" ? "tree" : "sections",
											array(
												"ROOT_MENU_TYPE" => "left",
												"MENU_CACHE_TYPE" => "A",
												"MENU_CACHE_TIME" => "36000000",
												"MENU_CACHE_USE_GROUPS" => "Y",
												"MENU_CACHE_GET_VARS" => array(),
												"MAX_LEVEL" => "4",
												"CHILD_MENU_TYPE" => "left",
												"USE_EXT" => "Y",
												"DELAY" => "N",
												"ALLOW_MULTI_SELECT" => "N",
												"CACHE_SELECTED_ITEMS" => "N"
											),
											false
										);?>
                                        </div>
									<?endif;
								endif;
								if($arSetting["SMART_FILTER_LOCATION"]["VALUE"] == "VERTICAL"):
									$APPLICATION->ShowViewContent("filter_vertical");
								endif;
                                if ($arSetting["CATALOG_LOCATION"]["VALUE"] == "HEADER"):?>
                                    <?if(in_array("BANNERS", $arSetting["BLOCK_LEFT"]["VALUE"])) {?>
                                        <?$APPLICATION->IncludeComponent("bitrix:main.include", "",
                                            array(
                                                "AREA_FILE_SHOW" => "file",
                                                "PATH" => SITE_DIR . "include/banners_left.php",
                                                "AREA_FILE_RECURSIVE" => "N",
                                                "EDIT_MODE" => "html",
                                            ),
                                            false,
                                            array("HIDE_ICONS" => "Y")
                                        );?>
                                    <?}?>
                                    <?if($APPLICATION->GetCurPage(true) != SITE_DIR . "index.php") {?>
                                        <?$APPLICATION->IncludeComponent("bitrix:main.include", "",
                                            array(
                                                "AREA_FILE_SHOW" => "file",
                                                "PATH" => SITE_DIR . "include/slider_left.php",
                                                "AREA_FILE_RECURSIVE" => "N",
                                                "EDIT_MODE" => "html",
                                            ),
                                            false,
                                            array("HIDE_ICONS" => "Y")
                                        );?>
                                    <?}?>
                                    <?if(empty($arSetting["BLOCK_LEFT"]["VALUE"])){?>
                                        &nbsp;
                                    <?}?>

								<?endif;?>
                                <?if(in_array("LINK_N_S_D", $arSetting["BLOCK_LEFT"]["VALUE"])) {?>
                                    <ul class="new_leader_disc">
                                        <li>
                                            <a class="new" href="<?= SITE_DIR ?>catalog/newproduct/">
                                                <span class="icon"><?= Loc::getMessage("CR_TITLE_ICON_NEWPRODUCT") ?></span>
                                                <span class="text"><?= Loc::getMessage("CR_TITLE_NEWPRODUCT") ?></span>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="hit" href="<?= SITE_DIR ?>catalog/saleleader/">
                                                <span class="icon"><?= Loc::getMessage("CR_TITLE_ICON_SALELEADER") ?></span>
                                                <span class="text"><?= Loc::getMessage("CR_TITLE_SALELEADER") ?></span>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="discount" href="https://ptech.ru/promotions/">
                                                <span class="icon"><?= Loc::getMessage("CR_TITLE_ICON_DISCOUNT") ?></span>
                                                <span class="text"><?= Loc::getMessage("CR_TITLE_DISCOUNT") ?></span>
                                            </a>
                                        </li>
                                    </ul>
                                <?}?>
								<?if($arSetting["CATALOG_LOCATION"]["VALUE"] == "LEFT"):?>

                                    <?if(in_array("BANNERS", $arSetting["BLOCK_LEFT"]["VALUE"])) {?>
                                        <? $APPLICATION->IncludeComponent("bitrix:main.include", "",
                                            array(
                                                "AREA_FILE_SHOW" => "file",
                                                "PATH" => SITE_DIR . "include/banners_left.php",
                                                "AREA_FILE_RECURSIVE" => "N",
                                                "EDIT_MODE" => "html",
                                            ),
                                            false,
                                            array("HIDE_ICONS" => "Y")
                                        );?>
                                    <?}?>
									<?if($APPLICATION->GetCurPage(true)!= SITE_DIR."index.php") {?>
                                        <?if(in_array("SLIDER", $arSetting["BLOCK_LEFT"]["VALUE"])) {?>
                                            <?$APPLICATION->IncludeComponent("bitrix:main.include", "",
                                                array(
                                                    "AREA_FILE_SHOW" => "file",
                                                    "PATH" => SITE_DIR . "include/slider_left.php",
                                                    "AREA_FILE_RECURSIVE" => "N",
                                                    "EDIT_MODE" => "html",
                                                ),
                                                false,
                                                array("HIDE_ICONS" => "Y")
                                            );?>
                                        <?}?>
									<?}?>
								<?endif;?>
                                <?if(in_array("VENDORS", $arSetting["BLOCK_LEFT"]["VALUE"])) {?>
                                    <div class="vendors">
                                        <div class="h3"><?=Loc::getMessage("MANUFACTURERS");?></div>
                                        <?$APPLICATION->IncludeComponent("bitrix:main.include", "",
                                            array(
                                                "AREA_FILE_SHOW" => "file",
                                                "PATH" => SITE_DIR . "include/vendors_left.php",
                                                "AREA_FILE_RECURSIVE" => "N",
                                                "EDIT_MODE" => "html",
                                            ),
                                            false,
                                            array("HIDE_ICONS" => "Y")
                                        );?>
                                    </div>
                                <?}?>

                                <?php //1==2 скрыто 2022-03-23 не понадобилось удалить  ?>
                                <?if(in_array("SUBSCRIBE", $arSetting["BLOCK_LEFT"]["VALUE"]) && 1 == 2 )  {?>
                                    <div class="subscribe">
                                        <div class="h3"><?= Loc::getMessage("SUBSCRIBE");?></div>
                                        <p><?=Loc::getMessage("SUBSCRIBE_TEXT");?></p>
                                        <?$APPLICATION->IncludeComponent("bitrix:subscribe.form", "left",
                                            array(
                                                "USE_PERSONALIZATION" => "Y",
                                                "PAGE" => SITE_DIR . "personal/mailings/",
                                                "SHOW_HIDDEN" => "N",
                                                "CACHE_TYPE" => "A",
                                                "CACHE_TIME" => "36000000",
                                                "CACHE_NOTES" => ""
                                            ),
                                            false
                                        );?>
                                    </div>
                                <?}?>
                                <?if(in_array("NEWS", $arSetting["BLOCK_LEFT"]["VALUE"])) {?>
                                    <?$APPLICATION->IncludeComponent("bitrix:main.include", "",
                                        array(
                                            "AREA_FILE_SHOW" => "file",
                                            "PATH" => SITE_DIR . "include/news_left.php",
                                            "AREA_FILE_RECURSIVE" => "N",
                                            "EDIT_MODE" => "html",
                                        ),
                                        false,
                                        array("HIDE_ICONS" => "Y")
                                    );?>
                                <?}?>
                                <?if(in_array("REVIEWS", $arSetting["BLOCK_LEFT"]["VALUE"])) {?>
                                    <?$APPLICATION->IncludeComponent("bitrix:main.include", "",
                                        array(
                                            "AREA_FILE_SHOW" => "file",
                                            "PATH" => SITE_DIR . "include/reviews_left.php",
                                            "AREA_FILE_RECURSIVE" => "N",
                                            "EDIT_MODE" => "html",
                                        ),
                                        false,
                                        array("HIDE_ICONS" => "Y")
                                    );?>
                                <?}?>
							</div>
						<?endif;?>
						<div class="workarea<?=($inOrderPage ? ' workarea-order' : '');?>">
	<?if($arSetting["CATALOG_LOCATION"]["VALUE"] == "LEFT"):?>
				<div class="top-menu">
					<div class="center1<?=($arSetting['SITE_BACKGROUND']['VALUE'] == 'Y' ? ' inner' : '');?>">
						<?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/top_menu.php"), false, array("HIDE_ICONS" => "Y"));?>
					</div>
				</div>
			<?elseif($arSetting["CATALOG_LOCATION"]["VALUE"] == "HEADER"):?>
				<div class="top-catalog">
					<div class="center<?=($arSetting['SITE_BACKGROUND']['VALUE'] == 'Y' ? ' inner' : '');?>">
						<?$APPLICATION->IncludeComponent("bitrix:menu", $arSetting["CATALOG_VIEW"]["VALUE"] == "FOUR_LEVELS" ? "tree" : "sections",
							array(
								"ROOT_MENU_TYPE" => "left",
								"MENU_CACHE_TYPE" => "A",
								"MENU_CACHE_TIME" => "36000000",
								"MENU_CACHE_USE_GROUPS" => "Y",
								"MENU_CACHE_GET_VARS" => array(),
								"MAX_LEVEL" => "4",
								"CHILD_MENU_TYPE" => "left",
								"USE_EXT" => "Y",
								"DELAY" => "N",
								"ALLOW_MULTI_SELECT" => "N",
								"CACHE_SELECTED_ITEMS" => "N"
							),
							false
						);?>
					</div>
				</div>
			<?endif;?><br>
							<?if($APPLICATION->GetCurPage(true)== SITE_DIR."index.php"):
								if(in_array("SLIDER", $arSetting["HOME_PAGE"]["VALUE"])):?>
									<?$APPLICATION->IncludeComponent("bitrix:main.include", "",
										array(
											"AREA_FILE_SHOW" => "file",
											"PATH" => SITE_DIR."include/slider.php",
											"AREA_FILE_RECURSIVE" => "N",
											"EDIT_MODE" => "html",
										),
										false,
										array("HIDE_ICONS" => "Y")
									);?>
								<?endif;

    ?>
    <?

								if(in_array("ADVANTAGES", $arSetting["HOME_PAGE"]["VALUE"])):
									global $arAdvFilter;
									$arAdvFilter = array(
										"!PROPERTY_SHOW_HOME" => false
									);?>
									<?$APPLICATION->IncludeComponent("bitrix:main.include", "",
										array(
											"AREA_FILE_SHOW" => "file",
											"PATH" => SITE_DIR."include/advantages.php",
											"AREA_FILE_RECURSIVE" => "N",
											"EDIT_MODE" => "html",
										),
										false,
										array("HIDE_ICONS" => "Y")
									);?>
								<?endif;?>

								<?if(in_array("PROMOTIONS", $arSetting["HOME_PAGE"]["VALUE"])):?>
									<?$APPLICATION->IncludeComponent("bitrix:main.include", "",
										array(
											"AREA_FILE_SHOW" => "file",
											"PATH" => SITE_DIR."include/promotions.php",
											"AREA_FILE_RECURSIVE" => "N",
											"EDIT_MODE" => "html",
										),
										false,
										array("HIDE_ICONS" => "Y")
									);?>
								<?endif;

								if(in_array("BANNERS", $arSetting["HOME_PAGE"]["VALUE"])):?>
									<?$APPLICATION->IncludeComponent("bitrix:main.include", "",
										array(
											"AREA_FILE_SHOW" => "file",
											"PATH" => SITE_DIR."include/banners_main.php",
											"AREA_FILE_RECURSIVE" => "N",
											"EDIT_MODE" => "html",
										),
										false,
										array("HIDE_ICONS" => "Y")
									);?>
								<?endif;


								if(in_array("TABS", $arSetting["HOME_PAGE"]["VALUE"])):?>
									<div class="tabs-wrap tabs-main">
										<ul class="tabs">
											<?if(in_array("RECOMMEND", $arSetting["HOME_PAGE"]["VALUE"])):?>
												<li class="tabs__tab recommend">
													<a href="javascript:void(0)"><span><?=Loc::getMessage("CR_TITLE_RECOMMEND")?></span></a>
												</li>
											<?endif;?>
											<li class="tabs__tab new">
												<a href="javascript:void(0)"><span><?=Loc::getMessage("CR_TITLE_NEWPRODUCT")?></span></a>
											</li>
											<li class="tabs__tab hit">
												<a href="javascript:void(0)"><span><?=Loc::getMessage("CR_TITLE_SALELEADER")?></span></a>
											</li>
											<li class="tabs__tab discount">
												<a href="javascript:void(0)"><span><?=Loc::getMessage("CR_TITLE_DISCOUNT")?></span></a>
											</li>
                                            <li class="tabs__tab catalog">
                                                <a href="javascript:void(0)"><span><?=Loc::getMessage("CR_TITLE_CATALOG")?></span></a>
                                            </li>
										    <li class="tabs__tab" ><a href="/promotions/"><b style="color: #C71848;">АКЦИИ</b></a>
											</li>
										</ul>

                                        <?if(in_array("RECOMMEND", $arSetting["HOME_PAGE"]["VALUE"])):?>
											<div class="tabs__box recommend">
												<?$APPLICATION->IncludeComponent("bitrix:main.include", "",
													array(
														"AREA_FILE_SHOW" => "file",
														"PATH" => SITE_DIR."include/recommend.php",
														"AREA_FILE_RECURSIVE" => "N",
														"EDIT_MODE" => "html",
													),
													false,
													array("HIDE_ICONS" => "Y")
												);?>
											</div>
										<?endif;?>
										<div class="tabs__box new">
											<div class="catalog-top">
												<?$APPLICATION->IncludeComponent("bitrix:main.include", "",
													array(
														"AREA_FILE_SHOW" => "file",
														"PATH" => SITE_DIR."include/newproduct.php",
														"AREA_FILE_RECURSIVE" => "N",
														"EDIT_MODE" => "html",
													),
													false,
													array("HIDE_ICONS" => "Y")
												);?>
												<a class="all" href="<?=SITE_DIR?>catalog/newproduct/"><?=Loc::getMessage("CR_TITLE_ALL_NEWPRODUCT");?></a>
											</div>
										</div>
										<div class="tabs__box hit">
											<div class="catalog-top">
												<?$APPLICATION->IncludeComponent("bitrix:main.include", "",
													array(
														"AREA_FILE_SHOW" => "file",
														"PATH" => SITE_DIR . "include/saleleader.php",
														"AREA_FILE_RECURSIVE" => "N",
														"EDIT_MODE" => "html",
													),
													false,
													array("HIDE_ICONS" => "Y")
												);?>
												<a class="all" href="<?=SITE_DIR?>catalog/saleleader/"><?=Loc::getMessage("CR_TITLE_ALL_SALELEADER");?></a>
											</div>
										</div>
										<div class="tabs__box discount">
											<div class="catalog-top">
												<?$APPLICATION->IncludeComponent("bitrix:main.include", "",
													array(
														"AREA_FILE_SHOW" => "file",
														"PATH" => SITE_DIR."include/discount.php",
														"AREA_FILE_RECURSIVE" => "N",
														"EDIT_MODE" => "html",
													),
													false,
													array("HIDE_ICONS" => "Y")
												);?>
												<a class="all" href="<?=SITE_DIR?>catalog/discount/"><?=Loc::getMessage("CR_TITLE_ALL_DISCOUNT");?></a>
											</div>
										</div>
                                        <div class="tabs__box catalog">
                                            <div class="homepage-catalog">
                                                <?$APPLICATION->IncludeComponent(
                                                    "bitrix:catalog.section.list",
                                                    "homepage_tile",
                                                    Array(
                                                        "ADD_SECTIONS_CHAIN" => "Y",
                                                        "CACHE_GROUPS" => "Y",
                                                        "CACHE_TIME" => "36000000",
                                                        "CACHE_TYPE" => "A",
                                                        "COMPOSITE_FRAME_MODE" => "A",
                                                        "COMPOSITE_FRAME_TYPE" => "AUTO",
                                                        "COUNT_ELEMENTS" => "N",
                                                        "HIDE_SECTION_NAME" => "Y",
                                                        "IBLOCK_ID" => "26",
                                                        "IBLOCK_TYPE" => "1c_catalog",
                                                        "SECTION_CODE" => "",
                                                        "SECTION_FIELDS" => array("ID","CODE","XML_ID","NAME","PICTURE","UF_SECTION_ICON"),
                                                        "SECTION_ID" => $_REQUEST["SECTION_ID"],
                                                        "SECTION_URL" => "",
                                                        "SECTION_USER_FIELDS" => array("",""),
                                                        "SHOW_PARENT_NAME" => "Y",
                                                        "TOP_DEPTH" => "1",
                                                    )
                                                );?>
                                            </div>
                                        </div>
										<div class="clr"></div>
									</div>
								<?endif;
							endif;?>
							<div class="body_text">
								<?if($APPLICATION->GetCurPage(true)!= SITE_DIR."index.php"):?>

									<div class="breadcrumb-share">
										<div id="navigation" class="breadcrumb">
											<?$APPLICATION->IncludeComponent("bitrix:breadcrumb", ".default",
												array(
													"START_FROM" => "0",
													"PATH" => "",
													"SITE_ID" => "-"
												),
												false,
												array("HIDE_ICONS" => "Y")
											);?>
										</div>
										<div class="share">
										<script src="//yastatic.net/es5-shims/0.0.2/es5-shims.min.js"></script>
<script src="//yastatic.net/share2/share.js"></script>
<div class="ya-share2" data-services="vkontakte,facebook,odnoklassniki,viber,whatsapp,telegram" data-size="s"></div>
																						
										</div>
									</div>

									<?php if (! preg_match('/^\/vendors\//', $APPLICATION->GetCurPage())) :?>
									<h1 id="pagetitle"><?=$APPLICATION->ShowTitle(false);?></h1>
									<?php endif ?>
								<?endif;?>