<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);?>

							</div>

                            <?php if($APPLICATION->GetCurPage(true)== SITE_DIR."index.php" ) : ?>

                                <?php if(in_array("VENDORS", $arSetting["HOME_PAGE"]["VALUE"])) : ?>
                                    <?$APPLICATION->IncludeComponent("bitrix:main.include", "",
                                        array(
                                            "AREA_FILE_SHOW" => "file",
                                            "PATH" => SITE_DIR . "include/vendors_bottom.php",
                                            "AREA_FILE_RECURSIVE" => "N",
                                            "EDIT_MODE" => "html",
                                        ),
                                        false,
                                        array("HIDE_ICONS" => "Y")
                                    );?>
                                <?php endif; ?>

                                <div style="clear: both"> </div>

                                <?php /** Стать партнером */?>
                                <?$APPLICATION->IncludeComponent("bitrix:main.include", "",
                                    array(
                                        "AREA_FILE_SHOW" => "file",
                                        "PATH" => SITE_DIR."include/become_partner.php",
                                        "AREA_FILE_RECURSIVE" => "N",
                                        "EDIT_MODE" => "html",
                                    ),
                                    false,
                                    array("HIDE_ICONS" => "Y")
                                );?>

					    	<?php endif;?>

						</div>

						<?if(!CSite::InDir('/news/')):?>
							<?$APPLICATION->IncludeComponent("bitrix:main.include", "",
								array(
									"AREA_FILE_SHOW" => "file",
									"PATH" => SITE_DIR."include/news_bottom.php",
									"AREA_FILE_RECURSIVE" => "N",
									"EDIT_MODE" => "html",
								),
								false,
								array("HIDE_ICONS" => "Y")
							);?>
						<?endif;?>
						<?if(!CSite::InDir('/reviews/')):?>
							<?$APPLICATION->IncludeComponent("bitrix:main.include", "",
								array(
									"AREA_FILE_SHOW" => "file",
									"PATH" => SITE_DIR."include/reviews_bottom.php",
									"AREA_FILE_RECURSIVE" => "N",
									"EDIT_MODE" => "html",
								),
								false,
								array("HIDE_ICONS" => "Y")
							);?>
						<?endif;?>
					</div>
<?php /* подписка на новости 2022-03-23
					<?$APPLICATION->IncludeComponent("bitrix:subscribe.form", "bottom",
						array(
							"USE_PERSONALIZATION" => "Y",
							"PAGE" => SITE_DIR."personal/mailings/",
							"SHOW_HIDDEN" => "N",
							"CACHE_TYPE" => "A",
							"CACHE_TIME" => "36000000",
							"CACHE_NOTES" => ""
						),
						false
					);?>
					*/
					?>
				</div>
			</div>
<div style="clear: both"></div>

