<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
    "NAME" => GetMessage("PVP_ORD_EXPRT_XLS_NAME"),
    "DESCRIPTION" => GetMessage("PVP_ORD_EXPRT_XLS_DESC"),
    "CACHE_PATH" => "Y",
    "PATH" => array(
        "ID" => "PVP",
        "CHILD" => array(
            "ID" => "order.exportxls",
            "NAME" => GetMessage("PVP_ORD_EXPRT_XLS_NAME")
        )
    ),
);
?>