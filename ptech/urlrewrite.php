<?php
$arUrlRewrite=array (
  2 => 
  array (
    'CONDITION' => '#^/online/([\\.\\-0-9a-zA-Z]+)(/?)([^/]*)#',
    'RULE' => 'alias=$1',
    'ID' => '',
    'PATH' => '/desktop_app/router.php',
    'SORT' => 100,
  ),
  3 => 
  array (
    'CONDITION' => '#^/bitrix/services/ymarket/#',
    'RULE' => '',
    'ID' => '',
    'PATH' => '/bitrix/services/ymarket/index.php',
    'SORT' => 100,
  ),
  4 => 
  array (
    'CONDITION' => '#^/online/(/?)([^/]*)#',
    'RULE' => '',
    'ID' => '',
    'PATH' => '/desktop_app/router.php',
    'SORT' => 100,
  ),
  31 => 
  array (
    'CONDITION' => '#^/api/internal/v1/#',
    'RULE' => '',
    'ID' => 'pvp:exchange',
    'PATH' => '/api/internal/v1/index.php',
    'SORT' => 100,
  ),
  46 => 
  array (
    'CONDITION' => '#^/restapi/(\\w+)#',
    'RULE' => 'URI=$1',
    'ID' => 'awelite.restapi',
    'PATH' => '/local/php_interface/awelite.restapi/controller_api.php',
    'SORT' => 100,
  ),
  38 => 
  array (
    'CONDITION' => '#^/promotions/#',
    'RULE' => '',
    'ID' => 'bitrix:news',
    'PATH' => '/promotions/index.php',
    'SORT' => 100,
  ),
  32 => 
  array (
    'CONDITION' => '#^/articles/#',
    'RULE' => '',
    'ID' => 'bitrix:news',
    'PATH' => '/articles/index.php',
    'SORT' => 100,
  ),
  35 => 
  array (
    'CONDITION' => '#^/newslist/#',
    'RULE' => '',
    'ID' => 'bitrix:news',
    'PATH' => '/newslist/index.php',
    'SORT' => 100,
  ),
  37 => 
  array (
    'CONDITION' => '#^/personal/#',
    'RULE' => '',
    'ID' => 'bitrix:sale.personal.section',
    'PATH' => '/personal/index.php',
    'SORT' => 100,
  ),
  43 => 
  array (
    'CONDITION' => '#^/test2211/#',
    'RULE' => '',
    'ID' => 'pvp:exchange',
    'PATH' => '/test2211/index.php',
    'SORT' => 100,
  ),
  39 => 
  array (
    'CONDITION' => '#^/reviews/#',
    'RULE' => '',
    'ID' => 'bitrix:news',
    'PATH' => '/reviews/index.php',
    'SORT' => 100,
  ),
  42 => 
  array (
    'CONDITION' => '#^/vendors/#',
    'RULE' => '',
    'ID' => 'bitrix:news',
    'PATH' => '/vendors/index.php',
    'SORT' => 100,
  ),
  44 => 
  array (
    'CONDITION' => '#^/catalog/#',
    'RULE' => '',
    'ID' => 'bitrix:catalog',
    'PATH' => '/catalog/index.php',
    'SORT' => 100,
  ),
  29 => 
  array (
    'CONDITION' => '#^/api/v1/#',
    'RULE' => '',
    'ID' => 'pvp:exchangev1',
    'PATH' => '/api/v1/index.php',
    'SORT' => 100,
  ),
  30 => 
  array (
    'CONDITION' => '#^/api/v2/#',
    'RULE' => '',
    'ID' => 'pvp:exchange',
    'PATH' => '/api/v2/index.php',
    'SORT' => 100,
  ),
  40 => 
  array (
    'CONDITION' => '#^/sales/#',
    'RULE' => '',
    'ID' => 'bitrix:news',
    'PATH' => '/sales/index.php',
    'SORT' => 100,
  ),
  28 => 
  array (
    'CONDITION' => '#^/rest/#',
    'RULE' => '',
    'ID' => NULL,
    'PATH' => '/bitrix/services/rest/index.php',
    'SORT' => 100,
  ),
  34 => 
  array (
    'CONDITION' => '#^/news/#',
    'RULE' => '',
    'ID' => 'bitrix:news',
    'PATH' => '/news/index.php',
    'SORT' => 100,
  ),
  45 => 
  array (
    'CONDITION' => '#^(.*)$#',
    'RULE' => '',
    'ID' => '',
    'PATH' => '/404.php',
    'SORT' => 999999,
  ),
);
