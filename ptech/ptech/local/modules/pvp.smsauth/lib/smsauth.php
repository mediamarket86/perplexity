<?php

namespace PVP\SmsAuth;

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Type\DateTime;
use PVP\Exchange\ErrorManager;
use PVP\SmsAuth\sender\SenderInterface;

class SmsAuth
{

    protected string $moduleCode = 'pvp.smsauth';
    protected int $messageLimit = 5; //sms limit
    protected int $sendTimeout = 60; //Seconds
    protected int $resetLimitsTimeout = 600; //Seconds
    protected int $codeTtl = 300; //Seconds
    protected int $codeCheckLimit = 30; //Лимит проверок кода
    protected SenderInterface $sender;



    public function __construct()
    {
        if (! Loader::includeModule('pvp.exchange')) {
            throw new \Exception('Модуль не установлен' . 'pvp.exchange');
        }

        Loc::loadLanguageFile(__FILE__);
    }

    public function setMessageLimit(int $messageLimit): self
    {
        $this->messageLimit = $messageLimit;

        return  $this;
    }

    /**
     * @return int
     */
    public function getMessageLimit(): int
    {
        return $this->messageLimit;
    }

    /**
     * @param int $sendTimeout
     */
    public function setSendTimeout(int $sendTimeout): self
    {
        $this->sendTimeout = $sendTimeout;

        return $this;
    }

    /**
     * @return int
     */
    public function getSendTimeout(): int
    {
        return $this->sendTimeout;
    }

    /**
     * @param int $resetLimitsTimeout
     */
    public function setResetLimitsTimeout(int $resetLimitsTimeout): self
    {
        $this->resetLimitsTimeout = $resetLimitsTimeout;

        return $this;
    }

    /**
     * @return int
     */
    public function getResetLimitsTimeout(): int
    {
        return $this->resetLimitsTimeout;
    }

    /**
     * @param int $codeTtl
     */
    public function setCodeTtl(int $codeTtl): self
    {
        $this->codeTtl = $codeTtl;

        return $this;
    }

    /**
     * @return int
     */
    public function getCodeTtl(): int
    {
        return $this->codeTtl;
    }

    /**
     * @param SenderInterface $sender
     */
    public function setSender(SenderInterface $sender): self
    {
        $this->sender = $sender;

        return $this;
    }

    /**
     * @return SenderInterface
     */
    public function getSender(): SenderInterface
    {
        return $this->sender;
    }

    public function add()
    {

    }

    public function findUserId(string $phone): int
    {
        $phone = $this->formatPhone($phone);

        /**
         * $phone не должен быть пустым, иначе выберет всех пользователей.
         */
        if (! preg_match('/^\+7[0-9]{7,}$/', $phone)) {
            ErrorManager::getInstance()->addError(GetMessage('SMSAUTH_PHONE_NOT_FOUND'));

            return 0;
        }

        $dbItems = UserExtendedTable::getList([
            'filter' => [
                'ACTIVE' => 'Y',
                'BLOCKED' => 'N',
                'PASSWORD_EXPIRED' => 'N',
                'PHONE_AUTH.PHONE_NUMBER' => $phone,
            ],
            'select' => ['ID']
        ])->fetchAll();

        if (empty($dbItems)) {
            ErrorManager::getInstance()->addError(GetMessage('SMSAUTH_PHONE_NOT_FOUND'));

            return 0;
        }

        return $dbItems[0]['ID'];
    }

    public function formatPhone(string $phone)
    {
        return '+' . preg_replace('/[^0-9]/', '', $phone);
    }

    public function findOrCreateAuthRow(int $userId)
    {
        if (! (int)$userId) {
            throw new \Exception('Некорректный User ID.');
        }

        if (! SmsAuthTable::getCount(['USER_ID' => $userId])) {

            $result = SmsAuthTable::add([
                SmsAuthTable::USER_ID => $userId,
                SmsAuthTable::SMS_COUNT => 0,
                SmsAuthTable::CHECK_COUNT => 0,
                SmsAuthTable::LAST_SEND => DateTime::createFromTimestamp(0),
                SmsAuthTable::HASH => '0000000000',
            ]);

            if (! $result->isSuccess()) {
                AddMessage2Log(__FILE__ . PHP_EOL . join(PHP_EOL, $result->getErrorMessages()));

                ErrorManager::getInstance()->addError('System error!');
                return false;
            }
        }

        $smsAuthRow =  SmsAuthTable::getById($userId)->fetchObject();

        return $smsAuthRow;
    }

