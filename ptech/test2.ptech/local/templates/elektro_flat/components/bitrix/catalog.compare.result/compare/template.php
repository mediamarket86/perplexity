<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
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

//PR($arResult);

$isAjax = ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST["ajax_action"]) && $_POST["ajax_action"] == "Y");

$templateData = array(
    'TEMPLATE_THEME' => $this->GetFolder().'/themes/'.$arParams['TEMPLATE_THEME'].'/style.css',
    'TEMPLATE_CLASS' => 'bx_'.$arParams['TEMPLATE_THEME']
);



//добавление из дроп-списка с умным поиском:
//при фокусе: 
// показывается список
//при вводе:
// обновляется список
//при клике по списку - ajax добавление товара в сравнение

?>


<div class="reg-entity wrap comparelist">
     
    <div class="basket">
        <div class="smart-search">
            <p class="title">добавить товары через умный поиск</p>
            <input type="text" name="smart-search"/>
            <!--место для элементов-->
            <ul id="smart_search_to_compare" class="result"></ul>
            <p class="desc">
                начните вводить название товара или артикул, <br/>
                включена автоматическая подсказка
            </p>

    
        </div><!--smart-search-->    
    </div>
</div>


<div class="bx_compare <? echo $templateData['TEMPLATE_CLASS']; ?>" id="bx_catalog_compare_block">
<? if ($isAjax) $APPLICATION->RestartBuffer(); ?>


<div class="table_compare content_in">
<table class="data-table">
<?
$arNotDrawPROPS = array ( "DETAIL_PICTURE", "PREVIEW_PICTURE" );

if (!empty($arResult["SHOW_FIELDS"]))
{
    foreach ($arResult["SHOW_FIELDS"] as $code => $arProp)
    if (!in_array($code, $arNotDrawPROPS))
    {
        $showRow = true;
        if (!isset($arResult['FIELDS_REQUIRED'][$code]) || $arResult['DIFFERENT'])
        {
            $arCompare = array();
            foreach($arResult["ITEMS"] as &$arElement)
            {
                $arPropertyValue = $arElement["FIELDS"][$code];
                if (is_array($arPropertyValue))
                {
                    sort($arPropertyValue);
                    $arPropertyValue = implode(" / ", $arPropertyValue);
                }
                $arCompare[] = $arPropertyValue;
            }
            unset($arElement);
            $showRow = (count(array_unique($arCompare)) > 1);
        }
        if ($showRow)
        {
            ?>
            <tr class="first">
                <td>
                    <p>Сравнить из категории</p>
                    <select name="" id="sections_list">
                       <option value="0" <?=($arResult["SECTION_SELECTED"]=='0' ? 'selected' : '');?>>Все</option>
                    <? foreach($arResult["SECTIONS_INFO"] as $itm) { ?>
                        <option value="<?=$itm['id'];?>" <?=($itm['id']==$arResult["SECTION_SELECTED"] ? 'selected' : '');?>><?=$itm['name'];?></option>
                    <? } ?>    
                    </select>
                    <div class="clear"></div>                
                
                    <?//GetMessage("IBLOCK_FIELD_".$code)?>
                    <div class="razlich">
                        <a href="<? echo $arResult['COMPARE_URL_TEMPLATE'].'DIFFERENT=N'; ?>" class="<?=(!$arResult["DIFFERENT"] ? ' active' : '')?>">
                            <?=(!$arResult["DIFFERENT"] ? 'Показаны' : 'Показать');?> все параметры
                        </a><br>
                        <a href="<? echo $arResult['COMPARE_URL_TEMPLATE'].'DIFFERENT=Y'; ?>" class="<?=($arResult["DIFFERENT"] ? ' active' : '')?>">
                            <?=($arResult["DIFFERENT"] ? 'Показаны' : 'Показать');?> различающиеся
                        </a>
                    </div>                    
                </td>
            <?
            foreach($arResult["ITEMS"] as &$arElement)
            {
        ?>
                <td valign="top">
        <?
                switch($code)
                {
                    case "NAME":
                        ?>
                        <a onclick="CatalogCompareObj.MakeAjaxAction('<?=CUtil::JSEscape($arElement['~DELETE_URL'])?>');" href="javascript:void(0)" class="delete_comp"></a>
                        <div class="prod_item">
                            <a href="<?=$arElement["DETAIL_PAGE_URL"]?>" class="pic_tit">
                                    <p class="pics">
                                        <span>
                                        <img src="<?=$arElement["FIELDS"]["DETAIL_PICTURE"]["SRC"]?>" width="105" 
                                             alt="<?=$arElement["FIELDS"]["DETAIL_PICTURE"]["ALT"]?>"
                                             title="<?=$arElement["FIELDS"]["DETAIL_PICTURE"]["TITLE"]?>" /></span>
                                    </p>
                                    <p class="title"><?=$arElement[$code]?></p>
                            </a>        
                            <s class="old_price">
                                <? if (       isset($arElement['MIN_PRICE']) && is_array($arElement['MIN_PRICE'])  && 
                        $arElement['MIN_PRICE']['PRINT_VALUE'] != $arElement['MIN_PRICE']['PRINT_DISCOUNT_VALUE']
                    )  {
                                    echo $arElement['MIN_PRICE']['PRINT_VALUE'];
                                } else echo '&nbsp;';?>
                            </s>
                            <div class="new_price">
                                <? if (isset($arElement['MIN_PRICE']) && is_array($arElement['MIN_PRICE'])) {
                                    echo $arElement['MIN_PRICE']['PRINT_DISCOUNT_VALUE'];
                                }  else echo '&nbsp;'; ?>
                            </div>
                            <?if($arElement["CAN_BUY"]):?>
                            <a href="<?=$arElement["BUY_URL"]?>" class="tocart">В корзину</a>
                <? else: ?>
                        <a href="#prod_order" class="toorder fancybox">Заказать</a>    
                            <?endif;?>
                        </div>
                        <?                    
                        break;
                    default:
                        echo $arElement["FIELDS"][$code];
                        break;
                }
            ?>
                </td>
            <?
            }
            unset($arElement);
        }
    ?>
    </tr>
    <?
    }
}

