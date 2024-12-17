<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

global $arSetting;
use Bitrix\Sale\DiscountCouponsManager;?>

<?//avg?>
<style>
    
.alert-noInt {
		display: none;
	color: red;
    font-size: 10px;
}
</style>

<div class="cart-items" id="id-cart-list">
	<div class="sort-clear">
		<div class="sort">
            <?if($countItemsDelay = count($arResult["ITEMS"]["DelDelCanBuy"])):?>
			    <div class="sorttext"><?=GetMessage("SALE_PRD_IN_BASKET")?></div>
    			<a href="javascript:void(0)" class="sortbutton current"><?=GetMessage("SALE_PRD_IN_BASKET_ACT")?></a>
				<a href="javascript:void(0)" onclick="showBasketItems(2);" class="sortbutton"><?=GetMessage("SALE_PRD_IN_BASKET_SHELVE")?> (<?=$countItemsDelay?>)</a>
			<?endif?>			
		</div>
		<?if(count($arResult["ITEMS"]["AnDelCanBuy"]) > 0):?>
			<div class="clear">
				<a class="btn_buy apuo clear_cart" href="<?=$arUrlTempl['BasketClear']?>" title="<?=GetMessage('SALE_CLEAR_CART')?>"><span class="clear_cont"><i class="fa fa-times"></i><span><?=GetMessage("SALE_CLEAR_CART")?></span></span></a>
			</div>
		<?endif;?>
	</div>
    <?$APPLICATION->IncludeComponent(
        "pvp:search.propertyandorder",
        "",
        Array(
            "COMPOSITE_FRAME_MODE" => "A",
            "COMPOSITE_FRAME_TYPE" => "AUTO",
            "IBLOCK_ID" => "26",
            "IBLOCK_TYPE" => "1c_catalog",
            "PROPERTY_CODE" => "CML2_ARTICLE"
        )
    );?>
    <div  id="min_price_message"  class="alertMsg info disN">
		  <i class="fa fa-info"></i>
		   <span class="text"><?=GetMessage('ORDER_MIN_PRICE')?><?=$arSetting["ORDER_MIN_PRICE"]["VALUE"]?><?=$arResult["allSum_CURRENCY"]?></span>
	</div> 
	<?if(count($arResult["ITEMS"]["AnDelCanBuy"]) > 0):?>	
		<div class="equipment" id="cart_equipment">
			<div class="thead">
				<div class="cart-item-image"><?=GetMessage("SALE_IMAGE")?></div>
				<?if(in_array("NAME", $arParams["COLUMNS_LIST"])):?>
					<div class="cart-item-name"><?=GetMessage("SALE_NAME")?></div>
				<?endif;?>

                <div class="catalog-item-favorite">
                </div>

                <?if(in_array("PRICE", $arParams["COLUMNS_LIST"])):?>
					<div class="cart-item-price"><?=GetMessage("SALE_PRICE")?></div>
				<?endif;?>
				<?if(in_array("QUANTITY", $arParams["COLUMNS_LIST"])):?>
					<div class="cart-item-quantity"><?=GetMessage("SALE_QUANTITY")?></div>
				<?endif;?>
				<div class="cart-item-summa"><?=GetMessage("SALE_SUMMA")?></div>
				<?if(in_array("DELAY", $arParams["COLUMNS_LIST"])):?>
					<div class="cart-item-actions"><?=GetMessage("SALE_ACTIONS")?></div>
				<?endif;?>
			</div>
			<div class="tbody">
				<?$i=0;
				foreach($arResult["ITEMS"]["AnDelCanBuy"] as $arBasketItems) {?>
					<div class="tr" id="<?=$arBasketItems['ID']?>">
						<div class="tr_into">							
							<div class="cart-item-image">
								<?if(is_array($arBasketItems["DETAIL_PICTURE"])):?>
									<img src="<?=$arBasketItems['DETAIL_PICTURE']['src']?>" width="<?=$arBasketItems['DETAIL_PICTURE']['width']?>" height="<?=$arBasketItems['DETAIL_PICTURE']['height']?>" />
								<?else:?>
									<img src="<?=SITE_TEMPLATE_PATH?>/images/no-photo.jpg" width="65" height="65" />
								<?endif?>
							</div>

                            <?php if($arBasketItems["CML2_ARTICLE"]) : ?>
                                <div class="cart-item-article">
                                    <?=$arBasketItems["CML2_ARTICLE"]?>
                                </div>
                            <?php endif ?>

                            <?if(in_array("NAME", $arParams["COLUMNS_LIST"])):?>
								<div class="cart-item-name">
									<?if(strlen($arBasketItems["DETAIL_PAGE_URL"])>0):?>
										<a href="<?=$arBasketItems["DETAIL_PAGE_URL"]?>">
									<?endif;?>
										<?=$arBasketItems["NAME"] ?>
									<?if(strlen($arBasketItems["DETAIL_PAGE_URL"])>0):?>
										</a>
									<?endif;?>
									<?if(in_array("PROPS", $arParams["COLUMNS_LIST"])):
										foreach($arBasketItems["PROPS"] as $val) {
											echo "<br />".$val["NAME"].": ".$val["VALUE"];
										}
									endif;?>
								</div>
							<?endif;?>

                            <div class="catalog-item-favorite">
                                <?$APPLICATION->IncludeComponent(
                                    "pvp:favorites.button",
                                    "",
                                    Array(
                                        "COMPOSITE_FRAME_MODE" => "A",
                                        "COMPOSITE_FRAME_TYPE" => "AUTO",
                                        "PRODUCT_ID" => $arBasketItems['PRODUCT_ID']
                                    )
                                );?>
                            </div>

							<?if(in_array("PRICE", $arParams["COLUMNS_LIST"])):?>
								<div class="cart-item-price">									
									<div class="old-price" id="old-price_<?=$arBasketItems['ID']?>">
										<?if($arBasketItems["DISCOUNT_PRICE"] > 0):
											echo $arBasketItems["FULL_PRICE_FORMATED"];
										endif;?>
									</div>
									<div class="price" id="price_<?=$arBasketItems['ID']?>">
										<?=$arBasketItems["PRICE_FORMATED"]?>
									</div>
									<?if($arSetting["REFERENCE_PRICE"]["VALUE"] == "Y" && !empty($arSetting["REFERENCE_PRICE_COEF"]["VALUE"])):?>
										<div class="reference-price" id="reference-price_<?=$arBasketItems['ID']?>" data-reference-coef="<?=$arSetting['REFERENCE_PRICE_COEF']['VALUE']?>" data-separator="<?=$arBasketItems['item_THOUSANDS_SEP']?>" data-reference-decimal="<?=$arBasketItems['itemReference_DECIMALS']?>" data-dec-point="<?=$arBasketItems['item_DEC_POINT']?>" data-hide-zero="<?=$arBasketItems['item_HIDE_ZERO']?>">
											<span id="itemReferenceVal_<?=$arBasketItems['ID']?>"><?=number_format(($arBasketItems["PRICE"] * $arSetting["REFERENCE_PRICE_COEF"]["VALUE"]), $arBasketItems["itemReference_DECIMALS"], $arBasketItems["item_DEC_POINT"], $arBasketItems["item_THOUSANDS_SEP"])?></span>
											<span class="curr"><?=$arBasketItems["item_CURRENCY"]?></span>
										</div>										
									<?endif;
									if(!empty($arBasketItems["MEASURE_TEXT"])):?>
										<div class="unit">
											<?=GetMessage('UNIT')?>
											<span id="unit_<?=$arBasketItems['ID']?>"><?=$arBasketItems["MEASURE_TEXT"]?></span>
										</div>
									<?endif;?>
								</div>
							<?endif;?>							
							<?if(in_array("QUANTITY", $arParams["COLUMNS_LIST"])):
								$ratio = isset($arBasketItems["MEASURE_RATIO"]) ? $arBasketItems["MEASURE_RATIO"] : 0;
								$max = isset($arBasketItems["AVAILABLE_QUANTITY"]) ? " max='".$arBasketItems["AVAILABLE_QUANTITY"]."'" : "";
								$useFloatQuantity = ($arParams["QUANTITY_FLOAT"] == "Y") ? true : false;
								$useFloatQuantityJS = ($useFloatQuantity ? "true" : "false");?>
								<div class="cart-item-quantity">									
									<div style="float:right;" class="buy_more">
										<a href="javascript:void(0)" class="minus" onclick="setQuantity(<?=$arBasketItems['ID']?>, <?=$arBasketItems['MEASURE_RATIO']?>, 'down', <?=$useFloatQuantityJS?>);"><span>-</span></a>
										<input type="text" name="QUANTITY_INPUT_<?=$arBasketItems["ID"]?>" id="QUANTITY_INPUT_<?=$arBasketItems["ID"]?>" class="quantity"<?=$max?> step="<?=$ratio?>" value="<?=$arBasketItems["QUANTITY"]?>" onchange="updateQuantity('QUANTITY_INPUT_<?=$arBasketItems["ID"]?>', '<?=$arBasketItems["ID"]?>', <?=$ratio?>, <?=$useFloatQuantityJS?>)" />
										<a href="javascript:void(0)" class="plus" onclick="setQuantity(<?=$arBasketItems['ID']?>, <?=$arBasketItems['MEASURE_RATIO']?>, 'up', <?=$useFloatQuantityJS?>);"><span>+</span></a>
										<?//avg?>
                                        <div class="alert-noInt">noInt</div>
                                        <?//.avg?>
									</div>
									<input type="hidden" id="QUANTITY_<?=$arBasketItems['ID']?>" name="QUANTITY_<?=$arBasketItems['ID']?>" value="<?=$arBasketItems["QUANTITY"]?>" />
								</div>
							<?endif;?>							
							<div class="cart-item-summa" id="cart-item-summa_<?=$arBasketItems['ID']?>" data-itemsum="<?=($arBasketItems['PRICE'] * $arBasketItems['QUANTITY'])?>"<?=($arSetting["REFERENCE_PRICE"]["VALUE"] == "Y" && !empty($arSetting["REFERENCE_PRICE_COEF"]["VALUE"]) ? " data-itemreferencesum='".$arBasketItems["PRICE"] * $arBasketItems["QUANTITY"] * $arSetting["REFERENCE_PRICE_COEF"]["VALUE"]."' data-itemreferencesumcoef='".$arSetting["REFERENCE_PRICE_COEF"]["VALUE"]."' data-reference-decimal='".$arBasketItems["itemReferenceSum_DECIMALS"]."'" : "");?> data-separator="<?=$arBasketItems['item_THOUSANDS_SEP']?>" data-decimal="<?=$arBasketItems['itemSum_DECIMALS']?>" data-dec-point="<?=$arBasketItems['item_DEC_POINT']?>" data-hide-zero="<?=$arBasketItems['item_HIDE_ZERO']?>">
								<span class="sum">
									<span id="itemSumVal_<?=$arBasketItems['ID']?>"><?=number_format(($arBasketItems["PRICE"] * $arBasketItems["QUANTITY"]), $arBasketItems["itemSum_DECIMALS"], $arBasketItems["item_DEC_POINT"], $arBasketItems["item_THOUSANDS_SEP"])?></span>
									<span class="curr"><?=$arBasketItems["item_CURRENCY"]?></span>
								</span>
								<?if($arSetting["REFERENCE_PRICE"]["VALUE"] == "Y" && !empty($arSetting["REFERENCE_PRICE_COEF"]["VALUE"])):?>
									<span class="reference-sum">
										<span id="itemReferenceSumVal_<?=$arBasketItems['ID']?>"><?=number_format(($arBasketItems["PRICE"] * $arBasketItems["QUANTITY"] * $arSetting["REFERENCE_PRICE_COEF"]["VALUE"]), $arBasketItems["itemReferenceSum_DECIMALS"], $arBasketItems["item_DEC_POINT"], $arBasketItems["item_THOUSANDS_SEP"])?></span>
										<span class="curr"><?=$arBasketItems["item_CURRENCY"]?></span>
									</span>
								<?endif;?>
							</div>							
							<?if(in_array("DELAY", $arParams["COLUMNS_LIST"])):?>
								<div class="cart-item-actions">

                                    <?php /*
 //Скрывается параметрами только вместе с кнопокй удалить просто закомментим
									<div class="delay">
										<a class="setaside" href="<?=str_replace("#ID#", $arBasketItems["ID"], $arUrlTempl["delay"])?>" title="<?=GetMessage("SALE_OTLOG")?>"><i class="fa fa-heart-o"></i></a>
									</div>
 */?>
									<?if(in_array("DELETE", $arParams["COLUMNS_LIST"])):?>
										<div class="delete">
											<a class="deleteitem" href="<?=str_replace("#ID#", $arBasketItems["ID"], $arUrlTempl["delete"])?>" onclick="//return DeleteFromCart(this);" title="<?=GetMessage("SALE_DELETE_PRD")?>"><i class="fa fa-trash-o"></i></a>
										</div>
									<?endif;?>
								</div>
							<?endif;?>
						</div>
					</div>
					<?$i++;
				}?>
				<input type="hidden" id="column_headers" value="<?=CUtil::JSEscape(implode(',', $arParams['COLUMNS_LIST']))?>" />
				<input type="hidden" id="offers_props" value="<?=CUtil::JSEscape(implode(',', $arParams['OFFERS_PROPS']))?>" />
				<input type="hidden" id="action_var" value="<?=CUtil::JSEscape($arParams['ACTION_VARIABLE'])?>" />
				<input type="hidden" id="quantity_float" value="<?=$arParams['QUANTITY_FLOAT']?>" />
				<input type="hidden" id="count_discount_4_all_quantity" value="<?=($arParams['COUNT_DISCOUNT_4_ALL_QUANTITY'] == 'Y') ? 'Y' : 'N'?>" />
				<input type="hidden" id="price_vat_show_value" value="<?=($arParams['PRICE_VAT_SHOW_VALUE'] == 'Y') ? 'Y' : 'N'?>" />
				<input type="hidden" id="hide_coupon" value="<?=($arParams['HIDE_COUPON'] == 'Y') ? 'Y' : 'N'?>" />
				<input type="hidden" id="use_prepayment" value="<?=($arParams['USE_PREPAYMENT'] == 'Y') ? 'Y' : 'N'?>" />
				<input type="hidden" id="auto_calculation" value="<?=($arParams["AUTO_CALCULATION"] == "N") ? "N" : "Y"?>" />
				<div class="myorders_itog<?=($arSetting['REFERENCE_PRICE']['VALUE'] == 'Y' && !empty($arSetting['REFERENCE_PRICE_COEF']['VALUE']) ? ' reference' : '');?>">
					<div class="cart-itogo"><?=GetMessage("SALE_ITOGO")?>:</div>
					<div class="cart-allsum" id="cart-allsum" data-allsum="<?=$arResult['allSum']?>"<?=($arSetting["REFERENCE_PRICE"]["VALUE"] == "Y" && !empty($arSetting["REFERENCE_PRICE_COEF"]["VALUE"]) ? " data-allreferencesum='".$arResult["allSum"] * $arSetting["REFERENCE_PRICE_COEF"]["VALUE"]."' data-allreferencesumcoef='".$arSetting["REFERENCE_PRICE_COEF"]["VALUE"]."' data-reference-decimal='".$arResult["allReferenceSum_DECIMALS"]."'" : "");?> data-separator="<?=$arResult['allSum_THOUSANDS_SEP']?>" data-decimal="<?=$arResult['allSum_DECIMALS']?>" data-dec-point="<?=$arResult['allSum_DEC_POINT']?>" data-hide-zero="<?=$arResult['allSum_HIDE_ZERO']?>">
						<span class="allsum">
							<span id="allSumVal"><?=number_format($arResult["allSum"], $arResult["allSum_DECIMALS"], $arResult["allSum_DEC_POINT"], $arResult["allSum_THOUSANDS_SEP"])?></span>
							<span class="curr"><?=$arResult["allSum_CURRENCY"]?></span>
						</span>
						<?if($arSetting["REFERENCE_PRICE"]["VALUE"] == "Y" && !empty($arSetting["REFERENCE_PRICE_COEF"]["VALUE"])):?>
							<span class="reference-allsum">
								<span id="allReferenceSumVal"><?=number_format($arResult["allSum"] * $arSetting["REFERENCE_PRICE_COEF"]["VALUE"], $arResult["allReferenceSum_DECIMALS"], $arResult["allSum_DEC_POINT"], $arResult["allSum_THOUSANDS_SEP"])?></span>
								<span class="curr"><?=$arResult["allSum_CURRENCY"]?></span>
							</span>
						<?endif;?>
					</div>
				</div>				
			</div>
		</div>		
		<div class="w100p">			
			<?if($arParams["HIDE_COUPON"] != "Y"):?>	
				<div class="cart-coupon" id="cart-coupon">
					<div class="bx_ordercart_coupon">						
						<input type="text" id="coupon" name="COUPON" value="" placeholder="<?=GetMessage("SALE_COUPON_VAL")?>" onchange="enterCoupon();" />
						<button type="button" name="ENTER_COUPON" value="" onclick="enterCoupon();"><i class="fa fa-chevron-right"></i></button>
						<div class="clr"></div>
					</div>
					<?if(!empty($arResult["COUPON_LIST"])) {
						foreach($arResult["COUPON_LIST"] as $oneCoupon) {
							$couponClass = "disabled";
							switch($oneCoupon["STATUS"]) {
								case DiscountCouponsManager::STATUS_NOT_FOUND:
								case DiscountCouponsManager::STATUS_FREEZE:
									$couponClass = "bad";
									break;
								case DiscountCouponsManager::STATUS_APPLYED:
									$couponClass = "good";
									break;
							}?>
							<div class="bx_ordercart_coupon">								
								<input type="hidden" name="OLD_COUPON[]" value="<?=htmlspecialcharsbx($oneCoupon['COUPON']);?>" />
								<div class="old_coupon <?=$couponClass;?>"><?=htmlspecialcharsbx($oneCoupon["COUPON"]);?><?=isset($oneCoupon["CHECK_CODE_TEXT"]) ? " ".(is_array($oneCoupon["CHECK_CODE_TEXT"]) ? implode("<br>", $oneCoupon["CHECK_CODE_TEXT"]) : $oneCoupon["CHECK_CODE_TEXT"]) : "";?></div>
								<span class="close" data-coupon="<?=htmlspecialcharsbx($oneCoupon['COUPON']);?>"><i class="fa fa-times"></i></span>
								<div class="clr"></div>
							</div>
						<?}
						unset($couponClass, $oneCoupon);
					}?>
				</div>
			<?endif;?>
			
            <input type="hidden" id="min_price_vlue" value="<?=$arSetting["ORDER_MIN_PRICE"]["VALUE"]?>">
            <input type="hidden" id="total_price_basket" value="<?=$arResult["allSum"]?>">
            
			<div id="btn_buy_basket" class="cart-buttons">

                <?php
                /**
                 * Формы конфликтуют, сюда вынес кнопку, а сам компонент в template.php
                 */
                ?>
                <div class="pvp-order-exportxls-block ext-btn">
                        <a class="download-btn">
                            <span class="export-button-desc">Скачать заказ</span>
                            <span class="icon"></span>
                        </a>
                </div>
                <script>
                    BX.ready(function () {
                        $('body').on('click', '.pvp-order-exportxls-block.ext-btn .download-btn', function () {
                           $('.basket-download-form .export-form').submit();
                        });
                    });
                </script>
                <?php /*------------------------------------------------------*/?>
				<?if(in_array("BUTTON_BOC", $arSetting["GENERAL_SETTINGS"]["VALUE"])){?>
					<button type="button" id="boc_anch_cart" class="btn_buy boc_anch_cart" name="boc_anch_cart" value="<?=GetMessage('SALE_BOC')?>"><?=GetMessage('SALE_BOC')?></button>
				<?}else{?>
				    <input type="hidden" id="boc_anch_cart" value="false">
				<?}?>
				<button type="button" id="btn_basket_order"  name="BasketOrder" class="btn_buy popdef bt3" value="<?=GetMessage('SALE_ORDER')?>" onclick="checkOut();"><?=GetMessage("SALE_ORDER")?></button>
			</div>
           
			<div class="clr"></div>
		</div>
	<?else:
		ShowNote(GetMessage("SALE_NO_ACTIVE_PRD"), "infotext");
	endif;?>
</div>

<?//avg?>
<script>    
 let i = setInterval(function() {
 
    var allInputs = document.querySelectorAll('#cart_equipment input[type="text"]');

    allInputs.forEach(function(elemInput) {
        
        let input_val = elemInput.value;

        let input_step = elemInput.step;

        let result = input_val / input_step;

        const parentElement = elemInput.parentElement;

        let div = parentElement.querySelector('div.alert-noInt');

        if( Number.isInteger(result) == false ) {

            div.style.display = 'block';
            div.textContent = 'не кратно: '+input_step;

             let btn_basket = document.getElementById('btn_basket_order');
            btn_basket.setAttribute('disabled', '');        

        } else {
            div.style.display = 'none';
        }; 
    });

}, 1000);
</script> 
<?//.avg?>