    public function sendCode(string $phone): bool
    {
        if (! $userId = $this->findUserId($phone)) {
            return false;
        }

        $smsAuthRow = $this->findOrCreateAuthRow($userId);

        $messageSended = $smsAuthRow->get(SmsAuthTable::SMS_COUNT);
        $lastSendTimestamp = $smsAuthRow->get(SmsAuthTable::LAST_SEND)->getTimestamp();

        if ($messageSended >= $this->getMessageLimit()) {
            if (($lastSendTimestamp + $this->getResetLimitsTimeout()) < time()) {
                $messageSended = 0;
                $smsAuthRow->set(SmsAuthTable::SMS_COUNT, $messageSended);
                $result = $smsAuthRow->save();

                if (! $result->isSuccess()) {
                    AddMessage2Log(join(PHP_EOL, $result->getErrorMessages()));
                }

            } else {
                $nextAttemptTime = ceil(($this->getResetLimitsTimeout() + $lastSendTimestamp - time()) / 60);
                $errMsg = sprintf(GetMessage('SMSAUTH_PHONE_MESSAGE_LIMIT_ERR'), $nextAttemptTime);
                ErrorManager::getInstance()->addError($errMsg);

                return false;
            }
        }

        $timeoutToReSend = $lastSendTimestamp + $this->sendTimeout - time();
        if (0 < $timeoutToReSend) {
            $errMsg = sprintf(GetMessage('SMSAUTH_PHONE_SEND_TIMEOUT_ERR'), $timeoutToReSend);
            ErrorManager::getInstance()->addError($errMsg);

            return false;
        }

        $code = random_int(100000, 999999);
        $hash = password_hash($code, PASSWORD_BCRYPT);
        $message = sprintf(GetMessage('SMSAUTH_PHONE_YOUR_CODE'), $code);

        try {
            $result = $this->getSender()->send($phone, $message);
        } catch (\Throwable $e) {
            AddMessage2Log($e->getCode() . PHP_EOL . $e->getMessage() . $e->getFile() . ':' . $e->getLine());
            $result = false;
        }

        if ($result) {
            $smsAuthRow->set(SmsAuthTable::HASH, $hash);
            $smsAuthRow->set(SmsAuthTable::SMS_COUNT, ++$messageSended);
            $smsAuthRow->set(SmsAuthTable::LAST_SEND, new DateTime());
            $smsAuthRow->set(SmsAuthTable::CHECK_COUNT, 0);
            $result = $smsAuthRow->save();

            if ($result->isSuccess()) {
                return true;
            } else {
                AddMessage2Log(join(PHP_EOL, $result->getErrorMessages()));

                return false;
            }
        }

        ErrorManager::getInstance()->addError(GetMessage('SMSAUTH_PHONE_PROVIDER_SEND_ERROR'));
        return false;
    }

    public function login(string $phone, string $code): bool
    {
        if (! $userId = $this->findUserId($phone)) {
            return false;
        }

        $smsAuthRow = $this->findOrCreateAuthRow($userId);

        if ($smsAuthRow->get(SmsAuthTable::CHECK_COUNT) > $this->codeCheckLimit) {
            ErrorManager::getInstance()->addError(GetMessage('SMSAUTH_PHONE_CODE_TTL_ERROR'));

            return false;
        } else {
            $smsAuthRow->set(SmsAuthTable::CHECK_COUNT, ($smsAuthRow->get(SmsAuthTable::CHECK_COUNT) + 1));
            $smsAuthRow->save();
        }

        if (password_verify($code, $smsAuthRow->get(SmsAuthTable::HASH))) {
            //Проверка срока жизни кода
            $lastSendTimestamp = $smsAuthRow->get(SmsAuthTable::LAST_SEND)->getTimestamp();

            if (($lastSendTimestamp + $this->codeTtl) < time()) {
                ErrorManager::getInstance()->addError(GetMessage('SMSAUTH_PHONE_CODE_TTL_ERROR'));

                return false;
            }

            //Aвторизация
            global $USER;

            $authContext = new \Bitrix\Main\Authentication\Context();
            $authContext->setUserId($userId);

            $result = $USER->Authorize($authContext, true);

            return $result;
        }

        $errStr = sprintf(GetMessage('SMSAUTH_PHONE_WRONG_CODE_ERROR') , $code);
        ErrorManager::getInstance()->addError($errStr);

        return false;
    }

    public function getNextSendTimeout($phone): int
    {
        if (! $userId = $this->findUserId($phone)) {
            return false;
        }

        $smsAuthRow = $this->findOrCreateAuthRow($userId);

        $lastSendTimestamp = $smsAuthRow->get(SmsAuthTable::LAST_SEND)->getTimestamp();
        $messageSended = $smsAuthRow->get(SmsAuthTable::SMS_COUNT);

        if ($this->getMessageLimit() <= $messageSended) {
            $intervalInSeconds = $lastSendTimestamp + $this->getResetLimitsTimeout() - time();

            return (0 < $intervalInSeconds ? $intervalInSeconds : 0);
        }

        $intervalInSeconds = $lastSendTimestamp + $this->sendTimeout - time();

        return (0 < $intervalInSeconds ? $intervalInSeconds : 0);
    }
}