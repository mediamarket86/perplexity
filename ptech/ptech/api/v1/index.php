<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';
?><?$APPLICATION->IncludeComponent(
	"pvp:exchangev1",
	"",
	Array(
		"AUTH_STRING" => "6b2829aaa95ea56a7930a3500f6f6a16",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO",
		"SEF_FOLDER" => "/api/v1/",
		"SEF_MODE" => "Y",
		"SEF_URL_TEMPLATES" => Array("method"=>"#AUTH#/#CONTROLLER#/#METHOD#","methodWithSlash"=>"#AUTH#/#CONTROLLER#/#METHOD#/"),
		"USER_TOKEN_FIELD" => "UF_AUTH_TOKEN"
	)
);?>