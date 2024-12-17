<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
    "NAME" => GetMessage("PVP_SMS_AUTH_NAME"),
    "DESCRIPTION" => GetMessage("PVP_SMS_AUTH_DESC"),
    "CACHE_PATH" => "Y",
    "PATH" => array(
        "ID" => "PVP",
        "CHILD" => array(
            "ID" => "smsauth",
            "NAME" => GetMessage("PVP_SMS_AUTH_NAME")
        )
    ),
);
?>