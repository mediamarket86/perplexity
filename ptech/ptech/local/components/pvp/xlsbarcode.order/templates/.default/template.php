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

<div class="example-container">
    Скачать <a href="/upload/barcode_import_template.xls" download>Шаблон документа для заполнения.</a>
</div><br>
<form class="excel-container" name="excel_form" action="<?= $APPLICATION->GetCurPage()?>" method="post" enctype="multipart/form-data">
    <div class="file-upload">
        <input type="file" name="excel_file" id="excel_file" class="visually-hidden" required />
        <label for="excel_file">
    <span class="btn_buy" style="font-size:13px"> выбрать файл</span>
     
        </label>
		 <button class="excel-load-button btn_buy" style="font-size:12px">&rdca; загрузить в корзину</button>
		    <span style="background-color: #e5b0b0; color:#000"> - выберите файл формата .xls</span>
    </div>
</form>

