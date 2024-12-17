<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?php
    $APPLICATION->restartBuffer();

    echo json_encode($arResult['RESULT']);
    die();
?>