<?php if ($APPLICATION->GetCurPage(true) != SITE_DIR . "index.php"): ?>
			<?$APPLICATION->IncludeComponent(
	"bitrix:main.include", 
	".default", 
	array(
		"AREA_FILE_SHOW" => "file",
		"PATH" => SITE_DIR."include/viewed_products.php",
		"COMPONENT_TEMPLATE" => ".default",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO",
		"EDIT_TEMPLATE" => "standard.php"
	),
	false
);?>
<?php endif; ?>


			<footer>
				<div class="center<?=($arSetting['SITE_BACKGROUND']['VALUE'] == 'Y' ? ' inner' : '');?>">
					<div class="footer_menu_soc_pay">
						<div class="footer_menu">
                            <div class="footer_menu__block">
                                <span class="block-header">
                                    О компании
                                </span>
							<?$APPLICATION->IncludeComponent("bitrix:menu", "bottom", 
								array(
									"ROOT_MENU_TYPE" => "footer1",
									"MENU_CACHE_TYPE" => "A",
									"MENU_CACHE_TIME" => "36000000",
									"MENU_CACHE_USE_GROUPS" => "Y",
									"MENU_CACHE_GET_VARS" => array(),
									"MAX_LEVEL" => "1",
									"CHILD_MENU_TYPE" => "",
									"USE_EXT" => "N",
									"ALLOW_MULTI_SELECT" => "N",
									"CACHE_SELECTED_ITEMS" => "N"
								),
								false
							);?>
                            </div>
                            <div class="footer_menu__block">
                                <span class="block-header">
                                    Пресс-центр
                                </span>
							<?$APPLICATION->IncludeComponent("bitrix:menu", "bottom", 
								array(
									"ROOT_MENU_TYPE" => "footer2",
									"MENU_CACHE_TYPE" => "A",
									"MENU_CACHE_TIME" => "36000000",
									"MENU_CACHE_USE_GROUPS" => "Y",
									"MENU_CACHE_GET_VARS" => array(),
									"MAX_LEVEL" => "1",
									"CHILD_MENU_TYPE" => "",
									"USE_EXT" => "N",
									"ALLOW_MULTI_SELECT" => "N",
									"CACHE_SELECTED_ITEMS" => "N"
								),
								false
							);?>
                            </div>
                            <div class="footer_menu__block">
                                <span class="block-header">
                                    Сотрудничество
                                </span>
							<?$APPLICATION->IncludeComponent("bitrix:menu", "bottom", 
								array(
									"ROOT_MENU_TYPE" => "footer3",
									"MENU_CACHE_TYPE" => "A",
									"MENU_CACHE_TIME" => "36000000",
									"MENU_CACHE_USE_GROUPS" => "Y",
									"MENU_CACHE_GET_VARS" => array(),
									"MAX_LEVEL" => "1",
									"CHILD_MENU_TYPE" => "",
									"USE_EXT" => "N",
									"ALLOW_MULTI_SELECT" => "N",
									"CACHE_SELECTED_ITEMS" => "N"
								),
								false
							);?>
                            </div>
                            <div class="footer_menu__block">
                                <span class="block-header">
                                    Акции
                                </span>
							<?$APPLICATION->IncludeComponent("bitrix:menu", "bottom", 
								array(
									"ROOT_MENU_TYPE" => "footer4",
									"MENU_CACHE_TYPE" => "A",
									"MENU_CACHE_TIME" => "36000000",
									"MENU_CACHE_USE_GROUPS" => "Y",
									"MENU_CACHE_GET_VARS" => array(),
									"MAX_LEVEL" => "1",
									"CHILD_MENU_TYPE" => "",
									"USE_EXT" => "N",
									"ALLOW_MULTI_SELECT" => "N",
									"CACHE_SELECTED_ITEMS" => "N"
								),
								false
							);?>
                            </div>
                            <?php if ($USER->IsAuthorized()) : ?>
                                <div class="footer_menu__block">
                                    <span class="block-header">
                                        Каталог
                                    </span>
                                <ul class="footer-catalog">
                                    <li>
                                        <a href="/upload/iblock/0c2/zhqnaaymc26uvi9s5c7ld2suyztra7e5.pdf" class="footer-catalog__link">Скачать</a>
                                    </li>
                                </ul>
                                </div>
                            <?php endif; ?>
						</div>
						<div class="footer_soc_pay">							
							<div class="footer_soc">
								<?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/join_us.php"), false, array("HIDE_ICONS" => "Y"));?>
							</div>
							<div class="footer_pay">
								<?global $arPayIcFilter;
								$arPayIcFilter = array();?>
								<?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/payments_icons.php"), false, array("HIDE_ICONS" => "Y"));?>
							</div>
						</div>
					</div>
					<div class="footer-bottom">						
						<div class="footer-bottom__blocks">
							<div class="footer-bottom__block-wrap fb-left">
								<div class="footer-bottom__block footer-bottom__copyright">
									<?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/copyright.php"), false);?>
								</div>
								<div class="footer-bottom__block footer-bottom__links">
									<?$APPLICATION->IncludeComponent("bitrix:menu", "bottom", 
										array(
											"ROOT_MENU_TYPE" => "bottom",
											"MENU_CACHE_TYPE" => "A",
											"MENU_CACHE_TIME" => "36000000",
											"MENU_CACHE_USE_GROUPS" => "Y",
											"MENU_CACHE_GET_VARS" => array(),
											"MAX_LEVEL" => "1",
											"CHILD_MENU_TYPE" => "",
											"USE_EXT" => "N",
											"ALLOW_MULTI_SELECT" => "N",
											"CACHE_SELECTED_ITEMS" => "N"
										),
										false
									);?>
								</div>
							</div>
						</div>
						<div class="footer-bottom__blocks">							
							<div class="footer-bottom__block-wrap fb-right">
								<div class="footer-bottom__block footer-bottom__counter">
									<?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/counter_1.php"), false);?>
								</div>
								<div class="footer-bottom__block footer-bottom__counter">
									<?$APPLICATION->IncludeComponent("bitrix:main.include", "", array(
	"AREA_FILE_SHOW" => "file",
		"PATH" => SITE_DIR."include/counter_2.php"
	),
	false,
	array(
	"ACTIVE_COMPONENT" => "N"
	)
);?>
								</div>
								<div class="footer-bottom__block footer-bottom__design">
									<?$APPLICATION->IncludeComponent("bitrix:main.include", "", array(
	"AREA_FILE_SHOW" => "file",
		"PATH" => SITE_DIR."include/developer.php"
	),
	false,
	array(
	"ACTIVE_COMPONENT" => "N"
	)
);?>
								</div>
							</div>
						</div>						
					</div>
					<div class="foot_panel_all">
						<div class="foot_panel">
							<div class="foot_panel_1">

                                <?$APPLICATION->IncludeComponent(
                                    "pvp:smsauth",
                                    "",
                                    Array(
                                        "MESSAGE_LIMIT" => "5",
                                        "SEND_TIMEOUT" => "20",
                                        "COMPOSITE_FRAME_MODE" => "A",
                                        "COMPOSITE_FRAME_TYPE" => "AUTO",
                                        "FORGOT_PASSWORD_URL" => "/personal/private/",
                                        "PROFILE_URL" => "/personal/private/",
                                        "REGISTER_URL" => "/personal/private/",
                                        "RESET_TIMEOUT" => "45",
                                        "CODE_TTL" => "120",
                                        "SHOW_ERRORS" => "Y"
                                    )
                                );?>

                                <?php if ($USER->isAuthorized()) : ?>
                                    <?$APPLICATION->IncludeComponent("bitrix:main.include", "",
                                        array(
                                            "AREA_FILE_SHOW" => "file",
                                            "PATH" => SITE_DIR."include/footer_compare.php"
                                        ),
                                        false,
                                        array("HIDE_ICONS" => "Y")
                                    );?>
                                <?php endif; ?>
							</div>

                            <?php if ($USER->isAuthorized()) : ?>
                                <div class="foot_panel_2">

                                    <?$APPLICATION->IncludeComponent(
                                        "pvp:favorites.line",
                                        "",
                                        Array(
                                            "COMPOSITE_FRAME_MODE" => "A",
                                            "COMPOSITE_FRAME_TYPE" => "AUTO",
                                            "PATH_TO_FAVORITES" => "/favorites/"
                                        )
                                    );?>

                                    <?$APPLICATION->IncludeComponent("bitrix:sale.basket.basket.line", ".default",
                                        array(
                                            "PATH_TO_BASKET" => SITE_DIR."personal/cart/",
                                            "PATH_TO_ORDER" => SITE_DIR."personal/order/make/",
                                            "HIDE_ON_BASKET_PAGES" => "N",
                                        ),
                                        false,
                                        array("HIDE_ICONS" => "Y")
                                    );?>
                                </div>
                            <?php endif; ?>
						</div>
					</div>
				</div>
			</footer>

			<?if($arSetting["SITE_BACKGROUND"]["VALUE"] == "Y"):?>
				</div>
			<?endif;?>
		</div>

		<!-- Yandex.Metrika counter -->
<script type="text/javascript" >
   (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
   m[i].l=1*new Date();k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
   (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

   ym(87277310, "init", {
        clickmap:true,
        trackLinks:true,
        accurateTrackBounce:true,
        webvisor:true,
        ecommerce:"dataLayer"
   });
</script>
<noscript><div><img src="https://mc.yandex.ru/watch/87277310" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->
<script src="https://app.vidwidget.ru/s/159bc136-e6d6-4137-b335-6021e7a76f81/"></script>