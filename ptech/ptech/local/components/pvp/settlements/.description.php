<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
    "NAME" => GetMessage("PVP_SETTLEMENTS_NAME"),
    "DESCRIPTION" => GetMessage("PVP_SETTLEMENTS_DESC"),
    "CACHE_PATH" => "Y",
    "PATH" => array(
        "ID" => "PVP",
        "CHILD" => array(
            "ID" => "settlements",
            "NAME" => GetMessage("PVP_SETTLEMENTS_NAME")
        )
    ),
);
?>