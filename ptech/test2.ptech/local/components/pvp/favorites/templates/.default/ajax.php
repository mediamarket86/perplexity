<?php
use Bitrix\Main\Application;
use Bitrix\Main\Localization\Loc;

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?php
$response = Application::getInstance()->getContext()->getResponse();
$request = Application::getInstance()->getContext()->getRequest();
$APPLICATION->RestartBuffer();
?>
<?php
$sort = $request->getCookie("sort") ? $request->getCookie("sort") : 'SORT';
$sort_order = $request->getCookie("order") ? $request->getCookie("order") : 'asc';

Loc::loadLanguageFile($_SERVER['DOCUMENT_ROOT' ] . $templateFolder . '/template.php');

include('include/section.php');
?>