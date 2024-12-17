<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
    "NAME" => GetMessage("PVP_CHECKCODE_NAME"),
    "DESCRIPTION" => GetMessage("PVP_CHECKCODE_DESC"),
    "CACHE_PATH" => "Y",
    "PATH" => array(
        "ID" => "PVP",
        "CHILD" => array(
            "ID" => "checkcode",
            "NAME" => GetMessage("PVP_CHECKCODE_NAME")
        )
    ),
);
?>