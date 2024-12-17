<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
    "NAME" => GetMessage("PVP_EXCHANGE_NAME"),
    "DESCRIPTION" => GetMessage("PVP_EXCHANGE_DESC"),
    "CACHE_PATH" => "Y",
    "PATH" => array(
        "ID" => "PVP",
        "CHILD" => array(
            "ID" => "exchange",
            "NAME" => GetMessage("PVP_EXCHANGE_GROUP_NAME")
        )
    ),
);
?>