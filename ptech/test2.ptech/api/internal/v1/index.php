<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';
?>

<?$APPLICATION->IncludeComponent(
	"pvp:exchange",
	"",
	Array(
		"AUTH_METHOD" => "UF",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO",
		"DEBUG_MODE" => "N",
		"SEF_FOLDER" => "/api/internal/v1/",
		"SEF_MODE" => "Y",
		"SEF_URL_TEMPLATES" => Array("method"=>"#CONTROLLER#/#METHOD#/"),
		"USER_TOKEN_FIELD" => "UF_AUTH_TOKEN"
	)
);?>

<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_after.php';
?>