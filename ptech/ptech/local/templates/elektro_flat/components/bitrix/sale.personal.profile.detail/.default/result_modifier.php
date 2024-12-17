<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

//ID_PROFILE HIDE
foreach ($arResult["ORDER_PROPS"] as $key => $propsGroup) {
    foreach ($propsGroup['PROPS'] as $level2key => $property) {
        if ('ID_PROFILE' == $property['CODE']) {
            unset($arResult["ORDER_PROPS"][$key]['PROPS'][$level2key]);
        }
    }
}

