<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
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
?>
<p> Уважаемые клиенты, для удобства оформления заказа вы можете использовать загрузку товаров из Excel файла с расширением .xlsx

</p>
<div class="example-container">
    Скачать <a href="/upload/excel_order_template.xlsx" download>Шаблон документа для заполнения.</a>
</div><br>
<form class="excel-container" name="excel_form" action="<?= $APPLICATION->GetCurPage()?>" method="post" enctype="multipart/form-data">
    <input type="hidden" name="IS_AJAX" value="Y"/>
    <div class="file-upload">
        <input type="file" name="excel_file" required="required" id="excel_file" class="visually-hidden" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" name="excel_file" />
        <label for="excel_file">
    <span class="btn_buy" style="font-size:13px"> выбрать файл</span>
     
        </label>
		 <button class="excel-load-button btn_buy" style="font-size:12px">&rdca; загрузить в корзину</button>
		    <span style="background-color: #e5b0b0; color:#000"> - выберите формат файла .xlsx</span>
    </div>
</form>
<div class="ptech-ecxcel-order messages" style="padding: 20px 0">
</div>

