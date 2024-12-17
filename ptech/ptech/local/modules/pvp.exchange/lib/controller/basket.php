<?php

namespace PVP\Exchange\Controller;

use Bitrix\Catalog\MeasureRatioTable;
use Bitrix\Currency\CurrencyManager;
use Bitrix\Main\Context;
use Bitrix\Main\Loader;
use Bitrix\Sale\BasketBase;
use Bitrix\Sale\Fuser;
use PVP\Exchange\ErrorManager;
use PVP\Exchange\ExchangeComponent;
use PVP\Exchange\Response\Response;
use PVP\Exchange\Traits\GetRequestDataTrait;
use PVP\Exchange\Traits\XmlIdToElementIdTrait;
use PVP\Exchange\Traits\BarCodeToElementIdTrait;
use PVP\Exchange\Traits\ArticleToElementIdTrait;

class Basket implements ControllerInterface
{
  use GetRequestDataTrait, XmlIdToElementIdTrait, BarCodeToElementIdTrait, ArticleToElementIdTrait;

  protected BasketBase $basket;
  protected int $fuser;
  protected int $iblockId;

  public function __construct()
  {
    $this->iblockId = ExchangeComponent::getInstance()->getCatalogParam('IBLOCK_ID');

    $this->fuser = Fuser::getId();
    $this->basket = \Bitrix\Sale\Basket::loadItemsForFUser($this->fuser, \Bitrix\Main\Context::getCurrent()->getSite());
  }

  public function getApiMethodList(string $httpMethod): array
  {
    switch ($httpMethod) {
      case 'POST':
        return ['add', 'delete', 'loadFromExcel'];
        break;
      case 'GET':
        return ['getAll', 'clear'];
        break;
      default:
        return [];
    }
  }

  public function getAll(): void
  {
    if (!Loader::includeModule('pvp.favorites')) {
      ErrorManager::getInstance()->addError('Module pvp.favorites not found');

      return;
    }

    $currentUser = \Bitrix\Main\Engine\CurrentUser::get()->getId();
    $request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();
    $getParams = $request->getQueryList()->toArray();

    $this->basket->refresh();
    $order = \Bitrix\Sale\Order::create(
      SITE_ID,
      $currentUser,
      CurrencyManager::getBaseCurrency()

    );

    $order->setBasket($this->basket);
    $order->doFinalAction();


    $basket = $this->basket->toArray();

    $favorites = new \PVP\Favorites\Favorites();
    $productInFavorites = $favorites->getForUser($currentUser, $this->iblockId);

    $productIds = array_column($basket, 'PRODUCT_ID');

    $dbItems = MeasureRatioTable::getList([
      'filter' => ['PRODUCT_ID' => $productIds]
    ])->fetchAll();

    $measureRatios = array_column($dbItems, null, 'PRODUCT_ID');

    $dbItems = \CIBlockElement::GetList(
      [],
      [
        'ID' => $productIds,
        'IBLOCK_ID' => ExchangeComponent::getInstance()->getCatalogParam('IBLOCK_ID'),
        'CATALOG_AVAILABLE' => 'Y'
      ],
      false,
      false,
      ['ID', 'XML_ID', 'PREVIEW_PICTURE', 'PROPERTY_CML2_ARTICLE']
    );

    $additionalProperties = [];
    while ($res = $dbItems->GetNext()) {
      $additionalProperties[$res['ID']] = $res;
    }

    foreach ($basket as $key => $item) {
      if ('N' === $item['CAN_BUY']) {
        unset($basket[$key]);
        continue;
      }

      if (empty($additionalProperties[$item['PRODUCT_ID']])) {
        continue;
      }

      $basket[$key]['PRICE'] = round($basket[$key]['PRICE'], 2);
      $basket[$key]['CATALOG_QUANTITY'] = $additionalProperties[$item['PRODUCT_ID']]['CATALOG_QUANTITY'];
      if (!empty($additionalProperties[$item['PRODUCT_ID']]['PREVIEW_PICTURE'])) {
        $picture = \CFile::GetByID($additionalProperties[$item['PRODUCT_ID']]["PREVIEW_PICTURE"])->Fetch();
        $basket[$key]['ADDITIONAL_PROPERTIES']['PREVIEW_PICTURE'] = $picture;
      }
      $basket[$key]['ADDITIONAL_PROPERTIES']['PROPERTY_CML2_ARTICLE_VALUE'] = empty($additionalProperties[$item['PRODUCT_ID']]['PROPERTY_CML2_ARTICLE_VALUE']) ? '' : $additionalProperties[$item['PRODUCT_ID']]['PROPERTY_CML2_ARTICLE_VALUE'];
      $basket[$key]['ADDITIONAL_PROPERTIES']['XML_ID'] = empty($additionalProperties[$item['PRODUCT_ID']]['XML_ID']) ? '' : $additionalProperties[$item['PRODUCT_ID']]['XML_ID'];
      $basket[$key]['ADDITIONAL_PROPERTIES']['CATALOG_MEASURE_RATIO'] = empty($measureRatios[$item['PRODUCT_ID']]) ? 1 : $measureRatios[$item['PRODUCT_ID']]['RATIO'];
      $basket[$key]['ADDITIONAL_PROPERTIES']['IN_FAVORITES'] = in_array($item['PRODUCT_ID'], $productInFavorites);
    }

    sort($basket);

    $data = $getParams;
    if ($data['export']) {
      $filename = "basket_export123.csv";
      $exportPath = $_SERVER["DOCUMENT_ROOT"] . "/upload/basket_export " . date("YmdHsi") . ".csv";
      $fp = fopen($exportPath, 'w');
      fprintf($fp, chr(0xEF) . chr(0xBB) . chr(0xBF));
      fputcsv($fp, ["Артикул", "Количество"], ';');
      foreach ($basket as $key => $value) {
        fputcsv($fp, [$value["ADDITIONAL_PROPERTIES"]["PROPERTY_CML2_ARTICLE_VALUE"], $value["QUANTITY"]], ';');
      }

      rewind($fp);
      fpassthru($fp);
      fclose($fp);

      ob_clean();
      output_reset_rewrite_vars();
      header('Content-type: text/csv; charset=UTF-8');
      header('Content-Disposition: attachment;filename="' . $filename . '";');
      echo "\xEF\xBB\xBF";
      $exportFile = file_get_contents($exportPath);
      print_r($exportFile);
      //unlink($exportPath);
      die();
    }

    Response::getInstance()->setResponseData($basket);
  }

