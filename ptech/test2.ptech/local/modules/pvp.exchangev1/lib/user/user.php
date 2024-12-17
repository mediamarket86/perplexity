<?php


namespace PVP\ExchangeV1\User;

use Bitrix\Main\UserTable;

class User
{

    protected $user;
    protected $errors;

    public function __construct(\CUser $user)
    {
        $this->user = $user;
    }

    public function createUserAndAuthorize(UserData $userData)
    {
        $userId = $this->createUser($userData);

        return $this->user->Authorize($userId);
    }

    public function getUser()
    {
        return $this->user;
    }

    public function authorizeByContactData(string $data)
    {
        if ($userId = $this->getUserIdByPhoneOrEmail($data)) {
            return $this->user->Authorize($userId);
        }

        return false;
    }

    public function getUserIdByPhoneOrEmail(string $login) : int
    {
        $userId = 0;

        if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
            $userId = $this->getUserIdByEmail($login);
        } elseif (preg_match('/[0-9]+/', $login)) {
            $userId = $this->getUserIdByPhone($login);
        }

        return $userId;
    }

    protected function getUserIdByPhone(int $phone): int
    {
        $user = UserTable::getList([
            'select' => ['ID'],
            'filter' => [
                'LOGIC' => 'OR',
                ['=PERSONAL_PHONE' => $phone],
                ['=LOGIN' => $phone],
                ['=PHONE_AUTH.PHONE_NUMBER' => $phone]
            ]
        ])->fetchAll();

        return $user ? $user['0']['ID'] : 0;

    }

    protected function getUserIdByEmail(string $email)
    {
        $user = UserTable::getList([
            'select' => ['ID'],
            'filter' => [
                'LOGIC' => 'OR',
                ['=LOGIN' => $email],
                ['=EMAIL' => $email],
            ]
        ])->fetchAll();

        return $user ? $user['0']['ID'] : 0;
    }

    public function createUser(UserData $userData): int
    {
        $userId = $this->user->Add($userData->getUserData());

        if (! $userId) {
            AddMessage2Log($this->user->LAST_ERROR);
        }

        return (int)$userId;
    }

    public function logout()
    {
        try {
            $this->user->Logout();
        } catch (\RuntimeException $e) {
            AddMessage2Log($e->getMessage());
        }
    }

    /**
     * @return array список телефонов пользователей вида PHONE_NUMBER => USER_ID
     */

    public function getUserPhoneList() : array
    {
        $dbItems = \Bitrix\Main\UserTable::getList([
            'select' => ['ID', 'LOGIN', 'PERSONAL_PHONE', 'PHONE_AUTH.PHONE_NUMBER']
        ]);

        $userPhoneList = [];

        while ($user = $dbItems->fetch()) {
            $phone = empty($user['MAIN_USER_PHONE_AUTH_PHONE_NUMBER']) ? $user['PERSONAL_PHONE'] : $user['MAIN_USER_PHONE_AUTH_PHONE_NUMBER'];

            if ($phone) {
                $phone = preg_replace('/[^0-9]/', '', $phone);

                $userPhoneList[$phone] = (int)$user['ID'];
            }
        }

        return $userPhoneList;
    }
}