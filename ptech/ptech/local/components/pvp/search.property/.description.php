<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
    "NAME" => GetMessage("PVP_SEARCH_PROPERTY_NAME"),
    "DESCRIPTION" => GetMessage("PVP_SEARCH_PROPERTY_DESC"),
    "CACHE_PATH" => "Y",
    "PATH" => array(
        "ID" => "PVP",
        "CHILD" => array(
            "ID" => "search.vendor.code",
            "NAME" => GetMessage("PVP_SEARCH_PROPERTY_NAME")
        )
    ),
);
?>