<?php
use Bitrix\Main\Application;
use Bitrix\Main\Localization\Loc;

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?php
$response = Application::getInstance()->getContext()->getResponse();
$request = Application::getInstance()->getContext()->getRequest();
?>
<?php
//SORT//
    $arAvailableSort = array(
        "default" => array('SORT', 'asc'),
        "price" => array("CATALOG_PRICE_12", "asc"),
        "rating" => array("PROPERTY_rating", "desc"),
    );

    $sort = $request->getCookie("sort") ? $request->getCookie("sort") : 'SORT';
    $sort_order = $request->getCookie("order") ? $request->getCookie("order") : 'asc';

    //AJAX_MODE//
    if($arParams["AJAX_MODE"] == "Y") {
        if($_REQUEST["sort"])
            $_SESSION["sort"] = $_REQUEST["sort"];

        if($_SESSION["sort"])
            $_REQUEST["sort"] = $_SESSION["sort"];
    }

    if($_REQUEST["sort"]) {
        $sort = 'SORT';
        $response->addCookie(new \Bitrix\Main\Web\Cookie("sort", $sort, false, "/", SITE_SERVER_NAME));
    }
    if($_REQUEST["sort"] == "article") {
        $sort = "PROPERTY_CML2_ARTICLE";
        $response->addCookie(new \Bitrix\Main\Web\Cookie("sort", $sort, null, "/", SITE_SERVER_NAME));
    }
    if($_REQUEST["sort"] == "price") {
        $sort = "CATALOG_PRICE_12";
        $response->addCookie(new \Bitrix\Main\Web\Cookie("sort", $sort, false, "/", SITE_SERVER_NAME));
    }
    if($_REQUEST["sort"] == "rating") {
        $sort = "PROPERTY_rating";
        $response->addCookie(new \Bitrix\Main\Web\Cookie("sort", $sort, false, "/", SITE_SERVER_NAME));
    }
    //AJAX_MODE//
    if($arParams["AJAX_MODE"] == "Y") {
        if($_REQUEST["order"])
            $_SESSION["order"] = $_REQUEST["order"];

        if($_SESSION["order"])
            $_REQUEST["order"] = $_SESSION["order"];
    }

    if($_REQUEST["order"]) {
        $sort_order = "asc";
        $response->addCookie(new \Bitrix\Main\Web\Cookie("order", $sort_order, false, "/", SITE_SERVER_NAME));
    }
    if($_REQUEST["order"] == "desc") {
        $sort_order = "desc";
        $response->addCookie(new \Bitrix\Main\Web\Cookie("order", $sort_order, false, "/", SITE_SERVER_NAME));
    }

?>

<div class="pvp-favorite-block favorite-list">
    <div class="sort-filter">
        <div class="action-wrap section-sort">
            <div>
                <label class="label"><span class="full"><?=Loc::getMessage("SECT_SORT_LABEL_FULL")?></span>:</label>
            </div>
            <div>
                <?foreach($arAvailableSort as $key => $val) {
                    $className = $sort == $val[0] ? "selected" : "";
                    if($className)
                        $className .= $sort_order == "asc" ? " asc" : " desc";
                    $newSort = $sort == $val[0] ? $sort_order == "desc" ? "asc" : "desc" : $arAvailableSort[$key][1];?>

                    <a href="<?=$APPLICATION->GetCurPageParam("sort=".$key."&amp;order=".$newSort, array("sort", "order"))?>" class="section-sort__link <?=$className?>" rel="nofollow"><?=Loc::getMessage("SECT_SORT_".$key)?></a>
                <?}?>
            </div>
        </div>

        <div class="action-wrap section-filter">
            <label class="label"><span class="full"><?=Loc::getMessage("SECT_FILTER_LABEL_FULL")?></span>:</label>
            <div class="section-filter-controls">
                <div class="list-header">
                    <a class="list-header__link" href="javascript:void(0);">Категориям</a>
                </div>
                <div class="section-list">
                    <div class="list-wrap">
                        <?php foreach ($arResult['SECTIONS'] as $section) :?>
                            <div class="section-list-item">
                                <div class="name-wrap">
                                    <span class="section-list-item__icon">
                                        <?php if ($section['ICON_SRC']) :?>
                                        <img class="icon" src="<?=$section['ICON_SRC']?>">
                                        <?php endif ?>
                                    </span>
                                    <span class="section-list-item__name"><?=$section['NAME']?></span>
                                </div>
                                <div class="controls">
                                    <label class="controls__label <?=in_array($section['ID'], $arResult['FILTER_SECTION']) ? 'active' : ''?>" for="section-<?=$section['ID']?>"></label>
                                    <input class="controls__checkbox" data-section-id="<?=$section['ID']?>" id="section-<?=$section['ID']?>" type="checkbox" <?=in_array($section['ID'], $arResult['FILTER_SECTION']) ? 'checked' : ''?>>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="catalog-loading">
                <span class="catalog loading placeholder">
                    <span class="lds-ring"><span></span><span></span><span></span><span></span></span>
                </span>
    </div>
    <div class="favorite-items">
        <?php include("include/section.php");?>
    </div>
</div>

