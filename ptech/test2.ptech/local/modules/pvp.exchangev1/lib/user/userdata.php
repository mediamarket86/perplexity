<?php


namespace PVP\ExchangeV1\User;


use Bitrix\Main\Context;

class UserData
{
    protected $data;

    public function __construct(string $phone)
    {
        $phone = $this->preparePhoneData($phone);

        $this->data['LOGIN'] = $phone;
        $this->data['PERSONAL_PHONE'] = $phone;

        if ('Y' == \COption::GetOptionString("main", "new_user_phone_required", 'N')) {
            $this->data['PHONE_NUMBER'] = $this->data['PERSONAL_PHONE'];
        }

        $userGroups = \COption::GetOptionString("main", "new_user_registration_def_group", []);

        if (is_string($userGroups)) {
            $userGroups = explode(',', $userGroups);
        }

        $this->data['GROUP_ID'] = $userGroups;

        $this->data['PASSWORD'] = \Bitrix\Main\Security\Random::getString(8);
        $this->data['CONFIRM_PASSWORD'] = $this->data['PASSWORD'];

        $this->data['ACTIVE'] = 'Y';
        $this->data['LID'] = Context::getCurrent()->getSite();
    }

    protected function preparePhoneData(string $phone)
    {
        $phone = preg_replace('/[^0-9]+/', '', $phone);

        if (! preg_match('/^7/', $phone) && 10 == mb_strlen($phone)) {
            $phone = '7' . $phone;
        }

        if (11 != mb_strlen($phone)) {
            Throw new \RuntimeException('Неверный номер телефона: ' . $phone);
        }

        return $phone;

    }

    public function setUserNames(string $firstName, string $lastName = '', string $surName = '')
    {
        $this->setField('NAME', $firstName);
        $this->setField('LAST_NAME', $lastName);
        $this->setField('SECOND_NAME', $surName);
    }

    /**
     * @param string $key
     * @param string $value
     * @return bool
     * Позволяет создать любое параметр и установить его значение
     */
    public function setField(string $key, $value): bool
    {
        if (! empty($value)) {
            $this->data[$key] = $value;

            return true;
        }

        return false;
    }

    public function setEmail(string $email): bool
    {
        $email = filter_var($email, FILTER_VALIDATE_EMAIL);

        return $this->setField('EMAIL', $email);
    }

    public function getUserData()
    {
        return $this->data;
    }
}