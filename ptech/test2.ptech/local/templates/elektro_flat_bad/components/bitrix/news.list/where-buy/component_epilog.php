<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

\Bitrix\Main\Page\Asset::getInstance()->addJs('https://api-maps.yandex.ru/2.1/?apikey=432181d8-5894-491b-b8f1-1c5b50b8db4a&lang=ru_RU');
$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();

$partnerId = $request->get('partnerId');
?>
<?php if ($partnerId) : ?>
    <script>
        BX.ready(function () {
            window.whereBuyPage.goToStoreList('<?=$partnerId?>');
        });
    </script>
<?php endif; ?>
