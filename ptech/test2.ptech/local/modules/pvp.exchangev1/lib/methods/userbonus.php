<?php


namespace PVP\ExchangeV1\Methods;

use PVP\ExchangeV1\User\User;

class UserBonus extends \PVP\ExchangeV1\Controller
{
    const MIN_BONUS_TO_USE = MIN_BONUS_TO_USE;

    protected $user;

    public function __construct($param)
    {
        parent::__construct($param);

        global $USER;

        $this->user = new User($USER);
    }

    public function get()
    {
        if (! $userId = $this->user->getUserIdByPhoneOrEmail($this->data['phone'])) {
            if (! empty($this->data['email']) && filter_var($this->data['email'], FILTER_VALIDATE_EMAIL)) {
                $userId = $this->user->getUserIdByPhoneOrEmail($this->data['email']);
            }
        }

        if (! $userId) {
            return $this->result = ['userBonuses' => 0];
        }

        $rsUser = $this->user->getUser()->GetByID($userId)->fetch();

        return $this->result = ['userBonuses' => self::MIN_BONUS_TO_USE <= (int)$rsUser['UF_BONUS_POINTS'] ? (int)$rsUser['UF_BONUS_POINTS'] : 0];
    }

    public static function calcMaxBonusesInOrder(float $orderSum, int $bonuses): int
    {
        $maxOrderBonuses = floor($orderSum) - 1;

        return $maxOrderBonuses < $bonuses ? $maxOrderBonuses : $bonuses;
    }
}