  public function add(): void
  {
    $data = $this->getData();

    if (is_array($data)) {
      foreach ($data as $item) {
        if ((!$item['xmlId']) && (!$item['barCode']) && (!$item['article'])) {
          ErrorManager::getInstance()->addError('В запросе не найден xmlId или barCode');
          continue;
        }
        if ($item['barCode']) {
          $elementId = $this->barCodeToElementId($item['barCode'], $this->iblockId);
        } elseif ($item['article']) {
          $elementId = $this->articleToElementId($item['article'], $this->iblockId);
        } else {
          $elementId = $this->xmlIdToElementId($item['xmlId'], $this->iblockId);
        }
        if ($elementId) {
          $quantity = empty($item['quantity']) ? 1 : (int)$item['quantity'];
          $this->add2basket($elementId, $quantity);
        } else {
          ErrorManager::getInstance()->addError('Элемент не найден: ' . $item['xmlId']);
        }
      }
    } else {
      ErrorManager::getInstance()->addError('Неверный запрос');
    }
  }

  public function clear()
  {
    foreach ($this->basket->getBasketItems() as $item) {
      /** @var \Bitrix\Sale\Result $result */
      $result = $item->delete();

      if (!$result->isSuccess()) {
        ErrorManager::getInstance()->addError($result->getErrorMessages());
      }
    }

    $this->basket->save();
  }

  public function delete(): void
  {
    $data = $this->getData();

    if (is_array($data)) {
      foreach ($data as $item) {
        if (empty($item['xmlId'])) {
          ErrorManager::getInstance()->addError('В запросе не найден xmlId');
          continue;
        }

        if ($elementId = $this->xmlIdToElementId($item['xmlId'], $this->iblockId)) {
          $this->deleteFromBasket($elementId);
        } else {
          ErrorManager::getInstance()->addError('Элкмент не найден: ' . $item['xmlId']);
        }
      }
    } else {
      ErrorManager::getInstance()->addError('Неверный запрос');
    }
  }

