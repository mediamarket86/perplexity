<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
    "NAME" => GetMessage("PVP_CHECKLOG_NAME"),
    "DESCRIPTION" => GetMessage("PVP_CHECKLOG_DESC"),
    "CACHE_PATH" => "Y",
    "PATH" => array(
        "ID" => "PVP",
        "CHILD" => array(
            "ID" => "checkcode",
            "NAME" => GetMessage("PVP_CHECKLOG_NAME")
        )
    ),
);
?>