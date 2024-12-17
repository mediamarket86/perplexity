<?php

namespace PVP\Exchange\Controller\Authorization;

use Bitrix\Main\Localization\Loc;
use PVP\Exchange\Controller\ControllerInterface;
use PVP\Exchange\ErrorManager;
use PVP\Exchange\Response\Response;
use PVP\Exchange\Traits\GetRequestDataTrait;
use PVP\Exchange\JWT\JWTManager;
use PVP\SmsAuth\SmsAuth;

class JWTAuth implements ControllerInterface
{
    use GetRequestDataTrait;

    protected Response $response;
    protected ErrorManager $errorManager;
    protected JWTManager $jwtManager;

    public function __construct()
    {
        $this->response = Response::getInstance();
        $this->errorManager = ErrorManager::getInstance();
        $this->jwtManager = new JWTManager();

        Loc::loadLanguageFile(__FILE__);
    }

    public static function needAdminRights(): bool
    {
        return false;
    }

    public static function needAuthorization(): bool
    {
        return false;
    }

    public function getApiMethodList(string $httpMethod): array
    {
        switch($httpMethod) {
            case 'POST':
                return ['login', 'refresh', 'forgot', 'sendSmsCode', 'smsCodeAuth'];
                break;
            case 'GET':
                return [];
                break;
            default:
                return [];
        }
    }

public function login(): void
{
    $data = $this->getData();
    
    if ($this->arParams['DEBUG_MODE'] === 'Y') {
        $this->errorManager->addError("DEBUG: Received login data: " . print_r($data, true));
    }

    if (empty($data['login']) || empty($data['password'])) {
        $this->response->setStatusCode(Response::STATUS_ERROR);
        $this->errorManager->addError("Отсутствуют обязательные поля login или password");
        return;
    }

    global $USER;
    $result = $USER->Login($data['login'], $data['password']);
    
    if (true !== $result) {
        $this->response->setStatusCode(Response::STATUS_ERROR);
        $this->errorManager->addError(strip_tags($result['MESSAGE']));
        
        // Добавляем отладочную информацию
        if ($this->arParams['DEBUG_MODE'] === 'Y') {
            $this->errorManager->addError("DEBUG: Login failed. Error: " . print_r($result, true));
        }
        return;
    }

    $tokens = $this->jwtManager->create($USER->GetID());
    Response::getInstance()->setResponseData($tokens);
    
    // Добавляем отладочную информацию об успешном входе
    if ($this->arParams['DEBUG_MODE'] === 'Y') {
        $this->errorManager->addError("DEBUG: Login successful for user ID: " . $USER->GetID());
    }
}


    public function refresh()
    {
        $data = $this->getData();

        if (empty($data['token'])) {
            $this->errorManager->addError(GetMessage('PVP_EX_JWTAUTH_ERR_FIELDS_NOT_FOUND'));

            return;
        }

        $tokens = $this->jwtManager->refresh($data['token']);

        if ($this->errorManager->hasErrors()) {
            return;
        }

        Response::getInstance()->setResponseData($tokens);
    }

    public function forgot()
    {
        $data = $this->getData();

        if (empty($data['login'])) {
            $this->errorManager->addError(GetMessage('PVP_EX_JWTAUTH_ERR_FIELDS_NOT_FOUND'));

            return;
        }

        global $USER;
        $result = $USER->SendPassword($data['login'], $data['login']);

        if ('ERROR' == $result['TYPE']) {
            $this->errorManager->addError(strip_tags($result['MESSAGE']));
        }

        Response::getInstance()->setResponseData(strip_tags($result['MESSAGE']));
    }

    public function sendSmsCode()
    {
        $data = $this->getData();

        if (empty($data['phone'])) {
            $this->errorManager->addError(GetMessage('PVP_EX_JWTAUTH_ERR_FIELDS_NOT_FOUND'));

            return;
        }

        if (! \Bitrix\Main\Loader::includeModule('pvp.smsauth')) {
            throw new \Exception('Модуль не найден: pvp.smsauth');
        }

        $smsAuth = new SmsAuth();
        $smsAuth->setSender(new \PVP\SmsAuth\Sender\SmscSender());


        $smsAuth->sendCode($data['phone']);
    }

    public function smsCodeAuth()
    {
        if (! \Bitrix\Main\Loader::includeModule('pvp.smsauth')) {
            throw new \Exception('Модуль не найден: pvp.smsauth');
        }

        $data = $this->getData();

        if (empty($data['phone']) || empty($data['code'])) {
            $this->errorManager->addError(GetMessage('PVP_EX_JWTAUTH_ERR_FIELDS_NOT_FOUND'));

            return;
        }

        $smsAuth = new SmsAuth();
        $smsAuth->setSender(new \PVP\SmsAuth\Sender\SmscSender());
        $smsAuth->setCodeTtl(30);//считать код действительным 30 сек с момента отправки

        if ($smsAuth->login($data['phone'], $data['code'])) {
            global $USER;

            $tokens = $this->jwtManager->create($USER->GetID());

            Response::getInstance()->setResponseData($tokens);
        }

    }
}