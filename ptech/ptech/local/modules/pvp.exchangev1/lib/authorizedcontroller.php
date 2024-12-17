<?php


namespace PVP\ExchangeV1;


class AuthorizedController extends Controller
{
    public function __construct($param)
    {
        global $USER;

        parent::__construct($param);

        if ($this->hasError()) return;

        if ($USER->IsAuthorized()) {
            return;
        }

        if (empty($this->data['LOGIN']) || empty($this->data['PASSWORD'])) {
            $this->addError('Тербуются учетные данные для авторизации!');
        } else {
            global $USER;

            $result = $USER->Login($this->data['LOGIN'], $this->data['PASSWORD']);

            if (true !== $result) {
                $this->addError($result['MESSAGE']);

                return;
            }

        }
    }
}