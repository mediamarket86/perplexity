<?php
$path = str_replace($_SERVER['DOCUMENT_ROOT'], '', __DIR__);
$path .= DIRECTORY_SEPARATOR . 'js' . DIRECTORY_SEPARATOR . 'cleave' . DIRECTORY_SEPARATOR . 'cleave.min.js';

\CJSCore::RegisterExt("pvp_cleave", Array(
    "js" =>    $path,
));