  public function loadFromExcel()
  {
    $data = $this->getData();

    if (!isset($data['file'])) {
      ErrorManager::getInstance()->addError('Неверный запрос');
      return;
    }

    $content = base64_decode($data['file']);

    $fileName = dirname(__DIR__, 2) . '/tmp/' . uniqid('', true);
    file_put_contents($fileName, $content);

    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
    try {
      $spreadsheet = $reader->load($fileName);
    } catch (\Exception $e) {
      ErrorManager::getInstance()->addError('Не удалось загрузить таблицу из файла.');
      unlink($fileName);

      return;
    }

    unlink($fileName);

    $productArray = [];
    $activeSheetArray = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
    foreach ($activeSheetArray as $rowNumber => $sheetRow) {
      if ($rowNumber === 1) {
        foreach ($sheetRow as $column) {
          if (is_string($column)) {
            $rows = array_slice($spreadsheet->getActiveSheet()->toArray(null, true, true, true), 1);
          } else {
            $rows = $activeSheetArray;
          }
          break;
        }
      }
    }

    foreach ($rows as $row) {
      if ($row['A'] && $row['B']) {
        if (!$productArray[$row['A']]) {
          $productArray[$row['A']] = (int)$row['B'];
        } else {
          $productArray[$row['A']] += (int)$row['B'];
        }
      }
    }

    if (empty($productArray)) {
      ErrorManager::getInstance()->addError('Неверный формат файла');

      return;
    }

    $result = [];
    foreach ($productArray as $productVendorCode => $quantity) {
      $product = \CIBlockElement::GetList(
        [],
        [
          '=IBLOCK_CODE'           => 'catalog_main',
          '=PROPERTY_CML2_ARTICLE' => trim($productVendorCode),
          '=ACTIVE' => 'Y'
        ],
        false,
        false,
        [
          'IBLOCK_ID',
          'ID',
          'PROPERTY_MINIMALNOE_KOLICHESTVO'
        ]
      )->Fetch();
      $productId = $product['ID'];
      $minProductCountForOrder = $product['PROPERTY_MINIMALNOE_KOLICHESTVO_VALUE'];

      if (empty($productId)) {
        $result[] = [
          'status' => 'error',
          'message' => 'Товар с артикулом ' . $productVendorCode . ' не найден',
        ];

        continue;
      }


      // $result[] = [
      //   'status' => 'error',
      //   'message' => $minProductCountForOrder . '+' . $quantity,
      // ];
      // continue;
      if(($minProductCountForOrder > 1) && ($quantity > 0) && ($quantity % $minProductCountForOrder > 0)){
        $result[] = [
          'status' => 'error',
          'message' => 'Товар с артикулом ' . $productVendorCode . ' заявлен в неправильном количестве. Количество должно быть кратным ' . $minProductCountForOrder,
        ];

        continue;
      }

      /** @var \Bitrix\Sale\BasketItem $item */
      foreach ($this->basket->getBasketItems() as $item) {
        if ($item->getProductId() == $productId) {

          $item->setField('QUANTITY', $item->getQuantity() + $quantity);
          $item->save();

          $result[] = [
            'status' => 'success',
            'message' => 'Количество товара с артикулом ' . $productVendorCode . ' обновлено',
          ];

          continue (2);
        }
      }

      $isAddedToBasket = $this->add2basket($productId, $quantity);
      if ($isAddedToBasket['isSuccess']) {
        $result[] = [
          'status' => 'success',
          'message' => 'Товар с артикулом ' . $productVendorCode . ' добавлен в корзину',
        ];
      } else {
        $result[] = [
          'status' => 'error',
          'message' => 'Товар с артикулом ' . $productVendorCode . ' НЕ добавлен в корзину: ' . implode(';', $isAddedToBasket['ErrorMessage']),
        ];
      }
    }

    Response::getInstance()->setResponseData($result);
  }

  protected function add2basket($elementId, $quantity): array
  {
    $item = null;
    /** @var \Bitrix\Sale\BasketItem $existItem */
    foreach ($this->basket->getBasketItems() as $existItem) {
      if ($elementId == $existItem->getProductId()) {
        $item = $existItem;
      }
    }

    if ($item) {
      $quantity += (int)$item->getField('QUANTITY');
      $item->setField('QUANTITY', $quantity);
    } else {
      //add new products
      $item = $this->basket->createItem('catalog', $elementId);
      $item->setFields(array(
        //'QUANTITY' => intval($quantity),
        'CURRENCY' => CurrencyManager::getBaseCurrency(),
        'LID' => Context::getCurrent()->getSite(),
        'PRODUCT_PROVIDER_CLASS' => '\Bitrix\Catalog\Product\CatalogProvider',
      ));
      $isQuantityValid = $item->setField('QUANTITY', intval($quantity));
      if (!$isQuantityValid->isSuccess()) {

        return ['isSuccess' => $isQuantityValid->isSuccess(), 'ErrorMessage' => $isQuantityValid->getErrorMessages()];
      }
    }

    $result = $item->save();


    return ['isSuccess' => $result->isSuccess(), 'ErrorMessage' => $result->getErrorMessages()];
  }

  protected function deleteFromBasket(int $elementId): void
  {
    /** @var \Bitrix\Sale\BasketItem $item */
    foreach ($this->basket->getBasketItems() as $item) {
      if ($elementId == $item->getProductId()) {
        $item->delete();
      }
    }

    $this->basket->save();
  }

  public static function needAdminRights(): bool
  {
    return false;
  }

  public static function needAuthorization(): bool
  {
    return true;
  }
}