if (!empty($arResult["SHOW_OFFER_FIELDS"]))
{
    foreach ($arResult["SHOW_OFFER_FIELDS"] as $code => $arProp)
    {
        $showRow = true;
        if ($arResult['DIFFERENT'])
        {
            $arCompare = array();
            foreach($arResult["ITEMS"] as &$arElement)
            {
                $Value = $arElement["OFFER_FIELDS"][$code];
                if(is_array($Value))
                {
                    sort($Value);
                    $Value = implode(" / ", $Value);
                }
                $arCompare[] = $Value;
            }
            unset($arElement);
            $showRow = (count(array_unique($arCompare)) > 1);
        }
        if ($showRow)
        {
        ?>
        <tr>
            <td><?=$code.": "?><?=GetMessage("IBLOCK_OFFER_FIELD_".$code)?></td>
            <?foreach($arResult["ITEMS"] as &$arElement)
            {
            ?>
            <td>
                <?=(is_array($arElement["OFFER_FIELDS"][$code])? implode("/ ", $arElement["OFFER_FIELDS"][$code]): $arElement["OFFER_FIELDS"][$code])?>
            </td>
            <?
            }
            unset($arElement);
            ?>
        </tr>
        <?
        }
    }
}
?>
<tr>
    <td><?=GetMessage('CATALOG_COMPARE_PRICE');?></td>
    <?
    foreach ($arResult["ITEMS"] as &$arElement)
    {
        if (isset($arElement['MIN_PRICE']) && is_array($arElement['MIN_PRICE']))
        {
            ?><td><? echo $arElement['MIN_PRICE']['PRINT_DISCOUNT_VALUE']; ?></td><?
        }
        else
        {
            ?><td>&nbsp;</td><?
        }
    }
    unset($arElement);
    ?>
</tr>
<?
if (!empty($arResult["SHOW_PROPERTIES"]))
{
    foreach ($arResult["SHOW_PROPERTIES"] as $code => $arProperty)
    if (strpos($code,'CML')!==0)
    {
        $showRow = true;
        if ($arResult['DIFFERENT'])
        {
            $arCompare = array();
            foreach($arResult["ITEMS"] as &$arElement)
            {
                $arPropertyValue = $arElement["DISPLAY_PROPERTIES"][$code]["VALUE"];
                if (is_array($arPropertyValue))
                {
                    sort($arPropertyValue);
                    $arPropertyValue = implode(" / ", $arPropertyValue);
                }
                $arCompare[] = $arPropertyValue;
            }
            unset($arElement);
            $showRow = (count(array_unique($arCompare)) > 1);
        }

        if ($showRow)
        {
            ?>
            <tr>
                <td><!-- <?=$code;?> --><?=$arProperty["NAME"]?></td>
                <?foreach($arResult["ITEMS"] as &$arElement)
                {
                    ?>
                    <td>
                        <?=(is_array($arElement["DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"])? implode("/ ", $arElement["DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"]): $arElement["DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"])?>
                    </td>
                <?
                }
                unset($arElement);
                ?>
            </tr>
        <?
        }
    }
}

if (!empty($arResult["SHOW_OFFER_PROPERTIES"]))
{
    foreach($arResult["SHOW_OFFER_PROPERTIES"] as $code=>$arProperty)
    {
        $showRow = true;
        if ($arResult['DIFFERENT'])
        {
            $arCompare = array();
            foreach($arResult["ITEMS"] as &$arElement)
            {
                $arPropertyValue = $arElement["OFFER_DISPLAY_PROPERTIES"][$code]["VALUE"];
                if(is_array($arPropertyValue))
                {
                    sort($arPropertyValue);
                    $arPropertyValue = implode(" / ", $arPropertyValue);
                }
                $arCompare[] = $arPropertyValue;
            }
            unset($arElement);
            $showRow = (count(array_unique($arCompare)) > 1);
        }
        if ($showRow)
        {
        ?>
        <tr>
            <td><?=$arProperty["NAME"]?></td>
            <?foreach($arResult["ITEMS"] as &$arElement)
            {
            ?>
            <td>
                <?=(is_array($arElement["OFFER_DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"])? implode("/ ", $arElement["OFFER_DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"]): $arElement["OFFER_DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"])?>
            </td>
            <?
            }
            unset($arElement);
            ?>
        </tr>
        <?
        }
    }
}

/*    
    <tr> 
        <td></td>
        <?foreach($arResult["ITEMS"] as &$arElement)
        {
        ?>
        <td>
            <a onclick="CatalogCompareObj.MakeAjaxAction('<?=CUtil::JSEscape($arElement['~DELETE_URL'])?>');" href="javascript:void(0)"><?=GetMessage("CATALOG_REMOVE_PRODUCT")?></a>
        </td>
        <?
        } 
        unset($arElement);
        ?>
    </tr>
*/
?>    
</table>
</div>
<?
if ($isAjax)
{
    die();
}
?>
</div>
<script type="text/javascript">
    var CatalogCompareObj = new BX.Iblock.Catalog.CompareClass("bx_catalog_compare_block");
    
    $(document).ready(function(){
        
        $("#sections_list").change(function(){
           console.log('sel_sect: ' + $(this).val());
           document.location.href = '/compare/?sel_cat=' + $(this).val();
        });
    });    
</script>