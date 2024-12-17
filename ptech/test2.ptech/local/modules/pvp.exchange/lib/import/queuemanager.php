<?php

namespace PVP\Exchange\Import;

use PVP\Exchange\ErrorManager;
use PVP\Exchange\ORM\ImportQueueLogTable;
use PVP\Exchange\Orm\ImportQueueTable;
use PVP\Exchange\Response\Response;

class QueueManager
{
    protected $executeLimit = 15;

    public function setData($entityType, $method, $data)
    {
        if ($this->hasQueue($entityType) || 1 < count($data)) {
            $queued = 0;
            foreach ($data as $dataItem) {
               if ($this->addToQueue($entityType, $method, $dataItem)) {
                   $queued++;
               }
            }

            Response::getInstance()->setStatusCode(Response::STATUS_QUEUED)
                ->setResponseData('Поставлено в очередь ' . $entityType . '::' . $method . '(' . $queued . ')');
        } else {
            foreach ($data as $dataItem) {
                $this->execute($entityType, $method, $dataItem);
            }
        }
    }

    public function hasQueue($entityType): bool
    {
        return (bool)ImportQueueTable::getCount([
            ImportQueueTable::FIELD_ENTITY_TYPE => $entityType
        ]);
    }

    public function queueExecute()
    {
        $actions = ImportQueueTable::getList([
            'order' => ['ID' => 'ASC'],
            'limit' => $this->executeLimit,
        ])->fetchAll();

        $errorManager = ErrorManager::getInstance();

        if (empty($actions)) {
            return false;
        }

        foreach ($actions as $action) {
            if ($errorManager->hasErrors()) {
                throw new \Exception('Произошла ошибка до начала импорта');
            }

            try {
                $this->execute(
                    $action[ImportQueueTable::FIELD_ENTITY_TYPE],
                    $action[ImportQueueTable::FIELD_METHOD],
                    $action[ImportQueueTable::FIELD_DATA]
                );
            } catch (\Error $exception) {
                ErrorManager::getInstance()->addError([[
                    'DATA' => $action,
                    'EXCEPTION' => [
                            $exception->getMessage(),
                            $exception->getCode(),
                            $exception->getFile(),
                            $exception->getTrace(),
                    ],
                ]]);
            }

            if ($errorManager->hasErrors()) {
                foreach ($errorManager->getErrors() as $error) {
                    ImportQueueLogTable::add([
                        ImportQueueLogTable::FIELD_ENTITY_TYPE => $action[ImportQueueTable::FIELD_ENTITY_TYPE],
                        ImportQueueLogTable::FIELD_ENTITY_METHOD => $action[ImportQueueTable::FIELD_METHOD],
                        ImportQueueLogTable::FIELD_DATA => $action[ImportQueueTable::FIELD_DATA],
                        ImportQueueLogTable::FIELD_MESSAGE => $error,
                    ]);
                }

                $errorManager->clearErrors();
            }

            ImportQueueTable::delete($action[ImportQueueTable::FIELD_ID]);
        }

        return true;
    }

    public function execute($entityType, $method, array $data)
    {
            \PVP\Exchange\Container::getInstance()->make($entityType)
                ->{$method}($data);
    }

    public function addToQueue($entityType, $method, $data):bool
    {
        $result = ImportQueueTable::add([
            ImportQueueTable::FIELD_ENTITY_TYPE => $entityType,
            ImportQueueTable::FIELD_METHOD => $method,
            ImportQueueTable::FIELD_DATA => $data,
        ]);

        if ($result->isSuccess()) {
            return true;
        }

        \PVP\Exchange\ErrorManager::getInstance()->addError($result->getErrorMessages());
        return false;
    }
}