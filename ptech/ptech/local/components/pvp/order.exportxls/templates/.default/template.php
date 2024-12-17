<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="pvp-order-exportxls-block">
    <form class="export-form" name="pvp_order_exportxls_<?=uniqid()?>" id="pvp_order_exportxls_<?=uniqid()?>" method="post">
        <input name="pvp_order_exportxls" value="Y" type="hidden">
        <button type="submit" class="download-btn">
            <span class="export-button-desc"><?=GetMessage('PVP_EXPRT_XLS_SAVE_BTN')?></span>
            <span class="icon"></span>
        </button>
    </form>
</div>

