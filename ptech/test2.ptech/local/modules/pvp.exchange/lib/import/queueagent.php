<?php

namespace PVP\Exchange\Import;

use Bitrix\Main\Loader;
use PVP\Exchange\Container;
use PVP\Exchange\Controllers\OldImportAdapter;
use PVP\Exchange\ErrorManager;
use PVP\Exchange\ORM\ImportQueueLogTable;
use PVP\Exchange\Orm\ImportQueueTable;

class QueueAgent
{
    const MAX_EXECUTION_TIME = 45;

    public static function ImportQueueAgent()
    {
        $start = microtime(true);
        /**
         * @var QueueManager $queueManager
         */
        $queueManager = Container::getInstance()->make(QueueManager::class);

        while ((microtime(true) < ($start + self::MAX_EXECUTION_TIME)) && $queueManager->queueExecute()) {}

        return '\PVP\Exchange\Import\QueueAgent::ImportQueueAgent();';
    }

    /**
     * @return string
     * @throws \Bitrix\Main\LoaderException
     * Берем JSON из файлов 1с конвертируем в человеческий и ставим в очередь через АПИ
     */
    public static function fileToRestAgent()
    {
        $successHttpCodes = [200, 202];
        $errorManager = ErrorManager::getInstance();

        if (! Loader::includeModule('pvp.tools')) {
            throw new \Exception('Модуль не найден pvp.tools');
        }

        $apiUrl = defined('DEV_MODE') ? 'https://matlvm.lvm/api/v2/123321' : 'https://materik-m.ru/api/v2/9d55be90ec9418ba8d3eb0b5a434e8d5c9b13cd0c0c63acef742f28e81d77022';
        $files = [
           '/OldImportAdapter/ElementsNew/' => $_SERVER['DOCUMENT_ROOT'] . '/upload/realweb.sync/elementNew.txt',
           '/OldImportAdapter/treeNew/' => $_SERVER['DOCUMENT_ROOT'] . '/upload/realweb.sync/treeNew.txt',
        ];

        $tmpDirPath = $_SERVER['DOCUMENT_ROOT'] . '/upload/pvp.exchange';


        foreach($files as $method => $file) {
            if (! file_exists($file)) {
                continue;
            }

            $tmpFileName = $tmpDirPath . '/' . basename($file);

            if (filemtime($tmpFileName) > filemtime($file)) {
                continue; //Файл не обновлялся
            }

            if (! copy($file, $tmpFileName)) {
                throw new \Exception('Не удалось создать временный файл ' . $tmpFileName);
            }

            $oldJsonContent = new \PVP\Tools\Import\File\JsonContent(new \PVP\Tools\Import\File\File($tmpFileName));

            $data = $oldJsonContent->decodeToArray();

            if (empty($data)) {
                $errorManager->addError('Файл пуст ' . $tmpFileName);

                continue;
            }

            $query = curl_init($apiUrl . $method);
            curl_setopt_array($query, [
                CURLOPT_POST => true,
                CURLOPT_TIMEOUT => 240,
                CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
                CURLOPT_POSTFIELDS => json_encode($data),
                CURLOPT_RETURNTRANSFER => 1, //возвращается стрингой
                CURLOPT_SSL_VERIFYPEER => 0, //отключаем https или по другому -k
            ]);

            $result = curl_exec($query);
            $queryInfo = curl_getinfo($query);

            if (! in_array((int)$queryInfo['http_code'], $successHttpCodes)) {
                $errorManager->addError([['CURL_RESULT_ERROR' => [
                    'RESULT' => json_decode($result, true),
                    'CURL_ERROR' => curl_error($query),
                    'QUERY_INFO' => $queryInfo,
                    ],
                ]]);
            }
        }

        if ($errorManager->hasErrors()) {
            foreach ($errorManager->getErrors() as $error) {
                ImportQueueLogTable::add([
                    ImportQueueLogTable::FIELD_ENTITY_TYPE => __CLASS__,
                    ImportQueueLogTable::FIELD_ENTITY_METHOD => __METHOD__,
                    ImportQueueLogTable::FIELD_DATA => '',
                    ImportQueueLogTable::FIELD_MESSAGE => $error,
                ]);
            }
        }

        return '\PVP\Exchange\Import\QueueAgent::fileToRestAgent();';
    }
}