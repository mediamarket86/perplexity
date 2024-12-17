<?php

namespace PVP\Exchange\Traits;

use Bitrix\Main\Application;


trait GetRequestDataTrait
{
	   public function getData(): array
		{
			$request = Application::getInstance()->getContext()->getRequest();
			
			if ($this->arParams['DEBUG_MODE'] === 'Y') {
				$this->errorManager->addError("DEBUG: Request method: " . $request->getRequestMethod());
				$this->errorManager->addError("DEBUG: Request URI: " . $request->getRequestUri());
			}
		
			if ($request->isPost()) {
				$input = file_get_contents('php://input');
				if ($this->arParams['DEBUG_MODE'] === 'Y') {
					$this->errorManager->addError("DEBUG: Raw input data: " . $input);
				}
				if (!empty($input)) {
					$data = json_decode($input, true);
					if ($this->arParams['DEBUG_MODE'] === 'Y') {
						$this->errorManager->addError("DEBUG: Decoded data: " . print_r($data, true));
					}
					return is_array($data) ? $data : [];
				}
			}
		
			if ($this->arParams['DEBUG_MODE'] === 'Y') {
				$this->errorManager->addError("DEBUG: No input data found");
			}
			return [];
		}


}

/*
trait GetRequestDataTrait
{
    public function getData(): array
    {
        $request = Application::getInstance()->getContext()->getRequest();

        if ($request->isPost() && $input = file_get_contents('php://input')) {
            return json_decode($input, true);
        }

        return [];
    }
}
*/