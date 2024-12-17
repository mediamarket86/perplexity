<?php if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

if (isset($arResult['RESPONSE'])) {
    \PVP\Exchange\Response\Response::getInstance()->setResponseData($arResult['RESPONSE']);
}