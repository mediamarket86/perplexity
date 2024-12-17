<?php

namespace PVP\Exchange\JWT;

use Bitrix\Main\Config\Option;
use Bitrix\Main\Type\DateTime;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use PVP\Exchange\ErrorManager;
use PVP\Exchange\Orm\JWTRefreshTable;

class JWTManager
{
    protected string $moduleId = 'pvp.exchange';
    protected string $privateKey;
    protected string $publicKey;
    protected string $cipherAlg;
    protected string $jwtAlg = 'RS256';

    protected string $refreshTTL;
    protected string $accessTTL;

    protected ErrorManager $errorManager;

    public function __construct()
    {
        if (empty($this->publicKey = Option::get($this->moduleId, 'public_key'))) {
            throw new \Exception('Public key empty');
        }

        if (empty($this->privateKey = Option::get($this->moduleId, 'private_key'))) {
            throw new \Exception('Private key empty');
        }

        if (empty($this->cipherAlg = Option::get($this->moduleId, 'cipher_alg'))) {
            $this->cipherAlg = openssl_get_cipher_methods()[0];
        }

        $this->accessTTL = Option::get($this->moduleId, 'access_ttl', 6);
        $this->refreshTTL = Option::get($this->moduleId, 'access_ttl', 6);

        if (! (int)$this->accessTTL || ! (int)$this->refreshTTL) {
            throw new \Exception('Ошибка настройки модуля');
        }

        $this->errorManager = ErrorManager::getInstance();
    }

    public function create(int $userId): array
    {
        $jti = $this->createTokenId($userId);
        $rtExpire = $this->getRefreshExpire();
        /**
         * @var \Bitrix\Main\ORM\Objectify\EntityObject $jwtRefreshRow
         */
        $jwtRefreshRow = JWTRefreshTable::createObject();
        $jwtRefreshRow->set(JWTRefreshTable::USER_ID, $userId);
        $jwtRefreshRow->set(JWTRefreshTable::HASH, $jti);
        $jwtRefreshRow->set(JWTRefreshTable::EXPIRE, DateTime::createFromTimestamp($rtExpire));

        $result = $jwtRefreshRow->save();

        if (! $result->isSuccess()) {
            throw new \Exception(implode('  ', $result->getErrorMessages()));
        }

        $tokens = $this->generateTokens($userId, $jwtRefreshRow->getId());

        return $tokens;
    }

    public function refresh(string $token): array
    {
        $refreshToken = $this->decode($token);

        if (empty($refreshToken['azp'])) {
            $this->errorManager->addError('Недействительный токен');

            return [];
        }

        $userId = $this->decrypt($refreshToken['azp']);

        $jwtRowCollection = JWTRefreshTable::getList([
            'filter' => [
                JWTRefreshTable::USER_ID => $userId,
                JWTRefreshTable::HASH => $refreshToken['jti'],
            ]
        ])->fetchCollection();

        if (! $jwtRowCollection->count() || ! $this->checkExpiration($refreshToken) ) {
            $this->errorManager->addError('Недействительный токен');

            return [];
        }

        $jwtRow = $jwtRowCollection->current();

        $tokens = $this->generateTokens($userId, $jwtRow->getId());

        return $tokens;
    }

    public function getAuthId(string $token): int
    {
        try {
            $authToken = $this->decode($token);
        } catch (\Throwable $e) {
            return 0;
        }

        if (empty($authToken['azp'])) {
            return 0;
        }

        $userId = $this->decrypt($authToken['azp']);

        return (int)$userId;
    }

    public function encode(array $payload): string
    {
        return JWT::encode($payload, $this->privateKey, $this->jwtAlg);
    }

    public function decode(string $token): array
    {
        return (array)JWT::decode($token, new Key($this->publicKey, $this->jwtAlg));
    }

    public function encrypt(string $data): string
    {
        $result = openssl_encrypt($data, $this->cipherAlg, $this->privateKey);

        if (empty($result)) {
            throw new \Exception('Не удалось зашифровать строку, попробуйте сменить алгоритм шифрования в настройках модуля, т.к. не все они подходят!');
        }

        return $result;
    }

    public function decrypt(string $data): string
    {
        return openssl_decrypt($data, $this->cipherAlg, $this->privateKey);
    }

    protected function generateTokens(int $userId, int $refreshTokenRowId): array
    {
        $rtPayload = $jwtPayload = $this->getPayload($userId, $this->getAccessExpire());
        $jwt = $this->encode($jwtPayload);

        $rtExpire =  $this->getRefreshExpire();
        $jti = $this->createTokenId($userId);

        $result = JWTRefreshTable::update($refreshTokenRowId, [
            JWTRefreshTable::EXPIRE => DateTime::createFromTimestamp($rtExpire),
            JWTRefreshTable::HASH => $jti,
        ]);

        if (! $result->isSuccess()) {
            throw new \Exception(implode('  ', $result->getErrorMessages()));
        }

        $rtPayload['exp'] = $rtExpire;
        $rtPayload['jti'] = $jti;

        $rt = $this->encode($rtPayload);

        return ['access' => $jwt, 'refresh' => $rt];
    }

    protected function getPayload(int $userId, int $expire): array
    {
        $issuedAt   = new \DateTimeImmutable();

        $payload = [
            'aud' => ['pvp-bitrix-ecom-api', $_SERVER['HTTP_HOST']],
            'iss' => $_SERVER['HTTP_HOST'],
            'iat' => $issuedAt->getTimestamp(),
            'nbf' => $issuedAt->getTimestamp(),
            'exp' => $expire,
            'azp' => $this->encrypt($userId),
        ];

        return $payload;
    }

    protected function createTokenId(int $userId): string
    {
        return $this->encrypt('U' .$userId . 'TUID' . uniqid(random_int(10000000, 9999999999), true));
    }

    protected function getAccessExpire()
    {
        $time = new \DateTimeImmutable();
        $expTime = $time->modify('+' . $this->accessTTL . ' hour');

        return $expTime->getTimestamp();
    }

    protected function getRefreshExpire()
    {
        $time = new \DateTimeImmutable();
        $expTime = $time->modify('+' . $this->refreshTTL . ' month');

        return $expTime->getTimestamp();
    }

    protected function checkExpiration(array $payload): bool
    {
        $time = time();
        if ($payload['exp'] < $time || $payload['nbf'] > $time) {
            return false;
        }

        return true;
    }
}