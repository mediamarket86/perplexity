<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
    "NAME" => GetMessage("AGROHOLDING_EXCHANGE_NAME"),
    "DESCRIPTION" => GetMessage("AGROHOLDING_EXCHANGE_DESC"),
    "CACHE_PATH" => "Y",
    "PATH" => array(
        "ID" => "PVP",
        "CHILD" => array(
            "ID" => "exchange",
            "NAME" => GetMessage("PVP_GROUP_NAME")
        )
    ),
);
?>