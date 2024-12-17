<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';
?>
<?php

$loginResult = $USER->Login(
    $_POST['USER_LOGIN'],
    $_POST['USER_PASSWORD'],
    'Y',
);

$result = [];

if (true === $loginResult) {
    $result['success'] = true;
} else {
    $result['error'] = $loginResult['MESSAGE'];
}

echo json_encode($result);
?>
<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_after.php';
?>