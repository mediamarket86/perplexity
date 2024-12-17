<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
    "NAME" => GetMessage("PVP_FAVORITES_NAME"),
    "DESCRIPTION" => GetMessage("PVP_FAVORITES_DESC"),
    "CACHE_PATH" => "Y",
    "PATH" => array(
        "ID" => "PVP",
        "CHILD" => array(
            "ID" => "favorites",
            "NAME" => GetMessage("PVP_FAVORITES_NAME")
        )
    ),
);
?>